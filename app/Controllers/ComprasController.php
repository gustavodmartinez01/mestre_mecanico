<?php

namespace App\Controllers;

use App\Models\ComprasRequisicaoModel;
use App\Models\ComprasItemModel;
use App\Models\ContasPagarModel;
use App\Models\FinanceiroMovimentacaoModel;
use App\Models\EstoqueMovimentacaoModel;
use App\Models\FornecedorModel; // Para carregar a lista no form
use App\Models\ProdutoModel; // Adicionado
use App\Models\ServicoModel; // Adicionado
use App\Libraries\PdfLib;
use App\Models\EmpresaModel;
use CodeIgniter\Controller;

class ComprasController extends BaseController
{
    protected $reqModel;
    protected $itemModel;
    protected $pagarModel;
    protected $movModel;
    protected $estoqueModel;
    protected $fornModel;
    protected $prodModel;
    protected $servModel;

    public function __construct()
    {
        $this->reqModel     = new ComprasRequisicaoModel();
        $this->itemModel    = new ComprasItemModel();
        $this->pagarModel   = new ContasPagarModel();
        $this->movModel     = new FinanceiroMovimentacaoModel();
        $this->estoqueModel = new EstoqueMovimentacaoModel();
        $this->fornModel    = new FornecedorModel();
        $this->prodModel    = new ProdutoModel();
        $this->servModel    = new ServicoModel();
    }

    /**
     * Listagem das Requisições (View: compras_requisicao_v.php)
     */
    public function index()
    {
        $data['requisicoes'] = $this->reqModel->getDetalhada();
        return view('compras/compras_requisicao_v', $data);
    }

    /**
     * Formulário de Nova Requisição
     */
    public function nova()
    {
        $data['fornecedores'] = $this->fornModel->where('empresa_id', session()->get('empresa_id'))->findAll();
        return view('compras/compras_requisicao_form_v', $data);
    }

    /**
     * Formulário de Edição
     */
    public function editar($id)
    {
        $requisicao = $this->reqModel->find($id);
        
        if (!$requisicao || $requisicao['status'] !== 'aberta') {
            return redirect()->to('/compras')->with('error', 'Requisição não encontrada ou já finalizada.');
        }

        $data = [
            'requisicao'   => $requisicao,
            'itens'        => $this->itemModel->where('requisicao_id', $id)->findAll(),
            'fornecedores' => $this->fornModel->where('empresa_id', session()->get('empresa_id'))->findAll()
        ];

        return view('compras/compras_requisicao_form_v', $data);
    }

    /**
     * Método Unificado para Salvar e Atualizar
     */
    public function salvar($id = null)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $dadosReq = [
            'empresa_id'      => session()->get('empresa_id'),
            'fornecedor_id'   => $this->request->getPost('fornecedor_id'),
            'usuario_id'      => session()->get('usuario_id'),
            'data_requisicao' => $this->request->getPost('data_requisicao'),
            'valor_total'     => $this->request->getPost('total_geral'),
            'observacoes'     => $this->request->getPost('observacoes'),
            'status'          => 'aberta'
        ];

        if ($id) {
            $this->reqModel->update($id, $dadosReq);
            $id_requisicao = $id;
        } else {
            $id_requisicao = $this->reqModel->insert($dadosReq);
        }

        // Lógica de Itens (Sincronização)
        $item_ids   = $this->request->getPost('item_id'); // IDs existentes
        $descricoes = $this->request->getPost('item_descricao');
        $quantidades = $this->request->getPost('item_quantidade');
        $valores    = $this->request->getPost('item_valor');
        $produtos   = $this->request->getPost('produto_id');

        // 1. Remover itens que foram excluídos na interface (Edição)
        if ($id) {
            $ids_no_form = array_filter($item_ids);
            $query_delete = $this->itemModel->where('requisicao_id', $id);
            if (!empty($ids_no_form)) {
                $query_delete->whereNotIn('id', $ids_no_form);
            }
            $query_delete->delete();
        }

        // 2. Inserir ou Atualizar Itens
        foreach ($descricoes as $index => $desc) {
            $dadosItem = [
                'requisicao_id'  => $id_requisicao,
                'produto_id'     => !empty($produtos[$index]) ? $produtos[$index] : null,
                'descricao_item' => $desc,
                'quantidade'     => $quantidades[$index],
                'valor_unitario' => $valores[$index]
                
            ];

            if (!empty($item_ids[$index])) {
                $this->itemModel->update($item_ids[$index], $dadosItem);
            } else {
                $this->itemModel->insert($dadosItem);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Erro ao processar requisição.');
        }

        return redirect()->to('/compras')->with('success', 'Requisição salva com sucesso!');
    }

