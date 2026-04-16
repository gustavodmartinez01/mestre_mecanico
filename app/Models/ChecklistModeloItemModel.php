<?php
namespace App\Models;
use CodeIgniter\Model;

class ChecklistModeloItemModel extends Model {
    protected $table = 'checklist_modelo_itens';
    protected $primaryKey = 'id';
    protected $allowedFields = ['checklist_modelo_id', 'descricao', 'obrigatorio', 'ordem'];
}