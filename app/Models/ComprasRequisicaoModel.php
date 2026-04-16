<?php

namespace App\Models;

use CodeIgniter\Model;

class ComprasRequisicaoModel extends Model
{
    protected $table            = 'compras_requisicoes';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'empresa_id', 'fornecedor_id', 'usuario_id', 
        'data_requisicao', 'status', 'valor_total', 'observacoes'
    ];

    protected $useTimestamps = true;

    /**
     * Retorna a requisição com o nome do fornecedor e do usuário
     */
    public function getDetalhada($id = null)
    {
        $builder = $this->select('compras_requisicoes.*, fornecedores.nome_fantasia as fornecedor_nome, usuarios.nome as usuario_nome')
                        ->join('fornecedores', 'fornecedores.id = compras_requisicoes.fornecedor_id')
                        ->join('usuarios', 'usuarios.id = compras_requisicoes.usuario_id');
        
        if ($id) {
            return $builder->where('compras_requisicoes.id', $id)->first();
        }

        return $builder->findAll();
    }
}