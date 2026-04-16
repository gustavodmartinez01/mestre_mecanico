<?php

namespace App\Models;

use CodeIgniter\Model;

class FinanceiroModel extends Model
{
    protected $table            = 'financeiro';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'os_id', 
        'tipo', 
        'valor', 
        'forma_pagamento', 
        'status', 
        'data_vencimento', 
        'data_pagamento', 
        'observacao'
    ];
    
    // Habilita o uso de timestamps para sabermos quando o registro foi criado
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Não precisamos de updated_at aqui
}