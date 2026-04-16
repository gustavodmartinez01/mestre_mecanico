<?php

namespace App\Models;

use CodeIgniter\Model;

class FinanceiroCategoriaModel extends Model
{
    protected $table            = 'financeiro_categorias';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['empresa_id', 'nome', 'tipo', 'status'];

    // Habilita o controle de data de criação/edição se você adicionou essas colunas
    protected $useTimestamps = false; 

    /**
     * Busca categorias filtrando por tipo (receita ou despesa)
     */
    public function getPorTipo($empresa_id, $tipo = 'despesa')
    {
        return $this->where('empresa_id', $empresa_id)
                    ->where('tipo', $tipo)
                    ->where('status', 1)
                    ->orderBy('nome', 'ASC')
                    ->findAll();
    }
}