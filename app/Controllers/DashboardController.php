<?php

namespace App\Controllers;

use App\Models\OrdemServicoModel;

class DashboardController extends BaseController
{
    public function index()
{
    $osModel = new \App\Models\OrdemServicoModel();
    $db = \Config\Database::connect();
    $empresa_id = session()->get('empresa_id');

    // 1. Quantidade de OS abertas
    $data['qtd_abertas'] = $osModel->where(['empresa_id' => $empresa_id, 'status' => 'aberta'])->countAllResults();

    // 2. Total a Receber (Soma de orçamentos/OS abertas)
    $data['total_aberto'] = $osModel->where(['empresa_id' => $empresa_id, 'status' => 'aberta'])
                                    ->selectSum('valor_total')->first()['valor_total'] ?? 0;

    // 3. Faturamento Mês Atual (Soma de OS finalizadas no mês)
    $data['faturamento_mes'] = $osModel->where(['empresa_id' => $empresa_id, 'status' => 'finalizada'])
                                       ->where('data_abertura >=', date('Y-m-01'))
                                       ->selectSum('valor_total')->first()['valor_total'] ?? 0;

    // 4. Entradas de Hoje
    $data['qtd_hoje'] = $osModel->where(['empresa_id' => $empresa_id])
                                ->where('DATE(data_abertura)', date('Y-m-d'))->countAllResults();

    // 5. Últimas Movimentações
    $data['ultimas_os'] = $osModel->getListaCompleta(10); 

    // --- NOVIDADE: CARGA DE TRABALHO DA EQUIPE ---
    // Busca a contagem de OS "abertas" agrupadas por funcionário
    $data['resumo_equipe'] = $db->table('ordem_servicos as os')
    ->select('f.nome as funcionario, COUNT(os.id) as total')
    ->join('funcionarios as f', 'f.id = os.tecnico_id') 
    ->where('os.empresa_id', $empresa_id)
    ->where('os.status', 'aberta')
    ->groupBy('f.id')
    ->orderBy('total', 'ASC') // Mostra quem tem menos serviço primeiro
    ->get()
    ->getResultArray();

    return view('dashboard/index_v', $data);
}
}