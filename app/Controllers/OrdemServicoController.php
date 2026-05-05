<?php

namespace App\Controllers;

use App\Models\OrdemServicoModel;
use App\Models\ClienteModel;
use App\Models\VeiculoModel;
use App\Models\ServicoModel;
use App\Models\ProdutoModel;
use App\Models\EstoqueMovimentacaoModel;
use App\Models\OsItemModel; 
use App\Models\OsFotoModel; // Você precisará criar este Model

class OrdemServicoController extends BaseController
{
    protected $osModel;
    protected $helpers = ['form', 'url', 'text', 'filesystem'];
    protected $itensModel;
    protected $clienteModel;
    protected $veiculoModel;
    protected $servicoModel;
    protected $produtoModel;
    protected $estoqueMovModel;
    protected $osFotoModel;



    public function __construct()
    {
        $this->osModel = new OrdemServicoModel();
        $this->itensModel = new OsItemModel();
        $this->clienteModel = new ClienteModel();
        $this->veiculoModel = new VeiculoModel();
        $this->produtoModel = new ProdutoModel();
        $this->estoqueMovModel = new EstoqueMovimentacaoModel();
        $this->osFotoModel = new OsFotoModel();

    }

    /**
     * Listagem Geral de OS
     */
    public function index()
    {
        $data = [
            'titulo' => 'Gerenciar Ordens de Serviço',
            'ordens' => $this->osModel->getListagemCompleta(session()->get('empresa_id'))
        ];

        return view('os/listar_v', $data);
    }

    /**
     * Tela de Abertura de Nova OS
     */
    public function nova()
{
    $clienteModel = new ClienteModel();
    $veiculoModel = new VeiculoModel();
    $funcionarioModel = new \App\Models\FuncionarioModel(); // Adicionado
    $db = \Config\Database::connect();
    $empresa_id = session()->get('empresa_id');

    // Busca a equipe e já calcula a carga de cada um
    $equipe = $funcionarioModel->where('empresa_id', $empresa_id)
                               ->where('ativo', 1)
                               ->findAll();

    foreach ($equipe as &$f) {
        $f['os_ativas'] = $db->table('ordem_servicos')
                             ->where('tecnico_id', $f['id'])
                             ->where('status', 'aberta')
                             ->countAllResults();
    }

    $data = [
        'titulo'         => 'Abrir Nova Ordem de Serviço',
        'proximo_numero' => $this->osModel->geraProximoNumero($empresa_id),
        'clientes'       => $clienteModel->where('empresa_id', $empresa_id)->findAll(),
        'veiculos'       => $veiculoModel->where('empresa_id', $empresa_id)->findAll(),
        'equipe'         => $equipe // Injetado para o select de responsáveis
    ];

    return view('os/abrir_v', $data);
}

    /**
     * Salva o cabeçalho da OS
     */
   public function salvar_abertura()
{
    // 1. Coleta todos os dados que vieram da View (cliente_id, veiculo_id, tecnico_id, etc.)
    $dados = $this->request->getPost();

    // 2. Validação manual rápida (Caso o HTML falhe)
    if (empty($dados['cliente_id']) || empty($dados['veiculo_id'])) {
        return redirect()->back()->withInput()->with('error', 'Selecione o cliente e o veículo obrigatoriamente.');
    }

    // 3. Complementa com dados automáticos do sistema
    $dados['empresa_id']     = session()->get('empresa_id');
    $dados['status']         = 'orcamento'; // Toda OS começa como orçamento
    $dados['data_abertura']  = date('Y-m-d H:i:s');
    
    // Se a view enviou 'criado_por', o CodeIgniter usa. 
    // Por segurança, forçamos o valor da sessão aqui:
    $dados['criado_por']     = session()->get('id'); 

    // 4. Garante o Número da OS
    // Se o campo hidden da View falhar, geramos aqui novamente
    if (empty($dados['numero_os'])) {
        $dados['numero_os'] = $this->osModel->geraProximoNumero($dados['empresa_id']);
    }

    // 5. Inserção no Banco
    if ($this->osModel->insert($dados)) {
        // Pega o ID gerado pelo AUTO_INCREMENT
        $id = $this->osModel->getInsertID();
        
        // Redireciona para a tela de gerenciamento (onde o Gustavo vai por itens/checklist)
        return redirect()->to(base_url("os/gerenciar/$id"))
                         ->with('success', "OS #{$dados['numero_os']} aberta com sucesso!");
    } else {
        // Em caso de erro de banco (ex: falta campo no allowedFields)
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Erro interno ao salvar: ' . implode(', ', $this->osModel->errors()));
    }
}

