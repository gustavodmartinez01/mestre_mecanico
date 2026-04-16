<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ContasReceberModel;
use App\Models\ClienteModel;
use App\Libraries\PdfLib;
use App\Models\EmpresaModel;
use App\Models\FinanceiroMovimentacaoModel;


class ContasReceberController extends BaseController
{
    protected $contaModel;
    protected $clienteModel;
    protected $movModel;

    public function __construct()
    {
        $this->contaModel = new ContasReceberModel();
        $this->clienteModel = new ClienteModel();
        $this->atualizarStatus();
        $this->movModel = new FinanceiroMovimentacaoModel();

    }

    public function index()
{
    // 1. Captura de Filtros do GET
    $busca    = $this->request->getGet('busca');
    $status   = $this->request->getGet('status');
    $completa = $this->request->getGet('completa');
    $hoje     = date('Y-m-d');

    // 2. Construção da Query Principal com Agrupamento
    // A situacao_grupo é calculada em tempo real baseada nos status já atualizados pelo constructor
    $this->contaModel->select("
        contas_receber.id_agrupador, 
        contas_receber.descricao, 
        contas_receber.completa,
        contas_receber.cliente_id,
        MIN(contas_receber.data_vencimento) as primeira_venc, 
        MAX(contas_receber.data_vencimento) as ultima_venc,
        SUM(contas_receber.valor_original) as valor_total_grupo,
        COUNT(contas_receber.id) as total_parcelas_grupo,
        SUM(CASE WHEN contas_receber.status = 'paga' THEN 1 ELSE 0 END) as parcelas_pagas,
        SUM(contas_receber.valor_pago) as total_recebido,
        
        /* Lógica de Status do Grupo Consolidado */
        CASE 
            WHEN SUM(CASE WHEN status != 'paga' THEN 1 ELSE 0 END) = 0 THEN 'quitado'
            WHEN SUM(CASE WHEN status = 'vencida' THEN 1 ELSE 0 END) > 0 THEN 'atrasado'
            WHEN SUM(CASE WHEN status = 'cancelada' THEN 1 ELSE 0 END) > 0 THEN 'cancelada'
            ELSE 'em_dia'
        END as situacao_grupo
    ");
    
    // Join para pegar o nome do cliente
    $this->contaModel->join('clientes', 'clientes.id = contas_receber.cliente_id');
    $this->contaModel->select('clientes.nome_razao as cliente_nome');

    // Aplicação dos Filtros
    if (!empty($busca)) {
        $this->contaModel->groupStart()
            ->like('clientes.nome_razao', $busca)
            ->orLike('contas_receber.descricao', $busca)
            ->orLike('contas_receber.id_agrupador', $busca)
        ->groupEnd();
    }

    if (!empty($status)) {
        $this->contaModel->where('contas_receber.status', $status);
    }

    if ($completa !== null && $completa !== '') {
        $this->contaModel->where('contas_receber.completa', $completa);
    }

    // Busca os dados agrupados
    $data['contas'] = $this->contaModel->groupBy('contas_receber.id_agrupador')
                                       ->orderBy('primeira_venc', 'ASC')
                                       ->findAll();

    // 3. Cálculo das Estatísticas (Widgets Superiores)
    // Criamos uma nova instância para limpar os filtros da query principal
    $statsModel = new \App\Models\ContasReceberModel();

    $data['stats'] = [
        'vencidas' => $statsModel->where('status', 'vencida')
                                 ->selectSum('valor_original')
                                 ->first()['valor_original'] ?? 0,

        'hoje'     => $statsModel->where('data_vencimento', $hoje)
                                 ->where('status', 'pendente')
                                 ->selectSum('valor_original')
                                 ->first()['valor_original'] ?? 0,

        'incompletas' => $statsModel->where('completa', 0)
                                    ->countAllResults(),

        'recebido_mes' => $statsModel->where('status', 'paga')
                                     ->where('MONTH(data_pagamento)', date('m'))
                                     ->where('YEAR(data_pagamento)', date('Y'))
                                     ->selectSum('valor_pago')
                                     ->first()['valor_pago'] ?? 0,
    ];

    // 4. Retorno dos filtros para manter o formulário preenchido
    $data['filter'] = [
        'busca'    => $busca,
        'status'   => $status,
        'completa' => $completa
    ];

    return view('financeiro/contas_receber_v', $data);
}
//*********************************** */

    private function getSumStatus($status, $empId) {
        $res = $this->contaModel->where(['status' => $status, 'empresa_id' => $empId])
                                ->selectSum('valor_original')
                                ->first();
        return $res['valor_original'] ?? 0;
    }

    private function getSumHoje($empId) {
        $res = $this->contaModel->where([
                                    'data_vencimento' => date('Y-m-d'), 
                                    'status' => 'pendente', 
                                    'empresa_id' => $empId
                                ])
                                ->selectSum('valor_original')
                                ->first();
        return $res['valor_original'] ?? 0;
    }

    private function getSumRecebidoMes($empId) {
        $res = $this->contaModel->where(['empresa_id' => $empId, 'status' => 'paga'])
                                ->where('MONTH(data_pagamento)', date('m'))
                                ->where('YEAR(data_pagamento)', date('Y'))
                                ->selectSum('valor_pago')
                                ->first();
        return $res['valor_pago'] ?? 0;
    }

    /**
 * Prepara a tela de parcelamento
 */
public function completar($id_agrupador)
{
    $contaBase = $this->contaModel->where('id_agrupador', $id_agrupador)->first();
    
    if (!$contaBase) {
        return redirect()->to('contas-receber')->with('error', 'Lançamento não encontrado.');
    }

    // Buscamos o nome do cliente para exibir na view
    $db = \Config\Database::connect();
    $cliente = $db->table('clientes')->where('id', $contaBase['cliente_id'])->get()->getRowArray();

    $data = [
        'titulo' => 'Configurar Parcelamento',
        'conta'  => array_merge($contaBase, ['cliente_nome' => $cliente['nome_razao']]),
        'modo'   => 'completar' 
    ];

    return view('financeiro/completar_conta_v', $data);
}

/**
 * Recebe a grade do JS e salva as parcelas reais
 */
public function salvar_parcelamento()
{
    $id_agrupador = $this->request->getPost('id_agrupador');
    $parcelas = $this->request->getPost('parcelas'); // Array vindo da tabela JS
    $modo = $this->request->getPost('modo');

    if (empty($parcelas)) {
        return redirect()->back()->with('error', 'Nenhuma parcela foi gerada.');
    }

    // 1. Buscamos os dados da conta "rascunho" (completa=0) para replicar os IDs
    $rascunho = $this->contaModel->where('id_agrupador', $id_agrupador)->first();

    if (!$rascunho) {
        return redirect()->to('/financeiro/contas-receber')->with('error', 'Erro ao localizar origem.');
    }

    $db = \Config\Database::connect();
    $db->transStart(); // Inicia transação para segurança total

    // 2. Removemos o rascunho incompleto para dar lugar às parcelas reais
    $this->contaModel->where('id_agrupador', $id_agrupador)->delete();

    // 3. Loop para inserir cada parcela da grade
    $totalParcelas = count($parcelas);
    foreach ($parcelas as $index => $p) {
        $dataInserto = [
            'empresa_id'      => session()->get('empresa_id'),
            'cliente_id'      => $rascunho['cliente_id'],
            'os_id'           => $rascunho['os_id'],
            'id_agrupador'    => $id_agrupador, // Mantemos o mesmo HASH
            'descricao'       => $rascunho['descricao'] . " ({$index}/{$totalParcelas})",
            'valor_original'  => $p['valor'],
            'data_vencimento' => $p['data'],
            'status'          => 'pendente',
            'completa'        => 1, // AGORA ESTÁ COMPLETA
            'parcela_atual'   => $index,
            'total_parcelas'  => $totalParcelas,
            'created_at'      => date('Y-m-d H:i:s')
        ];

        $this->contaModel->insert($dataInserto);
    }

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->back()->with('error', 'Erro ao salvar parcelamento.');
    }

    return redirect()->to('contas-receber')->with('success', 'Financeiro gerado com sucesso!');
}
// Método para carregar os dados na Modal de Baixa via AJAX ou preenchimento direto
    public function receber($id_agrupador)
    {
        // Aqui você pode retornar um JSON ou os dados para a modal
        $contas = $this->contaModel->where('id_agrupador', $id_agrupador)->findAll();
        return $this->response->setJSON($contas);
    }

