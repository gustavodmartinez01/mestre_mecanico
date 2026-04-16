<?php

namespace App\Models;

use CodeIgniter\Model;

class ContasReceberModel extends Model
{
    protected $table            = 'contas_receber';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // Campos permitidos para inclusão/edição
    protected $allowedFields    = [
        'empresa_id', 'cliente_id', 'os_id', 'descricao', 
        'valor_original', 'valor_pago', 'parcela_atual', 
        'total_parcelas', 'id_agrupador', 'desconto', 
        'juros_mora', 'multa_mora', 'atualizacao_monetaria', 
        'data_vencimento', 'data_pagamento', 'status', 
        'forma_pagamento', 'observacoes', 'completa'
    ];

    // Datas
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validação
    protected $validationRules = [
        'empresa_id'      => 'required|numeric',
        'cliente_id'      => 'required|numeric',
        'descricao'       => 'required|min_length[3]',
        'valor_original'  => 'required|decimal',
        'data_vencimento' => 'required|valid_date',
        'status'          => 'required|in_list[pendente,paga,vencida,cancelada]',
    ];

    /**
     * Busca contas vencidas e atualiza o status automaticamente
     */
    public function atualizarStatusVencidas()
    {
        $hoje = date('Y-m-d');
        return $this->db->table($this->table)
            ->set('status', 'vencida')
            ->where('status', 'pendente')
            ->where('data_vencimento <', $hoje)
            ->update();
    }

    // Dentro do seu ContaReceberModel.php
public function calcularValorAtualizado($id)
{
    $conta = $this->find($id);
    if ($conta['status'] == 'pendente' && strtotime($conta['data_vencimento']) < time()) {
        // Exemplo: Multa fixa de 2% + Juros de 1% ao mês (pro rata die)
        $vencimento = new \DateTime($conta['data_vencimento']);
        $hoje = new \DateTime();
        $intervalo = $vencimento->diff($hoje);
        $diasAtraso = $intervalo->days;

        $multa = $conta['valor_original'] * 0.02;
        $juros = ($conta['valor_original'] * 0.01 / 30) * $diasAtraso;
        
        return $conta['valor_original'] + $multa + $juros;
    }
    return $conta['valor_original'];
}
}