<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\PdfLib;
use App\Models\ComprasRequisicaoModel;
use App\Models\ComprasItemModel;
use App\Models\OrdemServicoModel;
use App\Models\EmpresaModel;
use App\Models\ContasPagarModel;
use App\Models\ContasReceberModel;
use App\Models\FinanceiroMovimentacaoModel;






class RelatorioController extends BaseController
{
    protected $compraModel;
    protected $itemCompraModel;
    protected $osModel;
    protected $empresaModel;
    protected $contasPagarModel;
    protected $contasReceberModel;
    protected $financeiroMovimentacoesModel;
    protected $pdf;

    public function __construct()
    {
        // Inicializa os Models com os nomes corretos
        $this->compraModel     = new ComprasRequisicaoModel();
        $this->itemCompraModel = new ComprasItemModel();
        $this->osModel         = new OrdemServicoModel();
        $this->empresaModel    = new EmpresaModel();
        $this->contasPagarModel = new ContasPagarModel();
        $this->contasReceberModel = new ContasReceberModel();
        $this->financeiroMovimentacoesModel = new FinanceiroMovimentacaoModel();

        
        // Inicializa a biblioteca PdfLib (mPDF)
        $this->pdf = new PdfLib();
    }

    public function index()
    {
        $data = [
            'title' => 'Central de Relatórios',
            'menu'  => 'relatorios'
        ];

        return view('relatorios/index_v', $data);
    }

