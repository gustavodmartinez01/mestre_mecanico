<?php

namespace App\Controllers;

use App\Models\ContaReceberModel;
// Use o model da sua OS aqui
// use App\Models\OrdemServicoModel; 

class CaixaController extends BaseController
{
    /**
     * Exibe a tela de fechamento (o checkout da OS)
     */
    public function checkout($os_id)
    {
        // Aqui você buscaria os dados da OS e do Cliente para exibir na View
        // $data['os'] = $osModel->find($os_id);
        return view('financeiro/caixa_checkout', $data ?? []);
    }

    /**
     * Processa a liquidação da OS e gera o financeiro
     */
    public function processarPagamento()
    {
        $db = \Config\Database::connect();
        $contaModel = new ContaReceberModel();

        // Coleta de dados do formulário
        $osId          = $this->request->getPost('os_id');
        $clienteId     = $this->request->getPost('cliente_id');
        $valorFinal    = $this->request->getPost('valor_final'); // Valor após descontos/acréscimos
        $numParcelas   = (int)$this->request->getPost('num_parcelas');
        $formaPagto    = $this->request->getPost('forma_pagamento');
        $primeiroVenc  = $this->request->getPost('data_vencimento');

        $db->transStart();

        $idAgrupador = uniqid('MM_FIN_'); // Identificador único do lote de parcelas
        
        // Cálculo de parcelas com ajuste de centavos
        $valorBase     = floor(($valorFinal / $numParcelas) * 100) / 100;
        $diferenca     = number_format($valorFinal - ($valorBase * $numParcelas), 2, '.', '');

        for ($i = 1; $i <= $numParcelas; $i++) {
            // Se for a última parcela, soma a diferença dos centavos
            $valorParcela = ($i == $numParcelas) ? ($valorBase + $diferenca) : $valorBase;
            
            // Incremento mensal do vencimento
            $dataVencimento = date('Y-m-d', strtotime($primeiroVenc . " + " . ($i - 1) * 30 . " days"));

            $contaModel->save([
                'empresa_id'      => session()->get('empresa_id'),
                'cliente_id'      => $clienteId,
                'os_id'           => $osId,
                'descricao'       => "Acerto OS #{$osId} - Parcela {$i}/{$numParcelas}",
                'valor_original'  => $valorParcela,
                'parcela_atual'   => $i,
                'total_parcelas'  => $numParcelas,
                'id_agrupador'    => $idAgrupador,
                'data_vencimento' => $dataVencimento,
                'forma_pagamento' => $formaPagto,
                'status'          => 'pendente'
            ]);
        }

        // Atualiza o status da OS para impedir novo faturamento
        $db->table('ordens_servico')
           ->where('id', $osId)
           ->update([
               'status' => 'Finalizada', 
               'data_fechamento' => date('Y-m-d H:i:s')
           ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Falha crítica ao processar o caixa.');
        }

        // Se tudo deu certo, redireciona para a tela de impressão de documentos
        return redirect()->to("caixa/sucesso/{$idAgrupador}")
                         ->with('success', 'Pagamento registrado com sucesso!');
    }

    /**
 * Exibe o resumo do acerto e links para impressão
 */
public function sucesso($idAgrupador)
{
    $contaModel = new \App\Models\ContaReceberModel();
    
    // Busca todas as parcelas geradas neste acerto
    $parcelas = $contaModel->where('id_agrupador', $idAgrupador)
                           ->orderBy('parcela_atual', 'ASC')
                           ->findAll();

    if (empty($parcelas)) {
        return redirect()->to('dashboard')->with('error', 'Acerto não encontrado.');
    }

    // Pegamos a primeira parcela para extrair dados comuns (Cliente, OS)
    $dadosGerais = $parcelas[0];

    $data = [
        'title'       => 'Acerto Finalizado',
        'parcelas'    => $parcelas,
        'idAgrupador' => $idAgrupador,
        'cliente_id'  => $dadosGerais['cliente_id'],
        'os_id'       => $dadosGerais['os_id']
    ];

    return view('financeiro/caixa_sucesso_v', $data);
}
}