 // Busca a parcela pendente com a data de vencimento mais antiga
  public function obter_proxima($id_agrupador)
{
    // 1. Tenta buscar a PARCELA VENCIDA mais antiga (Prioridade 1)
    $parcela = $this->contaModel->where('id_agrupador', $id_agrupador)
                                ->where('status', 'vencida')
                                ->orderBy('data_vencimento', 'ASC')
                                ->first();

    // 2. Se não encontrou vencida, busca a PENDENTE mais próxima (Prioridade 2)
    if (!$parcela) {
        $parcela = $this->contaModel->where('id_agrupador', $id_agrupador)
                                    ->where('status', 'pendente')
                                    ->orderBy('data_vencimento', 'ASC')
                                    ->first();
    }

    // Adicionamos informações extras que a Modal utiliza para exibição
    if ($parcela) {
        // Busca o total de parcelas do grupo para montar o "1/10" na modal
        $total = $this->contaModel->where('id_agrupador', $id_agrupador)->countAllResults();
        
        // Descobre qual é o número desta parcela (Ex: é a 3ª de 10)
        // Isso assume que suas parcelas são geradas com IDs sequenciais ou datas distintas
        $numero_parcela = $this->contaModel->where('id_agrupador', $id_agrupador)
                                           ->where('data_vencimento <=', $parcela['data_vencimento'])
                                           ->countAllResults();

        $parcela['parcela_atual'] = $numero_parcela;
        $parcela['total_parcelas'] = $total;
        $parcela['status_operacao'] = true;
    } else {
        return $this->response->setJSON(['status_operacao' => false, 'msg' => 'Nenhuma parcela pendente encontrada.']);
    }

    return $this->response->setJSON($parcela);
}
    // Método que processa a baixa (registrar pagamento)
 
public function cancelar_grupo($id_agrupador)
{
    if (empty($id_agrupador)) {
        return redirect()->back()->with('error', 'Agrupador não identificado.');
    }

    // Preparamos os dados de atualização
    $data = [
        'status'     => 'cancelada',
        'updated_at' => date('Y-m-d H:i:s')
    ];

    // Iniciamos uma transação para garantir que ou cancela tudo ou nada
    $db = \Config\Database::connect();
    $db->transStart();

    // Cancelamos apenas as que NÃO estão pagas dentro desse agrupador
    $this->contaModel->where('id_agrupador', $id_agrupador)
                     ->whereIn('status', ['pendente', 'vencida'])
                     ->set($data)
                     ->update();

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->back()->with('error', 'Erro ao tentar cancelar as parcelas do grupo.');
    }

