<?php

namespace App\Models;

use CodeIgniter\Model;

class ChecklistModel extends Model
{
    protected $table      = 'checklists';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // Campos que permitimos salvar
    protected $allowedFields = ['descricao', 'categoria', 'ordem_exibicao'];

    // Ordenação padrão por categoria e depois pela ordem definida
    public function getOrganizado()
    {
        return $this->orderBy('categoria', 'ASC')
                    ->orderBy('ordem_exibicao', 'ASC')
                    ->findAll();
    }
}