    /**
     * Roteador de geração de relatórios
     */
  public function gerar()
{
    $tipo   = $this->request->getGet('tipo_relatorio');
    $inicio = $this->request->getGet('data_inicio');
    $fim    = $this->request->getGet('data_fim');

    if (!$tipo || !$inicio || !$fim) {
        return redirect()->to('relatorios')->with('error', 'Filtros inválidos!');
    }

    // Dados comuns para as telas
    $data = [
        'inicio' => $inicio,
        'fim'    => $fim,
        'tipo'   => $tipo,
        'empresa' => $this->empresaModel->first()
    ];

    switch ($tipo) {
        case 'compras':
            $data['compras'] = $this->compraModel
                ->select('compras_requisicoes.*, fornecedores.nome_razao as nome_fornecedor')
                ->join('fornecedores', 'fornecedores.id = compras_requisicoes.fornecedor_id', 'left')
                ->where('compras_requisicoes.created_at >=', $inicio . ' 00:00:00')
                ->where('compras_requisicoes.created_at <=', $fim . ' 23:59:59')
                ->findAll();
            return view('relatorios/telas/relatorio_compras_v', $data);

        case 'fluxo':
    // Busca TODAS as movimentações (OS, Compras, Despesas Fixas, etc.)
    $movimentacoes = $this->financeiroMovimentacoesModel
        ->where('data_movimentacao >=', $inicio)
        ->where('data_movimentacao <=', $fim)
        ->orderBy('data_movimentacao', 'ASC')
        ->orderBy('id', 'ASC')
        ->findAll();

    $data['movimentacoes'] = $movimentacoes;
    
    // Cálculo de totais para o cabeçalho/rodapé do PDF
    $data['total_entradas'] = array_sum(array_column(array_filter($movimentacoes, fn($m) => $m['tipo'] == 'entrada'), 'valor'));
    $data['total_saidas']   = array_sum(array_column(array_filter($movimentacoes, fn($m) => $m['tipo'] == 'saida'), 'valor'));
    $data['saldo_periodo']  = $data['total_entradas'] - $data['total_saidas'];
    
    // Nome do arquivo PDF
    $data['titulo_relatorio'] = "Extrato de Fluxo de Caixa Completo";
   
            return view('relatorios/telas/fluxo_caixa_v', $data);
            
        // Adicione outros cases conforme necessário...
    }
            return redirect()->to('relatorios')->with('error', 'Filtros inválidos!');

}

/**
 * Método para Processar o PDF (Chamado pelo botão na tela de visualização)
 */
/**
 * Processa a exportação dos dados visualizados em tela para o formato PDF
 */
public function exportar_pdf()
{
    // 1. Captura os parâmetros da URL
    $tipo   = $this->request->getGet('tipo');
    $inicio = $this->request->getGet('inicio');
    $fim    = $this->request->getGet('fim');

    // Validação básica de segurança
    if (!$tipo || !$inicio || !$fim) {
        return redirect()->to('relatorios')->with('error', 'Parâmetros insuficientes para gerar o PDF.');
    }

    // 2. Busca dados da empresa para o cabeçalho
    $data['empresa'] = $this->empresaModel->first();
    $data['inicio']  = $inicio;
    $data['fim']     = $fim;

    // 3. Roteamento de dados conforme o tipo (Lógica similar ao visualizar, mas para PDF)
    switch ($tipo) {
        case 'compras':
            $data['compras'] = $this->compraModel
                ->select('compras_requisicoes.*, fornecedores.nome_razao as nome_fornecedor')
                ->join('fornecedores', 'fornecedores.id = compras_requisicoes.fornecedor_id', 'left')
                ->where('compras_requisicoes.created_at >=', $inicio . ' 00:00:00')
                ->where('compras_requisicoes.created_at <=', $fim . ' 23:59:59')
                ->findAll();
            
            $html = view('relatorios/pdf_compras_v', $data);
            $nomeArquivo = "Relatorio_Compras_{$inicio}.pdf";
            break;

       case 'fluxo':
    // Busca TODAS as movimentações (OS, Compras, Despesas Fixas, etc.)
    $movimentacoes = $this->financeiroMovimentacoesModel
        ->where('data_movimentacao >=', $inicio)
        ->where('data_movimentacao <=', $fim)
        ->orderBy('data_movimentacao', 'ASC')
        ->orderBy('id', 'ASC')
        ->findAll();

    $data['movimentacoes'] = $movimentacoes;
    
    // Cálculo de totais para o cabeçalho/rodapé do PDF
    $data['total_entradas'] = array_sum(array_column(array_filter($movimentacoes, fn($m) => $m['tipo'] == 'entrada'), 'valor'));
    $data['total_saidas']   = array_sum(array_column(array_filter($movimentacoes, fn($m) => $m['tipo'] == 'saida'), 'valor'));
    $data['saldo_periodo']  = $data['total_entradas'] - $data['total_saidas'];
    
    // Nome do arquivo PDF
    $data['titulo_relatorio'] = "Extrato de Fluxo de Caixa Completo";
    

            $data['saidas'] = $this->compraModel
                ->select('compras_requisicoes.id, compras_requisicoes.data_fechamento as data, fornecedores.nome_razao as favorecido, valor_total')
                ->join('fornecedores', 'fornecedores.id = compras_requisicoes.fornecedor_id', 'left')
                ->where('status', 'finalizada')
                ->where('data_fechamento >=', $inicio)
                ->where('data_fechamento <=', $fim)
                ->findAll();

            $html = view('relatorios/relatorio_fluxo_caixa_pdf_v', $data);
            $nomeArquivo = "Fluxo_Caixa_{$inicio}_a_{$fim}.pdf";
            break;

        case 'tecnico':
            // Aqui chamaremos a lógica de produtividade quando implementarmos
            return $this->relatorio_produtividade($inicio, $fim);

        default:
            return redirect()->to('relatorios')->with('error', 'Relatório não disponível para exportação.');
    }

    // 4. Dispara a geração através da nossa PdfLib
    // O destino 'I' abre no navegador, 'D' força o download
    return $this->pdf->gerar($html, $nomeArquivo, 'I');
}




    /**
     * RELATÓRIO: Compras por Período
     */
    private function relatorio_compras($inicio, $fim)
    {
        $compras = $this->compraModel
            ->select('compras_requisicoes.*, fornecedores.nome_razao as nome_fornecedor')
            ->join('fornecedores', 'fornecedores.id = compras_requisicoes.fornecedor_id', 'left')
            ->where('compras_requisicoes.created_at >=', $inicio . ' 00:00:00')
            ->where('compras_requisicoes.created_at <=', $fim . ' 23:59:59')
            ->findAll();

        $empresa = $this->empresaModel->first();

        $html = view('relatorios/pdf_compras_v', [
            'compras' => $compras,
            'inicio'  => $inicio,
            'fim'     => $fim,
            'empresa' => $empresa
        ]);

        $this->pdf->gerar($html, "Relatorio_Compras_{$inicio}_a_{$fim}.pdf");
    }

