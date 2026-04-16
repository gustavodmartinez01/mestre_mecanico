<?php

namespace App\Controllers;

use App\Models\OrdemServicoModel;
use App\Models\ClienteModel;
use App\Models\VeiculoModel;
use App\Models\ServicoModel;
use App\Models\ProdutoModel;
use App\Models\EstoqueMovModel;
use App\Models\OsItemModel; // Você precisará criar este Model
use App\Models\OsFotoModel; // Você precisará criar este Model

class OrdemServicoController extends BaseController
{
    protected $osModel;
    protected $helpers = ['form', 'url', 'text', 'filesystem'];

    public function __construct()
    {
        $this->osModel = new OrdemServicoModel();
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
        $dados = $this->request->getPost();
        $dados['empresa_id'] = session()->get('empresa_id');
        $dados['status']     = 'orcamento';
        $dados['criado_por'] = session()->get('user_id');

        if ($this->osModel->insert($dados)) {
            $id = $this->osModel->getInsertID();
            return redirect()->to("os/gerenciar/$id")->with('success', 'OS aberta! Adicione os itens agora.');
        }

        return redirect()->back()->withInput()->with('error', 'Erro ao abrir OS.');
    }

    /**
     * Painel de Gerenciamento da OS (Itens, Fotos, Checklist)
     */
    public function gerenciar($id)
{
    // 1. Busca os dados básicos da OS
    $os = $this->osModel->getOsCompleta($id);
    if (!$os) return redirect()->to('os')->with('error', 'OS não encontrada.');

    // 2. Instancia os Models necessários
    $itemModel = new \App\Models\OsItemModel();
    $fotoModel = new \App\Models\OsFotoModel();
    $checklistModeloModel = new \App\Models\ChecklistModeloModel();
    $osChecklistModel = new \App\Models\OsChecklistModel();
    $osChecklistItemModel = new \App\Models\OsChecklistItemModel();

    // 3. Busca se já existe um checklist aplicado a esta OS
    $osChecklist = $osChecklistModel->where('ordem_servico_id', $id)->first();
    $itensChecklist = [];
    if ($osChecklist) {
        $itensChecklist = $osChecklistItemModel->where('os_checklist_id', $osChecklist['id'])->findAll();
    }

    // 4. Prepara o array de dados para a View
    $data = [
        'titulo'            => 'Gerenciar OS #' . $os['numero_os'],
        'os'                => $os,
        'itens'             => $itemModel->where('ordem_servico_id', $id)->findAll(),
        'fotos'             => $fotoModel->where('ordem_servico_id', $id)->findAll(),
        'servicos' => (new \App\Models\ServicoModel())
                    ->where('empresa_id', $os['empresa_id'])
                    ->orderBy('nome', 'ASC') // Ordena Serviços
                    ->findAll(),
                    
         'produtos' => (new \App\Models\ProdutoModel())
                    ->where('empresa_id', $os['empresa_id'])
                    ->orderBy('nome', 'ASC') // Ordena Produtos
                    ->findAll(),
        // ESTAS SÃO AS VARIÁVEIS QUE ESTAVAM FALTANDO:
        'modelos_checklist' => $checklistModeloModel->where(['empresa_id' => $os['empresa_id'], 'ativo' => 1])->findAll(),
        'checklist_os'      => $osChecklist,
        'itens_checklist'   => $itensChecklist
    ];

    return view('os/gerenciar_v', $data);
}

    /**
     * Adiciona Item (Produto ou Serviço) e controla estoque
     */
    public function adicionar_item()
    {
        $tipo = $this->request->getPost('tipo');
        $osId = $this->request->getPost('ordem_servico_id');
        $itemId = $this->request->getPost('item_id');
        $qtd = $this->request->getPost('quantidade');

        $itemModel = new \App\Models\OsItemModel();
        $prodModel = new \App\Models\ProdutoModel();
        $servModel = new \App\Models\ServicoModel();

        // Busca dados originais do item para o snapshot
        if ($tipo == 'produto') {
            $base = $prodModel->find($itemId);
            $dadosItem = [
                'ordem_servico_id' => $osId,
                'tipo' => 'produto',
                'item_id' => $itemId,
                'descricao' => $base['nome'],
                'quantidade' => $qtd,
                'valor_unitario' => $base['preco_venda'],
                'custo_unitario' => $base['preco_custo'],
                'subtotal' => $base['preco_venda'] * $qtd,
                'margem' => ($base['preco_venda'] - $base['preco_custo']) * $qtd
            ];

            // 5.1 REGRA: Debitar estoque automaticamente
            $movModel = new \App\Models\EstoqueMovModel();
            $movModel->insert([
                'produto_id' => $itemId,
                'empresa_id' => session()->get('empresa_id'),
                'tipo' => 'S',
                'quantidade' => $qtd,
                'origem' => 'OS #' . $osId,
                'observacao' => 'Saída automática via Ordem de Serviço'
            ]);
            $prodModel->atualizarSaldo($itemId);

        } else {
            $base = $servModel->find($itemId);
            $dadosItem = [
                'ordem_servico_id' => $osId,
                'tipo' => 'servico',
                'item_id' => $itemId,
                'descricao' => $base['nome'],
                'quantidade' => 1,
                'valor_unitario' => $base['preco_venda'],
                'custo_unitario' => $base['preco_custo'],
                'subtotal' => $base['preco_venda'],
                'margem' => $base['preco_venda'] - $base['preco_custo']
            ];
        }

        $itemModel->insert($dadosItem);
        $this->atualizarTotaisOS($osId);

        return redirect()->to("os/gerenciar/$osId")->with('success', 'Item adicionado!');
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
    $itemId = $this->request->getPost('item_id');
    $status = $this->request->getPost('status'); // ok, nao_ok, nao_aplicavel
    $obs    = $this->request->getPost('observacao');

    $osChecklistItemModel = new \App\Models\OsChecklistItemModel();
    
    $osChecklistItemModel->update($itemId, [
        'status'       => $status,
        'observacao'   => $obs,
        'executado_at' => date('Y-m-d H:i:s'),
        'executado_por' => session()->get('user_id')
    ]);

    return $this->response->setJSON(['status' => 'success']);
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
        'ordem_servico_id' => $os['id'],
        'valor'      => $os['valor_total'],
        'vencimento' => date('Y-m-d'), // Por padrão, vencimento hoje
        'status'     => 'aberto',
        'historico'  => "Referente à OS #" . $os['numero_os']
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
        'status' => 'em_andamento', // Status que indica que o serviço começou
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

}