    return redirect()->to(base_url('contas-receber'))
                     ->with('success', 'Grupo de parcelas cancelado com sucesso!');
}

public function novo()
{
    // Aqui você deve buscar os clientes para o select do formulário
    $data['clientes'] = $this->clienteModel->orderBy('nome_razao', 'ASC')->findAll();
    return view('financeiro/contas_receber_form_v', $data);
}

public function salvar()
{
    $valor_total = (float) $this->request->getPost('valor_total');
    $n_parcelas  = (int) $this->request->getPost('parcelas');
    $primeiro_venc = $this->request->getPost('data_vencimento');
    
    // Geramos um ID único para este grupo de parcelas
    $id_agrupador = bin2hex(random_bytes(8)); 
    $valor_parcela = $valor_total / $n_parcelas;

    $db = \Config\Database::connect();
    $db->transStart();

    for ($i = 1; $i <= $n_parcelas; $i++) {
        // Calcula a data de vencimento (soma 1 mês para cada parcela após a primeira)
        $vencimento = date('Y-m-d', strtotime("+" . ($i - 1) . " month", strtotime($primeiro_venc)));

        $data = [
            'empresa_id'      => session()->get('empresa_id'), // ID da sua empresa logada
            'cliente_id'      => $this->request->getPost('cliente_id'),
            'descricao'       => $this->request->getPost('descricao'),
            'valor_original'  => $valor_parcela,
            'parcela_atual'   => $i,
            'total_parcelas'  => $n_parcelas,
            'id_agrupador'    => $id_agrupador,
            'data_vencimento' => $vencimento,
            'status'          => 'pendente',
            'completa'        => 1 // Conta manual já nasce completa
        ];

        $this->contaModel->insert($data);
    }

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->back()->with('error', 'Erro ao gerar as parcelas.');
    }

    return redirect()->to(base_url('contas-receber'))->with('success', "Lançamento de {$n_parcelas}x realizado com sucesso!");
}


