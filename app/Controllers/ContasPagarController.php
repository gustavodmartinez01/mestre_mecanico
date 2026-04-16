<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ContasPagarModel;
use App\Models\EmpresaModel;
use App\Models\FinanceiroCategoriaModel;
use App\Models\FinanceiroCentroCustoModel;
use App\Models\FinanceiroMovimentacaoModel;


class ContasPagarController extends BaseController
{
    protected $contaPagarModel;
    protected $movModel;

    public function __construct() {
        $this->contaPagarModel = new ContasPagarModel();
        $this->movModel = new FinanceiroMovimentacaoModel();
    }

   public function index()
{
    $empresa_id = session()->get('empresa_id');
    $mes_atual = date('m');
    $ano_atual = date('Y');

    // 1. Dados para o Dashboard (Geral)
    $data['resumo'] = [
        'vencidas' => $this->contaPagarModel
            ->where(['empresa_id' => $empresa_id, 'status' => 'pendente', 'data_vencimento <' => date('Y-m-d')])
            ->selectSum('valor_original')->first()['valor_original'] ?? 0,
            
        'pagas_mes' => $this->contaPagarModel
            ->where(['empresa_id' => $empresa_id, 'status' => 'paga'])
            ->where('MONTH(data_pagamento)', $mes_atual)
            ->where('YEAR(data_pagamento)', $ano_atual)
            ->selectSum('valor_pago')->first()['valor_pago'] ?? 0,

        'a_pagar_mes' => $this->contaPagarModel
            ->where(['empresa_id' => $empresa_id, 'status' => 'pendente'])
            ->where('MONTH(data_vencimento)', $mes_atual)
            ->where('YEAR(data_vencimento)', $ano_atual)
            ->selectSum('valor_original')->first()['valor_original'] ?? 0,

        'previsao_futura' => $this->contaPagarModel
            ->where(['empresa_id' => $empresa_id, 'status' => 'pendente'])
            ->where('data_vencimento >', date('Y-m-t')) // Após o último dia do mês atual
            ->selectSum('valor_original')->first()['valor_original'] ?? 0,
    ];

    // 2. Listagem focada no mês atual (ou vencidas não pagas)
    // Alteramos para buscar parcelas individuais em vez de agrupadas para mostrar "x de y"
   $hoje = date('Y-m-d');
$primeiroDiaMes = date('Y-m-01'); // Ex: 2024-05-01

$data['contas'] = $this->contaPagarModel
    ->where('empresa_id', $empresa_id)
    ->groupStart()
        // CONDIÇÃO 1: Tudo o que vence no mês atual (Pagas ou Pendentes)
        ->groupStart()
            ->where('data_vencimento >=', $primeiroDiaMes)
            ->where('data_vencimento <=', date('Y-m-t')) // 't' traz o último dia do mês
        ->groupEnd()
        
        // CONDIÇÃO 2: Tudo o que está pendente e venceu ANTES do mês atual
        ->orGroupStart()
            ->where('data_vencimento <', $primeiroDiaMes)
            ->where('status', 'pendente')
        ->groupEnd()
    ->groupEnd()
    ->orderBy('data_vencimento', 'ASC')
    ->findAll();

    $catModel = new \App\Models\FinanceiroCategoriaModel();
    $ccModel  = new \App\Models\FinanceiroCentroCustoModel();
    
    $data['categorias'] = $catModel->getPorTipo($empresa_id, 'despesa');
    $data['centrosCusto'] = $ccModel->getAtivos($empresa_id);

    return view('financeiro/contas_pagar_v', $data);
}
// SALVAR CONTA A PAGAR
public function salvar()
{
    // Captura os dados do formulário
    $tipo_fluxo     = $this->request->getPost('tipo_fluxo');
    $qtd_parcelas   = (int) $this->request->getPost('total_parcelas');
    $valor_input    = $this->request->getPost('valor_total');
    $data_vencimento = $this->request->getPost('data_vencimento');
    
    // 1. Definição da lógica de valores e numeração de parcelas
    if ($tipo_fluxo === 'repetir') {
        // Cenário: Conta de Consumo (Internet, Aluguel)
        // O valor digitado é o valor de CADA mês.
        $valor_por_parcela = $valor_input;
        $total_de_parcelas_registro = 1; // Para aparecer "1 de 1" na listagem
    } else {
        // Cenário: Compra Parcelada ou Única
        // O valor digitado é o TOTAL e deve ser dividido.
        $valor_por_parcela = $valor_input / ($qtd_parcelas > 0 ? $qtd_parcelas : 1);
        $total_de_parcelas_registro = $qtd_parcelas;
    }

    // 2. Upload do Documento de Origem (O boleto/fatura que chegou)
    $nomeArquivoOrigem = null;
    $arquivo = $this->request->getFile('arquivo_origem');
    
    if ($arquivo && $arquivo->isValid() && !$arquivo->hasMoved()) {
        $caminhoDestino = FCPATH . 'uploads/contas_pagar/origem/';
        
        // Cria a pasta caso não exista
        if (!is_dir($caminhoDestino)) {
            mkdir($caminhoDestino, 0755, true);
        }

        $nomeArquivoOrigem = $arquivo->getRandomName();
        $arquivo->move($caminhoDestino, $nomeArquivoOrigem);
    }

    // 3. Gerar um Identificador Único para o Grupo
    $id_agrupador = 'PAG-' . strtoupper(uniqid());

    try {
        // Iniciamos a transação para garantir que ou salva tudo ou nada
        $db = \Config\Database::connect();
        $db->transStart();

        for ($i = 1; $i <= $qtd_parcelas; $i++) {
            
            $dados = [
                'empresa_id'        => session()->get('empresa_id'),
                'categoria_id'      => $this->request->getPost('categoria_id'),
                'centro_custo_id'   => $this->request->getPost('centro_custo_id'),
                'descricao'         => $this->request->getPost('descricao'),
                'valor_original'    => $valor_por_parcela,
                'id_agrupador'      => $id_agrupador,
                'data_vencimento'   => $data_vencimento,
                'forma_pagamento'   => $this->request->getPost('forma_pagamento'),
                'status'            => 'pendente',
                'arquivo_origem'    => $nomeArquivoOrigem,
                'observacoes'       => $this->request->getPost('observacoes')
            ];

            // Ajuste da numeração conforme sua regra de negócio
            if ($tipo_fluxo === 'repetir') {
                $dados['parcela_atual']  = 1;
                $dados['total_parcelas'] = 1;
            } else {
                $dados['parcela_atual']  = $i;
                $dados['total_parcelas'] = $total_de_parcelas_registro;
            }

            $this->contaPagarModel->insert($dados);

            // Incrementa um mês para a data de vencimento da próxima parcela
            $data_vencimento = date('Y-m-d', strtotime("+1 month", strtotime($data_vencimento)));
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
        return redirect()->to(base_url('contas-pagar'))-> with('error', 'Erro ao gerar as parcelas.');
         }

          return redirect()->to(base_url('contas-pagar'))->with('success', "Lançamento realizado com sucesso!");


    } catch (\Exception $e) {
                  return redirect()->to(base_url('contas-pagar'))->with('success', "Houve um erro no sistema e a aperação não foi realizada!");

    }
}
/**
 * Busca a próxima parcela pendente de um agrupador
 */
public function obter_proxima($id_agrupador)
{
    $parcela = $this->contaPagarModel
        ->where('id_agrupador', $id_agrupador)
        ->where('status !=', 'paga')
        ->where('status !=', 'cancelada')
        ->orderBy('parcela_atual', 'ASC')
        ->first();

    if ($parcela) {
        return $this->response->setJSON($parcela);
    }

    return $this->response->setJSON(['status_operacao' => false]);
}


// SALVAR CONTA A PAGAR
public function salvarJson()
{
    // Captura os dados do formulário
    $tipo_fluxo     = $this->request->getPost('tipo_fluxo');
    $qtd_parcelas   = (int) $this->request->getPost('total_parcelas');
    $valor_input    = $this->request->getPost('valor_total');
    $data_vencimento = $this->request->getPost('data_vencimento');
    
    // 1. Definição da lógica de valores e numeração de parcelas
    if ($tipo_fluxo === 'repetir') {
        // Cenário: Conta de Consumo (Internet, Aluguel)
        // O valor digitado é o valor de CADA mês.
        $valor_por_parcela = $valor_input;
        $total_de_parcelas_registro = 1; // Para aparecer "1 de 1" na listagem
    } else {
        // Cenário: Compra Parcelada ou Única
        // O valor digitado é o TOTAL e deve ser dividido.
        $valor_por_parcela = $valor_input / ($qtd_parcelas > 0 ? $qtd_parcelas : 1);
        $total_de_parcelas_registro = $qtd_parcelas;
    }

    // 2. Upload do Documento de Origem (O boleto/fatura que chegou)
    $nomeArquivoOrigem = null;
    $arquivo = $this->request->getFile('arquivo_origem');
    
    if ($arquivo && $arquivo->isValid() && !$arquivo->hasMoved()) {
        $caminhoDestino = FCPATH . 'uploads/contas_pagar/origem/';
        
        // Cria a pasta caso não exista
        if (!is_dir($caminhoDestino)) {
            mkdir($caminhoDestino, 0755, true);
        }

        $nomeArquivoOrigem = $arquivo->getRandomName();
        $arquivo->move($caminhoDestino, $nomeArquivoOrigem);
    }

    // 3. Gerar um Identificador Único para o Grupo
    $id_agrupador = 'PAG-' . strtoupper(uniqid());

    try {
        // Iniciamos a transação para garantir que ou salva tudo ou nada
        $db = \Config\Database::connect();
        $db->transStart();

        for ($i = 1; $i <= $qtd_parcelas; $i++) {
            
            $dados = [
                'empresa_id'        => session()->get('empresa_id'),
                'categoria_id'      => $this->request->getPost('categoria_id'),
                'centro_custo_id'   => $this->request->getPost('centro_custo_id'),
                'descricao'         => $this->request->getPost('descricao'),
                'valor_original'    => $valor_por_parcela,
                'id_agrupador'      => $id_agrupador,
                'data_vencimento'   => $data_vencimento,
                'forma_pagamento'   => $this->request->getPost('forma_pagamento'),
                'status'            => 'pendente',
                'arquivo_origem'    => $nomeArquivoOrigem,
                'observacoes'       => $this->request->getPost('observacoes')
            ];

            // Ajuste da numeração conforme sua regra de negócio
            if ($tipo_fluxo === 'repetir') {
                $dados['parcela_atual']  = 1;
                $dados['total_parcelas'] = 1;
            } else {
                $dados['parcela_atual']  = $i;
                $dados['total_parcelas'] = $total_de_parcelas_registro;
            }

            $this->contaPagarModel->insert($dados);

            // Incrementa um mês para a data de vencimento da próxima parcela
            $data_vencimento = date('Y-m-d', strtotime("+1 month", strtotime($data_vencimento)));
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status' => false, 
                'msg' => 'Erro ao processar a transação no banco de dados.'
            ]);
        }