    /**
     * Painel de GERENCIAMENTO da OS (Itens, Fotos, Checklist)
     */
    public function gerenciar($id)
{
    $osModel = new \App\Models\OrdemServicoModel();
    $checkModel = new \App\Models\ChecklistModel(); 
    $osChecklistModel = new \App\Models\OsChecklistModel();
    
    // Models para o Modal de Itens
    $produtoModel = new \App\Models\ProdutoModel();
    $servicoModel = new \App\Models\ServicoModel();

    $data['os'] = $osModel->getOsCompleta($id);

    if (!$data['os']) {
        return redirect()->to('os')->with('error', 'Ordem de Serviço não encontrada.');
    }

    $empresa_id = session()->get('empresa_id');

    $data['itens'] = $osModel->getItens($id); 
    $data['fotos'] = $osModel->getFotos($id);

    // Itens de Checklist aplicados
    $data['itens_checklist'] = $osChecklistModel->where('ordem_servico_id', $id)->findAll();

    // Listas para os Modais de Checklist
   // $data['lista_check_entrada'] = $checkModel->where('tipo', 'entrada')->findAll();
   // $data['lista_check_servicos'] = $checkModel->where('tipo', 'servico')->findAll();

   // No seu método gerenciar($id)
    $checklistModel = new \App\Models\ChecklistModel();

    // Busca itens de ENTRADA ordenados por Categoria e depois por Descrição
    $data['lista_check_entrada'] = $checklistModel
    ->where('tipo', 'entrada')
    ->orderBy('categoria', 'ASC')
    ->orderBy('descricao', 'ASC')
    ->findAll();

// Busca itens de SERVIÇO (Conferência Técnica) da mesma forma
    $data['lista_check_servicos'] = $checklistModel
    ->where('tipo', 'servico')
    ->orderBy('categoria', 'ASC')
    ->orderBy('descricao', 'ASC')
    ->findAll();

    // --- ESTA PARTE É ESSENCIAL PARA O MODAL DE ITENS ---
    $data['produtos'] = $produtoModel->where('empresa_id', $empresa_id)->findAll();
    $data['servicos'] = $servicoModel->where('empresa_id', $empresa_id)->findAll();

    return view('os/gerenciar_v', $data);
}

    /**
     * Adiciona Item (Produto ou Serviço) e controla estoque
     */
   public function adicionar_item()
{
    $tipo = $this->request->getPost('tipo');
    $osId = $this->request->getPost('ordem_servico_id');
    $itemId = $this->request->getPost('produto_id'); // O Select2 envia como produto_id no form
    $qtd = $this->request->getPost('quantidade');
    $valorManual = $this->request->getPost('valor_unitario');

    $itemModel = new \App\Models\OsItemModel();
    $prodModel = new \App\Models\ProdutoModel();
    $servModel = new \App\Models\ServicoModel();

    if ($tipo == 'produto') {
        $base = $prodModel->find($itemId);
        $valorUnit = (!empty($valorManual)) ? $valorManual : $base['preco_venda'];
        
        $dadosItem = [
            'ordem_servico_id' => $osId,
            'tipo'           => 'produto',
            'item_id'        => $itemId,
            'descricao'      => $base['descricao'], // Corrigido de 'nome' para 'descricao'
            'quantidade'     => $qtd,
            'valor_unitario' => $valorUnit,
            'custo_unitario' => $base['preco_custo'] ?? 0,
            'subtotal'       => $valorUnit * $qtd
        ];

        // Movimentação de Estoque
        $movModel = new $this->estoqueMovModel();
        $movModel->insert([
            'produto_id' => $itemId,
            'empresa_id' => session()->get('empresa_id'),
            'tipo'       => 'S',
            'quantidade' => $qtd,
            'origem'     => 'OS #' . $osId,
            'observacao' => 'Saída automática'
        ]);
        $prodModel->atualizarSaldo($itemId);

    } else {
        $base = $servModel->find($itemId);
        $valorUnit = (!empty($valorManual)) ? $valorManual : $base['preco_venda'];

        $dadosItem = [
            'ordem_servico_id' => $osId,
            'tipo'           => 'servico',
            'item_id'        => $itemId,
            'descricao'      => $base['descricao'], // Corrigido de 'nome' para 'descricao'
            'quantidade'     => $qtd,
            'valor_unitario' => $valorUnit,
            'custo_unitario' => $base['preco_custo'] ?? 0,
            'subtotal'       => $valorUnit * $qtd
        ];
    }

    $itemModel->insert($dadosItem);
    $this->atualizarTotaisOS($osId);

    return redirect()->to("os/gerenciar/$osId")->with('success', 'Item adicionado com sucesso!');
}