public function imprimir_extrato($id_agrupador)
{
    $empresaModel = new EmpresaModel();
    
    // 1. Busca as parcelas
    $data['parcelas'] = $this->contaModel->where('id_agrupador', $id_agrupador)
                                         ->orderBy('parcela_atual', 'ASC')
                                         ->findAll();

    if (!$data['parcelas']) {
        return redirect()->back()->with('error', 'Extrato não encontrado.');
    }

    // 2. Busca dados complementares (Empresa e Cliente)
    $data['empresa'] = $empresaModel->find(session()->get('empresa_id'));
    $data['cliente'] = $this->clienteModel->find($data['parcelas'][0]['cliente_id']);

    // 3. Renderiza a view para uma variável HTML
    $html = view('financeiro/extrato_pdf_v', $data);

    // 4. Gera o PDF usando sua biblioteca
    $pdf = new PdfLib();
    $nomeArquivo = 'Extrato_' . $id_agrupador . '.pdf';
    $pdf->gerar($html, $nomeArquivo, 'I');
}
// Exemplo de lógica para rodar antes do SELECT da index
public function atualizarStatus() {
    $hoje = date('Y-m-d');

    // 1. Marcar como VENCIDA: 
    // Onde vencimento < hoje E não tem data_pagamento E status ainda é 'pendente'
    $this->contaModel
             ->set('status', 'vencida')
             ->where('data_vencimento <', $hoje)
             ->where('data_pagamento', null)
             ->where('status', 'pendente')
             ->update();

    // 2. Marcar como PAGA (Segurança):
    // Caso algum registro tenha data_pagamento mas o status ainda não tenha virado
    $this->contaModel
             ->set('status', 'paga')
             ->where('data_pagamento !=', null)
             ->update();
}

public function imprimir_recibo($id_parcela)
{
    // Busca a parcela específica com os dados do cliente
    $data['parcela'] = $this->contaModel
        ->select('contas_receber.*, clientes.nome_razao, clientes.documento, clientes.logradouro')
        ->join('clientes', 'clientes.id = contas_receber.cliente_id')
        ->where('contas_receber.id', $id_parcela)
        ->first();

    if (!$data['parcela'] || !$data['parcela']['data_pagamento']) {
        return redirect()->back()->with('error', 'Recibo indisponível. A parcela não foi paga.');
    }

    $html = view('financeiro/recibo_pagamento_pdf_v', $data);
    
    $pdfLib = new \App\Libraries\PdfLib();
    return $pdfLib->gerar($html, "Recibo_Pagamento_{$id_parcela}.pdf");
}