    /**
     * RELATÓRIO: Produtividade por Técnico
     * (Apenas esboço - podemos codificar a lógica completa agora se quiser)
     */
   

    /**
 * RELATÓRIO: Fluxo de Caixa (Entradas vs Saídas)
 */
private function relatorio_fluxo_caixa($inicio, $fim)
{
    // 1. Busca Entradas (Baseado em Ordens de Serviço Finalizadas)
    // Supomos que o campo de valor total na OS seja 'valor_total'
    $entradas = $this->osModel
        ->select('id, data_saida as data, cliente_nome, valor_total')
        ->where('status', 'finalizada') // ou o status que você usa para OS concluída
        ->where('data_saida >=', $inicio)
        ->where('data_saida <=', $fim)
        ->findAll();

    // 2. Busca Saídas (Baseado em Requisições de Compra Finalizadas)
    $saidas = $this->compraModel
        ->select('compras_requisicoes.id, compras_requisicoes.data_fechamento as data, fornecedores.nome_razao as favorecido, valor_total')
        ->join('fornecedores', 'fornecedores.id = compras_requisicoes.fornecedor_id', 'left')
        ->where('status', 'finalizada')
        ->where('data_fechamento >=', $inicio)
        ->where('data_fechamento <=', $fim)
        ->findAll();

    $empresa = $this->empresaModel->first();

    $html = view('relatorios/pdf_fluxo_caixa_v', [
        'entradas' => $entradas,
        'saidas'   => $saidas,
        'inicio'   => $inicio,
        'fim'      => $fim,
        'empresa'  => $empresa
    ]);

    $this->pdf->gerar($html, "Fluxo_Caixa_{$inicio}_a_{$fim}.pdf");
}
public function fluxo_caixa_dados()
{
    $inicio = $this->request->getGet('inicio');
    $fim    = $this->request->getGet('fim');

    // 1. BUSCA DE ENTRADAS (OS FINALIZADAS)
    $entradas = $this->osModel
        ->select('ordem_servicos.*, clientes.nome_razao as cliente_nome, DATE(data_fechamento) as data_dia')
        ->join('clientes', 'clientes.id = ordem_servicos.cliente_id', 'left')
        ->where('ordem_servicos.status', 'finalizada')
        ->where('data_fechamento >=', $inicio . ' 00:00:00')
        ->where('data_fechamento <=', $fim . ' 23:59:59')
        ->orderBy('data_fechamento', 'ASC')
        ->findAll();

    // 2. BUSCA DE OS CANCELADAS (PARA ESTATÍSTICAS)
    $canceladas = $this->osModel
        ->where('status', 'cancelada')
        ->where('updated_at >=', $inicio . ' 00:00:00')
        ->where('updated_at <=', $fim . ' 23:59:59')
        ->countAllResults();

    // 3. BUSCA DE SAÍDAS (COMPRAS FINALIZADAS)
    $saidas = $this->compraModel
        ->select('compras_requisicoes.*, fornecedores.nome_razao as favorecido, DATE(data_fechamento) as data_dia')
        ->join('fornecedores', 'fornecedores.id = compras_requisicoes.fornecedor_id', 'left')
        ->where('status', 'finalizada')
        ->where('data_fechamento >=', $inicio . ' 00:00:00')
        ->where('data_fechamento <=', $fim . ' 23:59:59')
        ->orderBy('data_fechamento', 'ASC')
        ->findAll();

    // 4. PROCESSAMENTO DO GRÁFICO TEMPORAL E MÉDIAS
    $periodo = new \DatePeriod(
        new \DateTime($inicio),
        new \DateInterval('P1D'),
        (new \DateTime($fim))->modify('+1 day')
    );

    $grafico = [
        'labels'   => [],
        'entradas' => [],
        'saidas'   => []
    ];

    foreach ($periodo as $data) {
        $dia = $data->format('Y-m-d');
        $grafico['labels'][] = $data->format('d/m');

        // Soma entradas do dia
        $somaE = array_sum(array_column(array_filter($entradas, function($e) use ($dia) {
            return $e['data_dia'] == $dia;
        }), 'valor_total'));

        // Soma saídas do dia
        $somaS = array_sum(array_column(array_filter($saidas, function($s) use ($dia) {
            return $s['data_dia'] == $dia;
        }), 'valor_total'));

        $grafico['entradas'][] = (float)$somaE;
        $grafico['saidas'][]   = (float)$somaS;
    }

    // 5. CÁLCULO DE TEMPO MÉDIO DE EXECUÇÃO (Horas)
    $temposExecucao = [];
    foreach ($entradas as $os) {
        if ($os['data_aprovacao'] && $os['data_fechamento']) {
            $dataAprov = new \DateTime($os['data_aprovacao']);
            $dataFech  = new \DateTime($os['data_fechamento']);
            $intervalo = $dataAprov->diff($dataFech);
            
            // Converte tudo para horas
            $horasTotais = ($intervalo->days * 24) + $intervalo->h + ($intervalo->i / 60);
            $temposExecucao[] = $horasTotais;
        }
    }
    $tempoMedio = count($temposExecucao) > 0 ? array_sum($temposExecucao) / count($temposExecucao) : 0;

    // 6. CÁLCULO DE MÉDIA MENSAL
    $d1 = new \DateTime($inicio);
    $d2 = new \DateTime($fim);
    $diffMeses = $d1->diff($d2)->m + ($d1->diff($d2)->y * 12);
    $mesesParaDivisao = ($diffMeses < 1) ? 1 : $diffMeses + 1;
    $mediaMensal = count($entradas) / $mesesParaDivisao;

    // 7. MONTAGEM DO RETORNO JSON
    return $this->response->setJSON([
        'html' => view('relatorios/telas/tabela_fluxo_parcial_v', [
            'entradas' => $entradas,
            'saidas'   => $saidas,
            'stats'    => [
                'total_concluidas' => count($entradas),
                'total_canceladas' => $canceladas,
                'tempo_medio'      => number_format($tempoMedio, 1, ',', '.'),
                'media_mensal'     => number_format($mediaMensal, 1, ',', '.')
            ]
        ]),
        'grafico' => $grafico,
        'totais'  => [
            'entradas' => array_sum($grafico['entradas']),
            'saidas'   => array_sum($grafico['saidas'])
        ]
    ]);
}
/**
 * Renderiza a tela principal do Fluxo de Caixa com filtros e containers para AJAX
 */
public function fluxo_caixa_view()
{
    // Dados básicos para o cabeçalho da página
    $data = [
        'title' => 'Relatório de Fluxo de Caixa',
        'menu'  => 'relatorios',
        // Podemos passar a empresa para o caso de querer exibir o logo já no carregamento
        'empresa' => $this->empresaModel->first()
    ];

    return view('relatorios/telas/relatorio_fluxo_caixa_v', $data);
}

private function calcularMediaMensal($total, $inicio, $fim) {
    $d1 = new \DateTime($inicio);
    $d2 = new \DateTime($fim);
    $meses = $d1->diff($d2)->m + ($d1->diff($d2)->y * 12) + 1;
    return number_format($total / $meses, 1, ',', '.');
}

public function compras_view()
{
    return view('relatorios/telas/relatorio_compras_v', ['title' => 'Relatório de Suprimentos']);
}

public function compras_dados()
{
    $inicio = $this->request->getGet('inicio');
    $fim    = $this->request->getGet('fim');

    // 1. Busca as requisições de compra finalizadas
    $compras = $this->compraModel
        ->select('compras_requisicoes.*, fornecedores.nome_razao as fornecedor_nome')
        ->join('fornecedores', 'fornecedores.id = compras_requisicoes.fornecedor_id', 'left')
        ->where('status', 'finalizada')
        ->where('data_fechamento >=', $inicio . ' 00:00:00')
        ->where('data_fechamento <=', $fim . ' 23:59:59')
        ->findAll();

    // 2. Busca itens para o gráfico (agrupando por Marca, já que não há categoria)
    $db = \Config\Database::connect();
    $itens = $db->table('compras_requisicoes_itens as itens') // Nome da tabela corrigido
        ->select('p.marca, SUM(itens.subtotal) as total_marca')
        ->join('produtos as p', 'p.id = itens.produto_id')
        ->join('compras_requisicoes as r', 'r.id = itens.requisicao_id') // Ajuste o nome da FK se necessário
        ->where('r.status', 'finalizada')
        ->where('r.data_fechamento >=', $inicio . ' 00:00:00')
        ->where('r.data_fechamento <=', $fim . ' 23:59:59')
        ->groupBy('p.marca')
        ->orderBy('total_marca', 'DESC')
        ->get()->getResultArray();

    // 3. Prepara dados do gráfico
    $grafico = [
        'labels' => array_map(function($i) { return $i['marca'] ?: 'Sem Marca'; }, $itens),
        'valores' => array_column($itens, 'total_marca')
    ];

    // 4. Estatísticas Operacionais
    $stats = [
        'total_compras'  => count($compras),
        'valor_total'    => (float) array_sum(array_column($compras, 'valor_total')),
        'ticket_medio'   => count($compras) > 0 ? array_sum(array_column($compras, 'valor_total')) / count($compras) : 0,
        'marcas_distintas' => count($itens)
    ];

    return $this->response->setJSON([
        'html' => view('relatorios/telas/tabela_compras_parcial_v', [
            'compras' => $compras,
            'stats'   => $stats
        ]),
        'grafico' => $grafico
    ]);
}
public function balanco_dados()
{
    try {
        $inicio = $this->request->getGet('inicio');
        $fim    = $this->request->getGet('fim');

        if (!$inicio || !$fim) {
            throw new \Exception("Datas não fornecidas.");
        }

        // 1. MOVIMENTAÇÕES REALIZADAS (Ordenação CRUCIAL para o saldo acumulado)
        $movimentacoes = $this->financeiroMovimentacoesModel
            ->where('data_movimentacao >=', $inicio)
            ->where('data_movimentacao <=', $fim)
            ->orderBy('data_movimentacao', 'ASC') // Garante a cronologia do extrato
            ->orderBy('id', 'ASC')               // Desempate por ordem de inserção
            ->findAll();

        // 2. BUSCA DE DADOS PARA SITUAÇÃO CRÍTICA (Saldo de Vencidos)
        $vencidasPagar = $this->contasPagarModel
            ->selectSum('valor_original')
            ->where('status', 'vencida')
            ->first();

        $pendentesReceber = $this->contasReceberModel
            ->selectSum('valor_original')
            ->whereIn('status', ['pendente', 'vencida'])
            ->first();

        $totalVencidoPagar = (float)($vencidasPagar['valor_original'] ?? 0);
        $totalPendenteReceber = (float)($pendentesReceber['valor_original'] ?? 0);

        // 3. ALERTAS: Contas Recorrentes (Pagar) do Mês Atual
        $alertasRecorrentes = $this->contasPagarModel
            ->where('is_recorrente', 1)
            ->whereIn('status', ['pendente', 'vencida'])
            ->where("DATE_FORMAT(data_vencimento, '%Y-%m')", date('Y-m'))
            ->findAll();

        // 4. ALERTAS: Devedores (Receber) do Mês Atual
        $alertasDevedores = $this->contasReceberModel
            ->select('contas_receber.*, clientes.nome_razao as devedor_nome')
            ->join('clientes', 'clientes.id = contas_receber.cliente_id')
            ->whereIn('contas_receber.status', ['pendente', 'vencida'])
            ->where("DATE_FORMAT(data_vencimento, '%Y-%m')", date('Y-m'))
            ->findAll();

        // 5. PROCESSAMENTO DO GRÁFICO (Usando o helper)
        $grafico = $this->prepararGraficoEvolucao($movimentacoes, $inicio, $fim);

        // 6. RENDERIZAÇÃO DA VIEW PARCIAL
        $html = view('relatorios/telas/tabela_balanco_parcial_v', [
            'movimentacoes' => $movimentacoes,
            'critico' => [
                'is_critico'     => ($totalVencidoPagar > $totalPendenteReceber),
                'vencidas_total' => $totalVencidoPagar,
                'receber_total'  => $totalPendenteReceber
            ],
            'alertas' => [
                'recorrentes' => $alertasRecorrentes,
                'devedores'   => $alertasDevedores
            ]
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'html'    => $html,
            'grafico' => $grafico
        ]);

    } catch (\Exception $e) {
        return $this->response->setStatusCode(500)->setJSON([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}
/**
 * Renderiza a página principal do Balanço e Saúde Financeira
 */
public function balanco_view()
{
    // O título pode ser passado para o layout_v através do array de dados
    $data = [
        'title' => 'Balanço de Saúde Financeira',
        // Você pode passar outras variáveis aqui se o seu layout_v exigir
    ];

    return view('relatorios/telas/relatorio_balanco_v', $data);
}

/**
 * Processa a evolução temporal para o gráfico misto e indicadores.
 * * @param array $movimentacoes Dados vindos de financeiro_movimentacoes
 * @param string $inicio Data Y-m-d
 * @param string $fim Data Y-m-d
 * @return array Estrutura para Chart.js
 */
private function prepararGraficoEvolucao($movimentacoes, $inicio, $fim)
{
    // Cria o período completo dia a dia para garantir que dias sem movimento apareçam no gráfico
    $periodo = new \DatePeriod(
        new \DateTime($inicio),
        new \DateInterval('P1D'),
        (new \DateTime($fim))->modify('+1 day')
    );

    $labels = [];
    $evolucaoDiaria = []; // Barras: Resultado individual de cada dia
    $caixaAcumulado = []; // Linha: Saldo real em conta naquele momento
    $saldoCorrente  = 0;

    foreach ($periodo as $data) {
        $dia = $data->format('Y-m-d');
        $labels[] = $data->format('d/m');

        // Filtra entradas e saídas do dia específico dentro do array já ordenado
        $entradasDia = array_filter($movimentacoes, function($m) use ($dia) {
            return $m['data_movimentacao'] == $dia && $m['tipo'] == 'entrada';
        });

        $saidasDia = array_filter($movimentacoes, function($m) use ($dia) {
            return $m['data_movimentacao'] == $dia && $m['tipo'] == 'saida';
        });

        // Soma os valores brutos
        $somaEntradas = array_sum(array_column($entradasDia, 'valor'));
        $somaSaidas   = array_sum(array_column($saidasDia, 'valor'));

        // Cálculo do fôlego do dia (Lucro ou Prejuízo diário)
        $resultadoDoDia = (float)$somaEntradas - (float)$somaSaidas;
        
        // Atualiza o saldo acumulado (Saúde Financeira)
        $saldoCorrente += $resultadoDoDia;

        // Alimenta os arrays para o Chart.js
        $evolucaoDiaria[] = $resultadoDoDia;
        $caixaAcumulado[] = (float)$saldoCorrente;
    }

    return [
        'labels'   => $labels,
        'evolucao' => $evolucaoDiaria, // Eixo Y das Barras
        'caixa'    => $caixaAcumulado  // Eixo Y da Linha de Tendência
    ];
}
public function produtividade_view()
{
    $inicio = $this->request->getGet('inicio') ?? date('Y-m-01');
    $fim = $this->request->getGet('fim') ?? date('Y-m-t');
    $empresaId = session()->get('empresa_id');

    $db = \Config\Database::connect();

    // 1. Resumo de OS no período
    $resumoOS = $this->osModel
        ->select('status, COUNT(id) as total, SUM(valor_total) as financeiro')
        ->where('empresa_id', $empresaId)
        ->where('data_abertura >=', $inicio)
        ->where('data_abertura <=', $fim)
        ->groupBy('status')
        ->findAll();

    // 2. Ranking de itens mais vendidos (Peças/Serviços)
    // Usando query builder para buscar na tabela de itens da OS
    $rankingItens = $db->table('ordem_servico_itens oi')
        ->select('oi.descricao, SUM(oi.quantidade) as qtd, SUM(oi.subtotal) as total_gerado')
        ->join('ordem_servicos os', 'os.id = oi.ordem_servico_id')
        ->where('os.empresa_id', $empresaId)
        ->where('os.data_abertura >=', $inicio)
        ->where('os.data_abertura <=', $fim)
        ->groupBy('oi.descricao')
        ->orderBy('total_gerado', 'DESC')
        ->limit(10)
        ->get()->getResultArray();

    $data = [
        'title'        => 'Relatório de Produtividade',
        'inicio'       => $inicio,
        'fim'          => $fim,
        'resumoOS'     => $resumoOS,
        'rankingItens' => $rankingItens
    ];

    return view('relatorios/produtividade_v', $data);
}

}