    /**
     * Módulo de Acompanhamento Fotográfico
     */
    public function upload_foto($osId)
    {
        $file = $this->request->getFile('foto');
        
        if ($file->isValid() && !$file->hasMoved()) {
            $novoNome = $file->getRandomName();
            $caminhoRelativo = "uploads/os/{$osId}/";
            $caminhoFisico = FCPATH . $caminhoRelativo;

            if (!is_dir($caminhoFisico)) mkdir($caminhoFisico, 0777, true);

            $file->move($caminhoFisico, $novoNome);

            $fotoModel = new \App\Models\OsFotoModel();
            $fotoModel->insert([
                'ordem_servico_id' => $osId,
                'tipo' => $this->request->getPost('tipo') ?? 'evidencia',
                'caminho_arquivo' => $caminhoRelativo . $novoNome,
                'descricao' => $this->request->getPost('descricao'),
                'tamanho_kb' => $file->getSizeByUnit('kb'),
                'criado_por' => session()->get('user_id')
            ]);

            return redirect()->to("os/gerenciar/$osId")->with('success', 'Foto anexada!');
        }
    }

    /**
     * Recalcula os totais da OS (Serviços + Produtos)
     */
    private function atualizarTotaisOS($id)
    {
        $itemModel = new \App\Models\OsItemModel();
        $servicos = $itemModel->selectSum('subtotal')->where(['ordem_servico_id' => $id, 'tipo' => 'servico'])->first();
        $produtos = $itemModel->selectSum('subtotal')->where(['ordem_servico_id' => $id, 'tipo' => 'produto'])->first();

        $valServ = $servicos['subtotal'] ?? 0;
        $valProd = $produtos['subtotal'] ?? 0;

        $this->osModel->update($id, [
            'valor_servicos' => $valServ,
            'valor_produtos' => $valProd,
            'valor_total'    => $valServ + $valProd
        ]);
    }

