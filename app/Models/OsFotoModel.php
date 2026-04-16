<?php

namespace App\Models;

use CodeIgniter\Model;

class OsFotoModel extends Model
{
    protected $table            = 'ordem_servico_fotos';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'ordem_servico_id', 
        'checklist_item_id', 
        'tipo', 
        'caminho_arquivo', 
        'descricao', 
        'tamanho_kb', 
        'criado_por'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
}