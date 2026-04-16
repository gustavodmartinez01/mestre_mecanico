<?php

namespace App\Models;

use CodeIgniter\Model;

class OsChecklistItemModel extends Model
{
    protected $table            = 'ordem_servico_checklist_itens';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'os_checklist_id', 
        'descricao', 
        'status', 
        'observacao', 
        'obrigatorio', 
        'executado_por', 
        'executado_at'
    ];

    protected $useTimestamps = false; // Usamos executado_at manualmente
}