    /**
     * Tela de Fechamento (Conferência)
     */
    public function fechar($id)
    {
        $requisicao = $this->reqModel->find($id);
        if (!$requisicao || $requisicao['status'] == 'finalizada') {
            return redirect()->to('/compras')->with('error', 'Esta requisição não pode ser fechada.');
        }

        $data = [
            'requisicao' => $requisicao,
            'itens'      => $this->itemModel->where('requisicao_id', $id)->findAll()
        ];

        return view('compras/fechamento_requisicao_v', $data);
    }

    /**
     * Finalização Real (Gera Financeiro e Estoque)
     */
    public function finalizar_requisicao()
    {
        $id_req = $this->request->getPost('id_requisicao');
        $itens_atendidos = $this->request->getPost('item_status'); // IDs dos itens marcados
        $ja_pago = $this->request->getPost('pago_agora') == 'sim';

        $db = \Config\Database::connect();
        $db->transStart();

        $itens = $this->itemModel->where('requisicao_id', $id_req)->findAll();
        $valor_final_compra = 0;

        foreach ($itens as $it) {
            $status = in_array($it['id'], $itens_atendidos) ? 'atendido' : 'nao_atendido';
            $this->itemModel->update($it['id'], ['situacao' => $status]);

            if ($status == 'atendido') {
                $valor_item = $it['quantidade'] * $it['valor_unitario'];
                $valor_final_compra += $valor_item;

                // Movimentação de Estoque
                if ($it['produto_id']) {
                    $this->estoqueModel->insert([
                        'empresa_id' => session()->get('empresa_id'),
                        'produto_id' => $it['produto_id'],
                        'tipo'       => 'entrada',
                        'quantidade' => $it['quantidade'],
                        'valor_unitario' => $it['valor_unitario'],
                        'origem_tabela' => 'compras_requisicoes',
                        'origem_id' => $id_req,
                        'descricao' => "Entrada ref. Req #$id_req"
                    ]);
                }
            }
        }

        // Gera Contas a Pagar
        $id_conta = $this->pagarModel->insert([
            'empresa_id'      => session()->get('empresa_id'),
            'fornecedor_id'   => $this->request->getPost('fornecedor_id'),
            'descricao'       => "Compra ref. Requisição #$id_req",
            'valor_original'  => $valor_final_compra,
            'data_vencimento' => $this->request->getPost('data_vencimento'),
            'status'          => $ja_pago ? 'paga' : 'pendente',
            'data_pagamento'  => $ja_pago ? date('Y-m-d') : null,
            'id_agrupador'    => 'REQ-'.$id_req
        ]);

        // Se pago, registra saída no caixa
        if ($ja_pago && $id_conta) {
            $this->movModel->registrar([
                'empresa_id'        => session()->get('empresa_id'),
                'tipo'              => 'saida',
                'descricao'         => "PAGTO REQ #$id_req",
                'valor'             => $valor_final_compra,
                'data_movimentacao' => date('Y-m-d'),
                'origem_tabela'     => 'contas_pagar',
                'origem_id'         => $id_conta
            ]);
        }

        // Finaliza a Requisição
        $this->reqModel->update($id_req, [
            'status' => 'finalizada',
            'valor_total' => $valor_final_compra,
            'data_fechamento' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
           // return $this->response->setJSON(['status' => false, 'msg' => 'Erro ao finalizar requisição.']);
            return redirect()->back()->with('error', 'Erro ao finalizar requisição.');
           }

       // return $this->response->setJSON(['status' => true, 'msg' => 'Compra finalizada com sucesso!']);
        return redirect()->to('/compras')->with('success', 'Compra finalizada com sucesso!');
       }

/* Busca AJAX unificada de Produtos e Serviços
     */
    public function buscar_itens_ajax()
    {
        $busca = $this->request->getGet('term');

        // Busca Produtos (coluna 'descricao' conforme instrução técnica)
        $produtos = $this->prodModel
            ->select('id, descricao as text, preco_custo as preco, "produto" as tipo')
            ->like('descricao', $busca)
            ->findAll(10);

        // Busca Serviços
        $servicos = $this->servModel
            ->select('id, nome as text, preco_custo as preco, "servico" as tipo')
            ->like('nome', $busca)
            ->findAll(10);

        // Une os resultados em um único array para o Select2
        $resultado = array_merge($produtos, $servicos);

        return $this->response->setJSON($resultado);
    }
    /**
 * Exclui uma requisição e seus itens vinculados
 */
public function excluir($id)
{
    // 1. Busca a requisição para verificar o status
    $requisicao = $this->reqModel->find($id);

    if (!$requisicao) {
        return redirect()->to('/compras')->with('error', 'Requisição não encontrada.');
    }

    // 2. Trava de segurança: só exclui se estiver aberta
    if ($requisicao['status'] !== 'aberta') {
        return redirect()->to('/compras')->with('error', 'Não é possível excluir uma requisição que já foi finalizada ou cancelada.');
    }

    $db = \Config\Database::connect();
    $db->transStart();

    // 3. Primeiro excluímos os itens (chave estrangeira ou vínculo lógico)
    $this->itemModel->where('requisicao_id', $id)->delete();

    // 4. Depois excluímos a requisição principal
    $this->reqModel->delete($id);

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->to('/compras')->with('error', 'Erro interno ao tentar excluir a requisição.');
    }

    return redirect()->to('/compras')->with('success', 'Requisição #' . $id . ' excluída com sucesso.');
}
public function detalhes($id)
{
    // Busca a requisição com o nome do fornecedor
    $requisicao = $this->reqModel
        ->select('compras_requisicoes.*, fornecedores.nome_razao as nome_fornecedor')
        ->join('fornecedores', 'fornecedores.id = compras_requisicoes.fornecedor_id', 'left')
        ->find($id);

    if (!$requisicao) {
        return redirect()->to('/compras')->with('error', 'Requisição não encontrada.');
    }

    $data = [
        'requisicao' => $requisicao,
        'itens'      => $this->itemModel->where('requisicao_id', $id)->findAll()
    ];

    return view('compras/detalhes_requisicao_v', $data);
}
public function imprimir($id)
{
    // 1. Busca a requisição
    $requisicao = $this->reqModel
        ->select('compras_requisicoes.*, fornecedores.nome_razao as nome_fornecedor')
        ->join('fornecedores', 'fornecedores.id = compras_requisicoes.fornecedor_id', 'left')
        ->find($id);

    if (!$requisicao) {
        return redirect()->back()->with('error', 'Requisição não encontrada.');
    }

    // 2. Busca dados da EMPRESA (Usando o model correto)
    $empresaModel = new EmpresaModel(); 
    $empresa = $empresaModel->first(); // Pega o primeiro registro de configuração

    $itens = $this->itemModel->where('requisicao_id', $id)->findAll();

    // 3. Renderiza a View
    $html =   view('compras/impressao_requisicao_pdf_v', [
        'requisicao' => $requisicao,
        'itens'      => $itens,
        'empresa'    => $empresa
    ]);

    // 4. PdfLib
    $pdf = new \App\Libraries\PdfLib();
    $nomeArquivo = "requisicao_" . str_pad($id, 5, '0', STR_PAD_LEFT) . ".pdf";
    $pdf->gerar($html, $nomeArquivo, 'I');
}


/**
 * Redireciona para o WhatsApp do fornecedor com o link da requisição
 */
public function whatsapp($id)
{
    // 1. Busca a requisição com os dados do fornecedor
    $requisicao = $this->reqModel
        ->select('compras_requisicoes.*, fornecedores.nome_razao, fornecedores.celular')
        ->join('fornecedores', 'fornecedores.id = compras_requisicoes.fornecedor_id', 'left')
        ->find($id);

    if (!$requisicao) {
        return redirect()->back()->with('error', 'Requisição não encontrada.');
    }

    // 2. Validação do Celular do Fornecedor
    if (empty($requisicao['celular'])) {
        return redirect()->back()->with('error', 'O fornecedor não possui celular cadastrado!');
    }

    // Limpa o número (apenas dígitos)
    $celular = preg_replace('/[^0-9]/', '', $requisicao['celular']);
    
    // Adiciona o código do Brasil (55) se necessário
    if (strlen($celular) >= 10 && strlen($celular) <= 11) {
        $celular = "55" . $celular;
    }

    // 3. Geração do Link Seguro (Hash Hexadecimal)
    $sal = "mestre_compras_2026"; // Sal exclusivo para o módulo de compras
    $hash = bin2hex($id . "-" . $sal);
    $linkPublico = base_url("view/requisicao/{$hash}");

    // 4. Montagem da Mensagem
    $mensagem = "Olá, *{$requisicao['nome_razao']}*! 📦\n\n";
    $mensagem .= "Segue os detalhes da nossa *Requisição de Compra #{$id}*.\n\n";
    $mensagem .= "Você pode visualizar o documento completo no link abaixo:\n";
    $mensagem .= $linkPublico . "\n\n";
    $mensagem .= "Por favor, nos retorne com o orçamento ou confirmação de entrega. Obrigado!";

    // 5. URL do WhatsApp
    $urlFinal = "https://api.whatsapp.com/send?phone={$celular}&text=" . urlencode($mensagem);

    return redirect()->to($urlFinal);
}

/**
 * Visualização pública da Requisição (PDF) via Hash
 */
public function visualizar_publico($hash)
{
    try {
        // Converte de hexadecimal para string
        $decodificado = hex2bin($hash);
        
        // Separa o ID do Sal
        $partes = explode("-", $decodificado);
        $id = $partes[0];
        $salOriginal = $partes[1] ?? '';

        // Validação de Segurança (mesmo Sal usado no método whatsapp)
        if (!is_numeric($id) || $salOriginal !== "mestre_compras_2026") {
            throw new \Exception("Link inválido.");
        }

        // Chama o método imprimir que já configuramos com a PdfLib
        return $this->imprimir($id);

    } catch (\Exception $e) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Link expirado ou inexistente.");
    }
}

}