public function imprimir_cancelamento($id_agrupador)
{
  $empresaModel = new EmpresaModel();
  $data['empresa'] = $empresaModel->find(session()->get('empresa_id'));


// Busca os dados do grupo e do cliente
    $data['cliente'] = $this->contaModel
        ->select('clientes.nome_razao, clientes.documento, contas_receber.descricao, contas_receber.id_agrupador')
        ->join('clientes', 'clientes.id = contas_receber.cliente_id')
        ->where('id_agrupador', $id_agrupador)
        ->first();

    // Busca apenas parcelas NÃO PAGAS (as que foram efetivamente canceladas)
    $data['parcelas'] = $this->contaModel
        ->where('id_agrupador', $id_agrupador)
        ->where('status', 'cancelada') // Ou status que você definiu no cancelamento
        ->orderBy('data_vencimento', 'ASC')
        ->findAll();

    $html = view('financeiro/comprovante_cancelamento_pdf_v', $data);
    
    $pdfLib = new \App\Libraries\PdfLib();
    return $pdfLib->gerar($html, "Comprovante_Cancelamento_{$id_agrupador}.pdf");
}


//LISTAR PARCELAS PAGAS
public function listar_pagas($id_agrupador)
{
    $parcelas = $this->contaModel
        ->where('id_agrupador', $id_agrupador)
        ->where('status', 'paga')
        ->orderBy('data_pagamento', 'DESC')
        ->findAll();

    return $this->response->setJSON($parcelas);
}


public function imprimir_recibos_lote()
{
    // 1. Captura de dados da Modal
    $id_parcela     = $this->request->getPost('id_parcela');
    $data_pagamento = $this->request->getPost('data_pagamento');
    $forma_pagto    = $this->request->getPost('forma_pagamento');
    
    // Valores financeiros (convertidos para float para cálculos precisos)
    $valor_pago     = (float) $this->request->getPost('valor_recebido');
    $valor_multa    = (float) $this->request->getPost('valor_multa');
    $valor_juros    = (float) $this->request->getPost('valor_juros');
    $valor_desconto = (float) $this->request->getPost('valor_desconto');

    // 2. Validação de segurança
    if (empty($id_parcela)) {
        return redirect()->back()->with('error', 'ID da parcela não identificado para a baixa.');
    }

    // 3. Busca a parcela para auditoria e feedback
    $parcela = $this->contaModel->find($id_parcela);
    if (!$parcela) {
        return redirect()->back()->with('error', 'Lançamento não encontrado no banco de dados.');
    }

    // 4. Preparação do Array de Update (Mapeado com sua estrutura de tabela)
    $dataUpdate = [
        'status'                => 'paga',
        'data_pagamento'        => $data_pagamento,
        'forma_pagamento'       => $forma_pagto,
        'valor_pago'            => $valor_pago,     // Valor final que entrou no caixa
        'multa_mora'            => $valor_multa,    // Coluna específica da sua tabela
        'juros_mora'            => $valor_juros,    // Coluna específica da sua tabela
        'desconto'              => $valor_desconto, // Coluna específica da sua tabela
        'atualizacao_monetaria' => 0.00,            // Reservado para uso futuro se necessário
        'updated_at'            => date('Y-m-d H:i:s')
    ];

    // 5. Execução com Transação para garantir segurança atômica
    $db = \Config\Database::connect();
    $db->transStart();

    try {
        $this->contaModel->update($id_parcela, $dataUpdate);
        // 2. Registra no Fluxo de Caixa (financeiro_movimentacao)
   $this->movModel->registrar
    ([
        'empresa_id'        => session()->get('empresa_id'),
        'tipo'              => 'entrada',
        'categoria_id'      => $parcela['categoria_id'] ?? null,
        'descricao'         => "RECEBIMENTO: " . $parcela['descricao'],
        'valor'             => $valor_pago,
        'data_movimentacao' => $data_pagamento,
        'origem_tabela'     => 'contas_receber',
        'origem_id'         => $parcela['id'],
        'observacoes'       => 'Baixa realizada com sucesso.'
    ]);

        
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Erro ao processar a baixa. A transação foi cancelada.'.$smg);
        }

        // 6. Sucesso: Redireciona com mensagem detalhada
        $msg = "Baixa realizada com sucesso! Parcela {$parcela['parcela_atual']}/{$parcela['total_parcelas']} quitada.";
                
       
        return $this->response->setJSON(['status' => true,'id_parcela'=> $id_parcela, 'msg' => 'Recebimentos registrado com sucesso!'.$msg]);
  
    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->back()->with('error', 'Ocorreu um erro inesperado: ' . $e->getMessage());
    }
    }