        return $this->response->setJSON([
            'status' => true, 
            'msg' => ($tipo_fluxo === 'repetir' ? 'Recorrência mensal gerada!' : 'Lançamento(s) gerado(s) com sucesso!')
        ]);

    } catch (\Exception $e) {
        return $this->response->setJSON([
            'status' => false, 
            'msg' => 'Erro sistêmico: ' . $e->getMessage()
        ]);
    }
}

/**
 * Processa o pagamento da parcela
 */
public function confirmar_pagamento()
{
    // Captura dos IDs e Dados do Formulário
    $id = $this->request->getPost('id_parcela');
    $valor_pago = $this->request->getPost('valor_pagamento'); // Valor final que saiu do caixa
    $data_pagamento = $this->request->getPost('data_pagamento');

    // 1. Busca a parcela para obter descrição e categoria antes da atualização
    $parcela = $this->contaPagarModel->find($id);
    if (!$parcela) {
        return $this->response->setJSON(['status' => false, 'msg' => 'Parcela não encontrada.']);
    }

    // 2. Prepara os dados para atualizar a tabela contas_pagar
    $dadosUpdate = [
        'valor_pago'      => $valor_pago,
        'data_pagamento'  => $data_pagamento,
        'forma_pagamento' => $this->request->getPost('forma_pagamento'),
        'status'          => 'paga',
        'observacoes'     => $this->request->getPost('observacoes') ?? 'Pagamento registrado.'
    ];

    // Lógica de Upload do Comprovante
    $arquivo = $this->request->getFile('comprovante');
    if ($arquivo && $arquivo->isValid() && !$arquivo->hasMoved()) {
        $novoNome = $arquivo->getRandomName();
        // Caminho conforme sua estrutura de pastas
        $arquivo->move(FCPATH . 'uploads/comprovantes_pagar/', $novoNome);
        $dadosUpdate['comprovante_arquivo'] = $novoNome;
    }

    // 3. Inicia a Transação Bancária (Database Transaction)
    $db = \Config\Database::connect();
    $db->transStart();

    // A. Atualiza o status da conta a pagar
    $this->contaPagarModel->update($id, $dadosUpdate);

    // B. Registra a saída no Fluxo de Caixa (financeiro_movimentacao)
    $this->movModel->registrar([
        'empresa_id'        => session()->get('empresa_id'),
        'tipo'              => 'saida', // Dinheiro saindo
        'categoria_id'      => $parcela['categoria_id'] ?? null,
        'descricao'         => "PAGAMENTO: " . $parcela['descricao'],
        'valor'             => $valor_pago,
        'data_movimentacao' => $data_pagamento,
        'origem_tabela'     => 'contas_pagar',
        'origem_id'         => $id,
        'observacoes'       => 'Baixa automática via módulo Contas a Pagar.'
    ]);

    $db->transComplete();

    // 4. Verifica se a transação foi bem-sucedida
    if ($db->transStatus() === false) {
        return $this->response->setJSON(['status' => false, 'msg' => 'Erro ao processar a baixa financeira. Tente novamente.']);
    }

    return $this->response->setJSON(['status' => true, 'msg' => 'Pagamento confirmado e registrado no fluxo de caixa!']);
}
public function detalhes($id_agrupador)
{
    
    // Busca todas as parcelas do grupo
    $data['parcelas'] = $this->contaPagarModel
                            ->where('id_agrupador', $id_agrupador)
                             ->orderBy('parcela_atual', 'ASC')
                             ->findAll();

    if (empty($data['parcelas'])) {
        return redirect()->to('/contas-pagar')->with('error', 'Grupo não encontrado.');
    }

    // Informações gerais do grupo (pega da primeira parcela)
    $data['info'] = $data['parcelas'][0];
    $data['total_grupo'] = array_sum(array_column($data['parcelas'], 'valor_original'));

    return view('financeiro/detalhes_grupo_v', $data);
}
public function estornar_pagamento($id)
{
    // 1. Busca a parcela para verificar o status e obter o nome do arquivo
    $parcela = $this->contaPagarModel->find($id);
    
    if (!$parcela || $parcela['status'] !== 'paga') {
        return $this->response->setJSON([
            'status' => false, 
            'msg' => 'Parcela não encontrada ou ainda não foi paga.'
        ]);
    }

    // 2. Iniciar Transação
    $db = \Config\Database::connect();
    $db->transStart();

    // A. Nome do arquivo antes de limpar o banco
    $nomeArquivo = $parcela['comprovante_arquivo'];

    // B. Resetar os campos na tabela contas_pagar
    $this->contaPagarModel->update($id, [
        'status'              => 'pendente',
        'valor_pago'          => null,
        'data_pagamento'      => null,
        'forma_pagamento'     => null,
        'comprovante_arquivo' => null, // Limpa a referência no banco
        'observacoes'         => $parcela['observacoes'] . " | Estorno em " . date('d/m/Y H:i')
    ]);

    // C. Remover do Fluxo de Caixa
    $this->movModel->estornar('contas_pagar', $id);

    $db->transComplete();

    // 3. Se a transação no banco foi um sucesso, tentamos apagar o arquivo físico
    if ($db->transStatus() === true) {
        
        if (!empty($nomeArquivo)) {
            // Define o caminho completo (ajuste a pasta se for Contas a Receber)
            $caminhoArquivo = FCPATH . 'uploads/comprovantes_pagar/' . $nomeArquivo;

            // Verifica se o arquivo existe no disco antes de deletar
            if (file_exists($caminhoArquivo)) {
                unlink($caminhoArquivo);
            }
        }

        return $this->response->setJSON([
            'status' => true, 
            'msg' => 'Pagamento estornado e comprovante removido!'
        ]);
    }

    return $this->response->setJSON([
        'status' => false, 
        'msg' => 'Erro ao processar o estorno.'
    ]);
}

}