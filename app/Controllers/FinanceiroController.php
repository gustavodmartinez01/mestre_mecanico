<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrdemServicoModel;
use App\Models\ContasReceberModel; // Usando seu model oficial

class FinanceiroController extends BaseController
{
    protected $osModel;
    protected $contaModel;

    public function __construct()
    {
        $this->osModel = new OrdemServicoModel();
        $this->contaModel = new ContasReceberModel();
    }

    public function pagamento($id)
    {
        // Importante: use o método que criamos no Model para vir com nome do cliente e placa
        $os = $this->osModel->getOsCompleta($id);

        if (!$os) {
            return redirect()->to(base_url('os'))->with('error', 'Ordem de Serviço não encontrada.');
        }

        if ($os['status'] == 'finalizada' || $os['status'] == 'cancelada') {
            return redirect()->to(base_url('os/gerenciar/' . $id))->with('info', 'Esta OS já foi encerrada financeiramente.');
        }

        $data = [
            'titulo' => 'Fechamento de Caixa - OS #' . $os['numero_os'],
            'os'     => $os
        ];

        return view('financeiro/pagamento_v', $data);
    }

    public function processar_pagamento()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Acesso negado.']);
        }

        $osId       = $this->request->getPost('os_id');
        $formaPgto  = $this->request->getPost('forma_pgto');
        $totalFinal = $this->request->getPost('total_final');
        $desconto   = $this->request->getPost('desconto') ?? 0;
        $juros      = $this->request->getPost('juros') ?? 0;

        // Busca dados da OS para pegar cliente_id e empresa_id
        $os = $this->osModel->find($osId);

        // 1. Atualiza a OS
        $this->osModel->update($osId, [
            'status'          => 'finalizada',
            'valor_total'     => $totalFinal,
            'data_fechamento' => date('Y-m-d H:i:s')
        ]);

        // 2. Define status baseado na forma de pagamento
        $formasImediatas = ['dinheiro', 'pix', 'cartao_debito']; // Ajuste conforme seu ENUM
        $estaPago = in_array($formaPgto, $formasImediatas);
        
        // 3. Monta dados para sua tabela contas_receber
        $dadosFinanceiros = [
            'empresa_id'      => session()->get('empresa_id'),
            'cliente_id'      => $os['cliente_id'],
            'os_id'           => $osId,
            'descricao'       => "Pagamento OS #" . ($os['numero_os'] ?? $osId),
            'valor_original'  => $totalFinal + $desconto - $juros, // Cálculo reverso do principal
            'valor_pago'      => $estaPago ? $totalFinal : 0.00,
            'desconto'        => $desconto,
            'juros_mora'      => $juros,
            'data_vencimento' => date('Y-m-d'),
            'data_pagamento'  => $estaPago ? date('Y-m-d') : null,
            'status'          => $estaPago ? 'paga' : 'pendente',
            'forma_pagamento' => $formaPgto,
            'observacoes'     => "Processado via Mestre Mecânico"
        ];

        if ($this->contaModel->insert($dadosFinanceiros)) {
            $idFinanceiro = $this->contaModel->getInsertID();
            
            return $this->response->setJSON([
                'status'  => 'success',
                'pago'    => $estaPago,
                'id_financeiro' => $idFinanceiro // Enviamos o ID para o AJAX gerar o comprovante
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Erro ao gravar lançamento.']);
    }

    /**
     * Gera o PDF do comprovante baseado no ID da contas_receber
     */
   public function imprimir_comprovante($id)
{
    
    // O join aqui é necessário para trazer os dados do cliente e veículo no recibo
    $registro = $this->contaModel->select('contas_receber.*, clientes.nome_razao, clientes.documento, veiculos.placa, veiculos.modelo')
        ->join('clientes', 'clientes.id = contas_receber.cliente_id')
        ->join('ordem_servicos', 'ordem_servicos.id = contas_receber.os_id', 'left')
        ->join('veiculos', 'veiculos.id = ordem_servicos.veiculo_id', 'left')
        ->where('contas_receber.id', $id)
        ->first();

    if (!$registro) {
        return redirect()->to('financeiro/lista')->with('error', 'Comprovante não encontrado.');
    }

    // Para a empresa, você pode usar o EmpresaModel para manter o padrão
    $empresaModel = new \App\Models\EmpresaModel();
    $empresa = $empresaModel->find($registro['empresa_id']);

    $data = [
        'p'       => $registro,
        'empresa' => $empresa
    ];

    $pdf = new \App\Libraries\PdfLib();
    $html = view('financeiro/comprovante_pdf_v', $data);
    
    return $pdf->gerar($html, "Recibo_".$registro['id'].".pdf");
}
public function fluxo_caixa()
{
    return view('financeiro/fluxo_caixa_v', [
        'title' => 'Fluxo de Caixa (Movimentações)'
    ]);
}

public function fluxo_caixa_dados()
{
    $inicio = $this->request->getGet('inicio') ?: date('Y-m-01');
    $fim    = $this->request->getGet('fim') ?: date('Y-m-d');

    // Busca as movimentações reais da tabela financeiro_movimentacoes
    $movimentacoes = $this->financeiroMovimentacoesModel
        ->where('data_movimentacao >=', $inicio)
        ->where('data_movimentacao <=', $fim)
        ->orderBy('data_movimentacao', 'DESC') // Ordem inversa para ver o mais recente primeiro
        ->findAll();

    // Calcula os totais do período
    $totalEntradas = array_sum(array_column(array_filter($movimentacoes, fn($m) => $m['tipo'] == 'entrada'), 'valor'));
    $totalSaidas   = array_sum(array_column(array_filter($movimentacoes, fn($m) => $m['tipo'] == 'saida'), 'valor'));

    return $this->response->setJSON([
        'html' => view('financeiro/tabela_fluxo_caixa_parcial_v', [
            'movimentacoes' => $movimentacoes,
            'resumo' => [
                'entradas' => $totalEntradas,
                'saidas'   => $totalSaidas,
                'saldo'    => $totalEntradas - $totalSaidas
            ]
        ])
    ]);
}


}