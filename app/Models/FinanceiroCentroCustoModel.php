<?php

namespace App\Models;

use CodeIgniter\Model;

class FinanceiroCentroCustoModel extends Model
{
    protected $table            = 'financeiro_centros_custo';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['empresa_id', 'nome', 'status'];

    protected $useTimestamps = false;

    /**
     * Busca centros de custo ativos
     */
    public function getAtivos($empresa_id)
    {
        return $this->where('empresa_id', $empresa_id)
                    ->where('status', 1)
                    ->orderBy('nome', 'ASC')
                    ->findAll();
    }
}