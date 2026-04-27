<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\PdfLib;
use App\Models\ComprasRequisicaoModel;
use App\Models\ComprasItemModel;
use App\Models\OrdemServicoModel;
use App\Models\EmpresaModel;

class RelatorioController extends BaseController
{
    protected $compraModel;
    protected $itemCompraModel;
    protected $osModel;
    protected $empresaModel;
    protected $pdf;

    public function __construct()
    {
        // Inicializa os Models com os nomes corretos
        $this->compraModel     = new ComprasRequisicaoModel();
        $this->itemCompraModel = new ComprasItemModel();
        $this->osModel         = new OrdemServicoModel();
        $this->empresaModel    = new EmpresaModel();
        
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
            $data['entradas'] = $this->osModel
                ->select('id, data_saida as data, cliente_nome, valor_total')
                ->where('status', 'finalizada')
                ->where('data_saida >=', $inicio)
                ->where('data_saida <=', $fim)
                ->findAll();

            $data['saidas'] = $this->compraModel
                ->select('compras_requisicoes.id, compras_requisicoes.data_fechamento as data, fornecedores.nome_razao as favorecido, valor_total')
                ->join('fornecedores', 'fornecedores.id = compras_requisicoes.fornecedor_id', 'left')
                ->where('status', 'finalizada')
                ->where('data_fechamento >=', $inicio)
                ->where('data_fechamento <=', $fim)
                ->findAll();
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
            $data['entradas'] = $this->osModel
                ->select('id, data_saida as data, cliente_nome, valor_total')
                ->where('status', 'finalizada')
                ->where('data_saida >=', $inicio)
                ->where('data_saida <=', $fim)
                ->findAll();

            $data['saidas'] = $this->compraModel
                ->select('compras_requisicoes.id, compras_requisicoes.data_fechamento as data, fornecedores.nome_razao as favorecido, valor_total')
                ->join('fornecedores', 'fornecedores.id = compras_requisicoes.fornecedor_id', 'left')
                ->where('status', 'finalizada')
                ->where('data_fechamento >=', $inicio)
                ->where('data_fechamento <=', $fim)
                ->findAll();

            $html = view('relatorios/pdf_fluxo_caixa_v', $data);
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
    private function relatorio_produtividade($inicio, $fim)
    {
        // Lógica para buscar OS finalizadas e somar mão de obra por técnico
        die("Relatório de produtividade em desenvolvimento com OrdemServicoModel...");
    }

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
    $inicio = $this->request->getGet('inicio');
    $fim    = $this->request->getGet('fim');

    // 1. Totais de Receitas (OS Finalizadas)
    $receitas = $this->osModel
        ->select('DATE(data_fechamento) as dia, SUM(valor_total) as total')
        ->where('status', 'finalizada')
        ->where('data_fechamento >=', $inicio . ' 00:00:00')
        ->where('data_fechamento <=', $fim . ' 23:59:59')
        ->groupBy('DATE(data_fechamento)')
        ->findAll();

    // 2. Totais de Despesas (Compras Finalizadas)
    $despesas = $this->compraModel
        ->select('DATE(data_fechamento) as dia, SUM(valor_total) as total')
        ->where('status', 'finalizada')
        ->where('data_fechamento >=', $inicio . ' 00:00:00')
        ->where('data_fechamento <=', $fim . ' 23:59:59')
        ->groupBy('DATE(data_fechamento)')
        ->findAll();

    // 3. Processamento da Evolução Temporal
    $periodo = new \DatePeriod(new \DateTime($inicio), new \DateInterval('P1D'), (new \DateTime($fim))->modify('+1 day'));
    
    $labels = [];
    $dataLucroPrejuizo = [];
    $dataSaldoAcumulado = [];
    $saldoAcumulado = 0;
    $totalR = 0;
    $totalD = 0;

    foreach ($periodo as $dt) {
        $dia = $dt->format('Y-m-d');
        $labels[] = $dt->format('d/m');

        $rDia = array_sum(array_column(array_filter($receitas, fn($r) => $r['dia'] == $dia), 'total'));
        $dDia = array_sum(array_column(array_filter($despesas, fn($d) => $d['dia'] == $dia), 'total'));
        
        $lucroDia = $rDia - $dDia;
        $saldoAcumulado += $lucroDia;
        
        $dataLucroPrejuizo[] = (float)$lucroDia;
        $dataSaldoAcumulado[] = (float)$saldoAcumulado;
        
        $totalR += $rDia;
        $totalD += $dDia;
    }

    return $this->response->setJSON([
        'html' => view('relatorios/telas/tabela_balanco_parcial_v', [
            'receitas' => $totalR,
            'despesas' => $totalD,
            'lucro'    => $totalR - $totalD
        ]),
        'grafico' => [
            'labels' => $labels,
            'evolucao' => $dataLucroPrejuizo, // Barras (Resultado do dia)
            'caixa' => $dataSaldoAcumulado   // Linha (Saúde do caixa)
        ]
    ]);
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

}