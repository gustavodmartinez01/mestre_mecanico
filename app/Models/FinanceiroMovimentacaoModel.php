<?php

namespace App\Models;

use CodeIgniter\Model;

class FinanceiroMovimentacaoModel extends Model
{
    protected $table            = 'financeiro_movimentacao';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'empresa_id', 
        'tipo', 
        'categoria_id', 
        'conta_bancaria_id', 
        'descricao', 
        'valor', 
        'data_movimentacao', 
        'origem_tabela', 
        'origem_id', 
        'observacoes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Registra uma nova movimentação no financeiro
     * Útil para ser chamado de dentro dos outros controllers
     */
    public function registrar($dados)
    {
        // Garante que o valor seja sempre positivo para salvar (o 'tipo' define se subtrai)
        if (isset($dados['valor'])) {
            $dados['valor'] = abs($dados['valor']);
        }
        
        return $this->insert($dados);
    }

    /**
     * Remove uma movimentação vinculada a uma conta (Estorno)
     */
    public function estornar($tabela, $id_origem)
    {
        return $this->where('origem_tabela', $tabela)
                    ->where('origem_id', $id_origem)
                    ->delete();
    }

    /**
     * Busca o resumo de entradas e saídas para o Dashboard
     */
    public function getResumoMensal($empresa_id, $mes, $ano)
    {
        return $this->select("
                SUM(CASE WHEN tipo = 'entrada' THEN valor ELSE 0 END) as entradas,
                SUM(CASE WHEN tipo = 'saida' THEN valor ELSE 0 END) as saidas
            ")
            ->where('empresa_id', $empresa_id)
            ->where('MONTH(data_movimentacao)', $mes)
            ->where('YEAR(data_movimentacao)', $ano)
            ->first();
    }
}