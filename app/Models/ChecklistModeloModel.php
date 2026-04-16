<?php
namespace App\Models;
use CodeIgniter\Model;

class ChecklistModeloModel extends Model {
    protected $table = 'checklist_modelos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['empresa_id', 'nome', 'descricao', 'ativo'];
}