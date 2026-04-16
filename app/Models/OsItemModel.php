<?php

namespace App\Models;

use CodeIgniter\Model;

class OsItemModel extends Model
{
    protected $table            = 'ordem_servico_itens';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'ordem_servico_id', 
        'tipo', 
        'item_id', 
        'descricao', 
        'quantidade', 
        'valor_unitario', 
        'custo_unitario', 
        'subtotal', 
        'margem'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}