// METODO ALTERNATIVO PROCESSAR BAIXA 
public function processar_baixa()
{
    // 1. Captura de dados da Modal
    $id_parcela     = $this->request->getPost('id_parcela');
    $data_pagamento = $this->request->getPost('data_pagamento');
    $forma_pagto    = $this->request->getPost('forma_pagamento');
    
    // Valores financeiros (convertidos para float para cálculos precisos)
    $valor_pago     = (float) $this->request->getPost('valor_recebido');
    $valor_multa    = (float) $this->request->getPost('valor_multa');
    $valor_juros    = (float) $this->request->getPost('valor_juros');
    $valor_desconto = (float) $this->request->getPost('valor_desconto');

    // 2. Validação de segurança
    if (empty($id_parcela)) {
        return redirect()->back()->with('error', 'ID da parcela não identificado para a baixa.');
    }

    // 3. Busca a parcela para auditoria e feedback
    $parcela = $this->contaModel->find($id_parcela);
    if (!$parcela) {
        return redirect()->back()->with('error', 'Lançamento não encontrado no banco de dados.');
    }

    // 4. Preparação do Array de Update (Mapeado com sua estrutura de tabela)
    $dataUpdate = [
        'status'                => 'paga',
        'data_pagamento'        => $data_pagamento,
        'forma_pagamento'       => $forma_pagto,
        'valor_pago'            => $valor_pago,     // Valor final que entrou no caixa
        'multa_mora'            => $valor_multa,    // Coluna específica da sua tabela
        'juros_mora'            => $valor_juros,    // Coluna específica da sua tabela
        'desconto'              => $valor_desconto, // Coluna específica da sua tabela
        'atualizacao_monetaria' => 0.00,            // Reservado para uso futuro se necessário
        'updated_at'            => date('Y-m-d H:i:s')
    ];

    // 5. Execução com Transação para garantir segurança atômica
    $db = \Config\Database::connect();
    $db->transStart();

    try {
        $this->contaModel->update($id_parcela, $dataUpdate);
        // 2. Registra no Fluxo de Caixa (financeiro_movimentacao)
   $this->movModel->registrar
    ([
        'empresa_id'        => session()->get('empresa_id'),
        'tipo'              => 'entrada',
        'categoria_id'      => $parcela['categoria_id'] ?? null,
        'descricao'         => "RECEBIMENTO: " . $parcela['descricao'],
        'valor'             => $valor_pago,
        'data_movimentacao' => $data_pagamento,
        'origem_tabela'     => 'contas_receber',
        'origem_id'         => $parcela['id'],
        'observacoes'       => 'Baixa realizada com sucesso.'
    ]);

        
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Erro ao processar a baixa. A transação foi cancelada.'.$smg);
        }

        // 6. Sucesso: Redireciona com mensagem detalhada
        $msg = "Baixa realizada com sucesso! Parcela {$parcela['parcela_atual']}/{$parcela['total_parcelas']} quitada.";
        
        
        //$url = base_url('contas-receber/imprimir-recibo/'.$id_parcela);
     
        //$this->imprimir_recibo($id_parcela);
         //return redirect()->to('contas-receber')->with('success', $msg);

        return $this->response->setJSON(['status' => true,'id_parcela'=> $id_parcela, 'msg' => 'Recebimentos registrado com sucesso!'.$msg]);
  
    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->back()->with('error', 'Ocorreu um erro inesperado: ' . $e->getMessage());
    }
}


}