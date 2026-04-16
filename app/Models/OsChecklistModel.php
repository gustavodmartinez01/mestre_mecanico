<?php
namespace App\Models;
use CodeIgniter\Model;

class OsChecklistModel extends Model {
    protected $table = 'os_checklists';
    protected $primaryKey = 'id';
    
    // Habilita o controle automático de data/hora
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'ordem_servico_id', 
        'descricao', 
        'observacao',
        'status', 
        'status_anterior',
        'tipo'
    ];
}