    public function gerarPdf()
{
    $empresaId = session()->get('empresa_id');
    
    $empresaModel = new \App\Models\EmpresaModel();
    $oficina = $empresaModel->find($empresaId);

    // Buscamos as ordens (pode adicionar filtros aqui depois)
    $ordens = $this->osModel->getListagemCompleta($empresaId);

    $data = [
        'oficina' => $oficina,
        'ordens'  => $ordens
    ];

    $lib = new \App\Libraries\PdfLib();
    $html = view('os/pdf_relacao_v', $data);

    while (ob_get_level() > 0) ob_end_clean();
    $lib->gerar($html, 'Relatorio_OS.pdf');
    exit;
}

public function imprimir($id)
{
    // 1. Instanciar os Models necessários
    $itemModel = new \App\Models\OsItemModel();
    $fotoModel = new \App\Models\OsFotoModel();
    $osChecklistModel = new \App\Models\OsChecklistModel();
    $osChecklistItemModel = new \App\Models\OsChecklistItemModel();
    $empresaModel = new \App\Models\EmpresaModel();

    // 2. Buscar dados da OS (getOsCompleta deve retornar cliente_nome, veiculo_placa, empresa_id, etc.)
    $os = $this->osModel->getOsCompleta($id);

    if (!$os) {
        return redirect()->back()->with('error', 'Ordem de Serviço não encontrada.');
    }

    // 3. Buscar dados da Oficina pelo ID gravado na OS (Resolve o erro de sessão no link externo)
    $oficina = $empresaModel->find($os['empresa_id']);

    if (!$oficina) {
        // Fallback preventivo para evitar erro de array key indefinida na view
        $oficina = [
            'nome_fantasia' => 'Oficina Pro',
            'logradouro'    => 'Endereço não configurado',
            'numero'        => '',
            'bairro'        => '',
            'cidade'        => '',
            'estado'        => '',
            'telefone'      => '',
            'cnpj'          => '00.000.000/0000-00'
        ];
    }

    // 4. Buscar Itens, Fotos e Checklist
    $itens = $itemModel->where('ordem_servico_id', $id)->findAll();
    $fotos = $fotoModel->where('ordem_servico_id', $id)->findAll();
    
    // Busca o checklist vinculado a esta OS (se houver)
    $checkVinculo = $osChecklistModel->where('ordem_servico_id', $id)->first();
    $itensChecklist = [];
    if ($checkVinculo) {
        $itensChecklist = $osChecklistItemModel->where('os_checklist_id', $checkVinculo['id'])->findAll();
    }

    // 5. Preparar dados para a View
    $data = [
        'os'              => $os,
        'oficina'         => $oficina,
        'itens'           => $itens,
        'fotos'           => $fotos,
        'itens_checklist' => $itensChecklist
    ];

    // 6. Gerar o PDF usando a biblioteca mPDF (PdfLib)
    $pdf = new \App\Libraries\PdfLib();
    $html = view('os/pdf_os_v', $data);

    // Limpeza de buffer para evitar caracteres estranhos (sopa de letrinhas)
    if (ob_get_contents()) ob_end_clean();
// echo $html; exit;
    return $pdf->gerar($html, "OS_{$os['numero_os']}.pdf");
}
/**
 * Aplica um modelo de checklist à OS (Snapshot)
 */
public function aplicar_checklist()
{
    $osId = $this->request->getPost('ordem_servico_id');
    $modeloId = $this->request->getPost('checklist_modelo_id');

    $modeloItemModel = new \App\Models\ChecklistModeloItemModel();
    $osChecklistModel = new \App\Models\OsChecklistModel(); // Crie este Model (tabela ordem_servico_checklists)
    $osChecklistItemModel = new \App\Models\OsChecklistItemModel(); // Crie este Model (tabela ordem_servico_checklist_itens)

    // 1. Cria o registro pai da execução do checklist na OS
    $osChecklistId = $osChecklistModel->insert([
        'ordem_servico_id'    => $osId,
        'checklist_modelo_id' => $modeloId,
        'status'              => 'pendente',
        'iniciado_em'         => date('Y-m-d H:i:s')
    ]);

    // 2. Busca os itens do modelo original
    $itensModelo = $modeloItemModel->where('checklist_modelo_id', $modeloId)->orderBy('ordem', 'ASC')->findAll();

    // 3. Clona cada item para a tabela de execução (Snapshot)
    foreach ($itensModelo as $item) {
        $osChecklistItemModel->insert([
            'os_checklist_id' => $osChecklistId,
            'descricao'       => $item['descricao'],
            'obrigatorio'     => $item['obrigatorio'],
            'status'          => 'pendente'
        ]);
    }

    return redirect()->to("os/gerenciar/$osId")->with('success', 'Checklist aplicado com sucesso! Pronto para inspeção.');
}

/**
 * Atualiza o status de um item do checklist (Via AJAX ou Form)
 */
public function atualizar_item_checklist()
{
    $itemId    = $this->request->getPost('item_id');
    $novoStatus = $this->request->getPost('status'); // 'ok' ou 'nao_ok'
    $observacao = $this->request->getPost('observacao'); // Novo campo

    $model = new \App\Models\OsChecklistModel();
    $item  = $model->find($itemId);

    if ($item) {
        $dados = [
            'status_anterior' => $item['status'], // Salva o que era antes
            'status'          => $novoStatus,
            'observacao' => $observacao
        ];

        if ($model->update($itemId, $dados)) {
            return $this->response->setJSON(['status' => 'success']);
        }
    }

    return $this->response->setStatusCode(500)->setJSON(['status' => 'error']);
}

public function remover_item($itemId, $osId)
{
    $itemModel = new \App\Models\OsItemModel();
    $item = $itemModel->find($itemId);

    if ($item) {
        // Se for produto, devolvemos ao estoque antes de deletar
        if ($item['tipo'] == 'produto') {
            $prodModel = new \App\Models\ProdutoModel();
            $movModel = new \App\Models\EstoqueMovModel();
            
            $movModel->insert([
                'produto_id' => $item['item_id'],
                'empresa_id' => session()->get('empresa_id'),
                'tipo'       => 'E', // Entrada (Devolução)
                'quantidade' => $item['quantidade'],
                'origem'     => "Cancelamento Item OS #$osId",
                'observacao' => "Item removido da OS"
            ]);
            $prodModel->atualizarSaldo($item['item_id']);
        }

        $itemModel->delete($itemId);
        $this->atualizarTotaisOS($osId); // Aquele método que recalcula o total
    }

    return redirect()->to("os/gerenciar/$osId")->with('success', 'Item removido e estoque ajustado.');
}

public function finalizar($id)
{
    $os = $this->osModel->find($id);
    
    // 1. Validação de Segurança
    if ($os['status'] == 'finalizada') {
        return redirect()->back()->with('error', 'Esta OS já está finalizada.');
    }

    // 2. Validação do Checklist (Opcional - mas recomendado)
    $osChecklistItemModel = new \App\Models\OsChecklistItemModel();
    $osChecklistModel = new \App\Models\OsChecklistModel();
    $check = $osChecklistModel->where('ordem_servico_id', $id)->first();
    
    if ($check) {
        $pendentes = $osChecklistItemModel->where([
            'os_checklist_id' => $check['id'],
            'status' => 'pendente',
            'obrigatorio' => 1
        ])->countAllResults();

        if ($pendentes > 0) {
            return redirect()->back()->with('error', 'Existem itens obrigatórios no checklist que não foram avaliados!');
        }
    }

    // 3. Gerar o Financeiro (Contas a Receber)
    $financeiroModel = new \App\Models\ContaReceberModel(); // Você deve ter esse Model
    $financeiroModel->insert([
        'empresa_id' => $os['empresa_id'],
        'cliente_id' => $os['cliente_id'],
        'os_id' => $os['id'],
        'valor_original'      => $os['valor_total'],
        'vencimento' => date('Y-m-d'), // Por padrão, vencimento hoje
        'status'     => 'pendente',
        'completa'   => 0,
        'descricao'  => "Referente à OS #" . $os['numero_os']
    ]);

    // 4. Atualizar o Status da OS
    $this->osModel->update($id, [
        'status' => 'finalizada',
        'data_fechamento' => date('Y-m-d H:i:s')
    ]);

    return redirect()->to("os/gerenciar/$id")->with('success', 'OS Finalizada! O valor foi lançado no Contas a Receber.');
}
/**
 * Gera o link seguro e redireciona para o WhatsApp do cliente
 */
public function whatsapp($id)
{
    // 1. Busca os dados da OS
    $os = $this->osModel->getOsCompleta($id);

    if (!$os) {
        return redirect()->back()->with('error', 'Ordem de Serviço não encontrada.');
    }

    // 2. Validação do Celular (conforme solicitado: cliente_celular)
    if (empty($os['cliente_celular'])) {
        return redirect()->back()->with('error', 'O cliente não possui celular cadastrado!');
    }

    // Limpa o número para deixar apenas dígitos
    $celular = preg_replace('/[^0-9]/', '', $os['cliente_celular']);
    
    // Adiciona o código do Brasil (55) se necessário
    if (strlen($celular) == 11 || strlen($celular) == 10) {
        $celular = "55" . $celular;
    }

    // 3. Geração do Link Seguro (Dica Bônus: Hexadecimal + Sal de Segurança)
    // O sal evita que o cliente descubra o ID real ou tente ver a OS de outros
    $sal = "oficina_pro_2024"; 
    $hash = bin2hex($id . "-" . $sal);
    $linkPublico = base_url("view/os/{$hash}");

    // 4. Montagem da Mensagem (conforme solicitado: cliente_nome)
    $mensagem = "Olá, *{$os['cliente_nome']}*! 🚗\n\n";
    $mensagem .= "Sua OS *#{$os['numero_os']}* do veículo *{$os['veiculo_placa']}* está disponível para visualização.\n\n";
    $mensagem .= "Acesse o link abaixo para conferir os detalhes, fotos e valores:\n";
    $mensagem .= $linkPublico . "\n\n";
    $mensagem .= "Qualquer dúvida, estamos à disposição!";

    // 5. URL do WhatsApp com urlencode para evitar quebra de caracteres
    $urlFinal = "https://api.whatsapp.com/send?phone={$celular}&text=" . urlencode($mensagem);

    return redirect()->to($urlFinal);
}

/**
 * Visualização pública da OS (PDF) sem necessidade de login
 */
public function visualizar_cliente($hash)
{
    try {
        // Converte de hexadecimal para string
        $decodificado = hex2bin($hash);
        
        // Separa o ID do Sal
        $partes = explode("-", $decodificado);
        $id = $partes[0];
        $salOriginal = $partes[1] ?? '';

        // Validação de Segurança
        if (!is_numeric($id) || $salOriginal !== "oficina_pro_2024") {
            throw new \Exception("Link de visualização inválido ou expirado.");
        }

        // Chama o método de impressão (que já usa a PdfLib com mPDF)
        return $this->imprimir($id);

    } catch (\Exception $e) {
        // Se houver erro na decodificação ou segurança, mostra erro 404
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($e->getMessage());
    }
}
public function aprovar($id)
{
    $osModel = new \App\Models\OrdemServicoModel();
    
    // Verifica se a OS existe e pertence à empresa logada
    $os = $osModel->where([
        'id' => $id, 
        'empresa_id' => session()->get('empresa_id')
    ])->first();

    if (!$os) {
        return redirect()->back()->with('error', 'Ordem de Serviço não encontrada.');
    }

    // Dados para atualização
    $dados = [
        'status' => 'em_execucao', // Status que indica que o serviço começou
        'data_aprovacao' => date('Y-m-d H:i:s') // Opcional: se tiver este campo no banco
    ];

    if ($osModel->update($id, $dados)) {
        return redirect()->to(base_url("os/gerenciar/$id"))
                         ->with('success', 'Orçamento aprovado! O serviço está em andamento.');
    } else {
        return redirect()->back()->with('error', 'Erro ao aprovar orçamento.');
    }
}
public function alterarStatus($id, $novoStatus)
{
    $osModel = new \App\Models\OrdemServicoModel();
    
    // Lista de status permitidos para segurança
    $statusPermitidos = ['orcamento', 'aprovada', 'em_execucao', 'finalizada', 'cancelada'];

    if (!in_array($novoStatus, $statusPermitidos)) {
        return redirect()->back()->with('error', 'Status inválido.');
    }

    $os = $osModel->where(['id' => $id, 'empresa_id' => session()->get('empresa_id')])->first();

    if (!$os) {
        return redirect()->back()->with('error', 'Ordem de Serviço não encontrada.');
    }

    $dados = ['status' => $novoStatus];

    // Dica Bônus: Se estiver finalizando, grava a data de fechamento
    if ($novoStatus == 'finalizada') {
        $dados['data_fechamento'] = date('Y-m-d H:i:s');
    }

    if ($osModel->update($id, $dados)) {
        $mensagens = [
            'aprovada'    => 'Orçamento aprovado com sucesso!',
            'em_execucao' => 'Serviço iniciado e em execução.',
            'finalizada'  => 'Ordem de Serviço finalizada com sucesso!',
            'cancelada'   => 'A Ordem de Serviço foi cancelada.'
        ];

        return redirect()->to(base_url("os/gerenciar/$id"))
                         ->with('success', $mensagens[$novoStatus] ?? 'Status atualizado!');
    }

    return redirect()->back()->with('error', 'Erro ao atualizar status.');
}
// INCLUI INTEMS DE CHECKLIST
public function incluir_item_checklist() 
{
    $osId        = $this->request->getPost('os_id');
    $checklistId = $this->request->getPost('checklist_id');
    $tipo        = $this->request->getPost('tipo'); 

    $mestreModel = new \App\Models\ChecklistModel();
    $itemMestre  = $mestreModel->find($checklistId);

    if ($itemMestre) {
        $osCheckModel = new \App\Models\OsChecklistModel();
        
        return $this->response->setJSON([
            'status' => $osCheckModel->insert([
                'ordem_servico_id' => $osId,
                'descricao'        => $itemMestre['descricao'],
                'tipo'             => $tipo,
                'status'           => 'pendente' // O banco aceita 'pendente', 'ok' ou 'nao_ok'
            ]) ? 'success' : 'error'
        ]);
    }
    
    return $this->response->setJSON(['status' => 'error', 'message' => 'Item não encontrado']);
}

// EXCLUIR FOTOS
public function excluir_foto($foto_id, $os_id)
{
    $fotoModel = new \App\Models\OsFotoModel(); // Ajuste para o nome do seu Model de fotos
    $foto = $fotoModel->find($foto_id);

    if ($foto) {
        // 1. Caminho completo do arquivo no servidor
        $caminho_completo = FCPATH . $foto['caminho_arquivo'];

        // 2. Tenta excluir o arquivo físico
        if (file_exists($caminho_completo)) {
            unlink($caminho_completo);
        }

        // 3. Exclui o registro do banco de dados
        $fotoModel->delete($foto_id);

        return redirect()->to(base_url("os/gerenciar/$os_id"))->with('success', 'Foto removida com sucesso.');
    }

    return redirect()->to(base_url("os/gerenciar/$os_id"))->with('error', 'Foto não encontrada.');
}

public function relatorio($id)
{
    $db = \Config\Database::connect();
    
    // 1. Busca Dados da Empresa (Configurações do Cabeçalho)
    $empresa = $db->table('empresas')->get()->getRowArray();

    // 2. Busca OS com Joins corrigidos (tabela ordem_servicos e coluna nome_razao)
    $os = $db->table('ordem_servicos')
             ->select('ordem_servicos.*, clientes.nome_razao as cliente_nome, veiculos.placa as veiculo_placa, veiculos.modelo as veiculo_modelo, usuarios.nome as tecnico_nome')
             ->join('clientes', 'clientes.id = ordem_servicos.cliente_id')
             ->join('veiculos', 'veiculos.id = ordem_servicos.veiculo_id')
             ->join('usuarios', 'usuarios.id = ordem_servicos.usuario_id', 'left')
             ->where('ordem_servicos.id', $id)
             ->get()->getRowArray();

    if (!$os) {
        return redirect()->back()->with('error', 'Ordem de Serviço não encontrada.');
    }

    // 3. Busca itens, fotos e checklists com os nomes de tabela corrigidos
    $data['empresa']    = $empresa;
    $data['os']         = $os;
    $data['itens']      = $db->table('ordem_servico_itens')
                            ->where('ordem_servico_id', $id)
                            ->get()->getResultArray();

    $data['fotos']      = $db->table('ordem_servico_fotos')
                            ->where('ordem_servico_id', $id)
                            ->get()->getResultArray();

    $data['checklists'] = $db->table('os_checklists')
                            ->where('ordem_servico_id', $id)
                            ->orderBy('tipo', 'ASC')
                            ->orderBy('created_at', 'ASC')
                            ->get()->getResultArray();

    // 4. Renderiza a view pdf_relatorio_v
    $html = view('os/pdf_relatorio_v', $data);

    // 5. Uso da sua Library PdfLib (método gerar)
    $pdfLib = new \App\Libraries\PdfLib();
    
    // Nome do arquivo para o download
    $nomeArquivo = "Relatorio_OS_" . $id . "_" . ($os['veiculo_placa'] ?? 'sem_placa');
    
    return $pdfLib->gerar($html, $nomeArquivo);
}
public function buscar_itens_json($tipo)
{
    try {
        $db = \Config\Database::connect();
        
        // Determina a tabela correta
        $tabela = ($tipo === 'produto') ? 'produtos' : 'servicos';
        
        // Termo de busca enviado pelo Select2
        $term = $this->request->getGet('q');

        $builder = $db->table($tabela);
        
        // IMPORTANTE: Garantir que as colunas existam nessas tabelas
        // Se 'servicos' também não tiver 'nome', use 'descricao'
        $builder->select('id, descricao as text, preco_venda as preco');
        
        if (!empty($term)) {
            $builder->like('descricao', $term);
        }
        
        $resultados = $builder->where('ativo', 1) 
                              ->orderBy('descricao', 'ASC')
                              ->limit(30)
                              ->get()
                              ->getResultArray();

        return $this->response->setJSON($resultados);

    } catch (\Exception $e) {
        // Se der erro, retorna o erro no JSON para você ver no Network do Chrome
        return $this->response->setJSON(['error' => $e->getMessage()])->setStatusCode(500);
    }
}

/**
 * Altera o status da OS para 'em_execucao'
 */
public function iniciar_execucao($id)
{
   $os = $this->osModel->find($id);

    // Validação de segurança
    if (!$os) {
        return redirect()->back()->with('error', 'Ordem de Serviço não encontrada.');
    }

    if ($os['status'] !== 'aberta') {
        return redirect()->back()->with('error', 'Apenas OS aprovadas podem ser iniciadas.');
    }

    // Atualiza o status
    $osModel->update($id, [
        'status' => 'em_execucao',
        'data_inicio_servico' => date('Y-m-d H:i:s') // Opcional: caso tenha este campo para métricas
    ]);

    return redirect()->to("os/gerenciar/$id")->with('success', 'Serviço iniciado com sucesso! Mãos à obra.');
}

/**
 * Cancela a Ordem de Serviço usando a propriedade injetada no construct
 */
public function cancelar($id)
{
    // Busca a OS usando a propriedade da classe definida no __construct
    $os = $this->osModel->find($id);

    if (!$os) {
        return redirect()->back()->with('error', 'Ordem de Serviço não encontrada.');
    }

    // Impede cancelar o que já está finalizado
    if ($os['status'] == 'finalizada') {
        return redirect()->back()->with('error', 'Não é possível cancelar uma OS já finalizada.');
    }

    $dados = [
        'status' => 'cancelada'
    ];

    // Faz o update usando a propriedade da classe
    if ($this->osModel->update($id, $dados)) {
        return redirect()->to("os/gerenciar/$id")->with('success', 'Ordem de Serviço cancelada!');
    } else {
        return redirect()->back()->with('error', 'Erro ao processar o cancelamento.');
    }
}
public function detalhes($id)
{
    $empresaId = session()->get('empresa_id');

    // 1. Busca os dados principais da OS com Join no Cliente e Veículo
    $os = $this->osModel
        ->select('ordem_servicos.*, clientes.nome_razao as cliente_nome, clientes.telefone as cliente_fone, veiculos.placa, veiculos.modelo, veiculos.marca')
        ->join('clientes', 'clientes.id = ordem_servicos.cliente_id')
        ->join('veiculos', 'veiculos.id = ordem_servicos.veiculo_id')
        ->where(['ordem_servicos.id' => $id, 'ordem_servicos.empresa_id' => $empresaId])
        ->first();

    if (!$os) {
        return redirect()->to('/os')->with('error', 'Ordem de Serviço não encontrada.');
    }

    // 2. Busca os itens da OS (Peças e Serviços)
    // Supondo que você tenha um model OsItensModel
    //$itensModel = new \App\Models\OsItensModel();
    $itens = $this->itensModel->where('ordem_servico_id', $id)->findAll();

    $data = [
        'title' => 'Ordem de Serviço #' . $id,
        'os'    => $os,
        'itens' => $itens
    ];

    return view('os/os_detalhes_v', $data);
}



}
