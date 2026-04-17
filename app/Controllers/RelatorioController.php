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
            return view('relatorios/telas/compras_v', $data);

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
}