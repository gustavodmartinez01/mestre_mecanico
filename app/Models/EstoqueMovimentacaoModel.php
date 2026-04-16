<?php

namespace App\Models;

use CodeIgniter\Model;

class EstoqueMovimentacaoModel extends Model
{
    protected $table            = 'estoque_movimentacao';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'produto_id', 'empresa_id', 'tipo', 'quantidade', 
        'origem', 'observacao', 'data_movimento'
    ];
}