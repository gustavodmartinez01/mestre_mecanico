<?php

namespace App\Models;

use CodeIgniter\Model;

class ComprasItemModel extends Model
{
    protected $table            = 'compras_requisicoes_itens';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'requisicao_id',
        'produto_id', 
        'descricao_item', 
        'quantidade', 
        'valor_unitario',
        'situacao',
        'subtotal'
    ];
  

    /**
     * Busca itens de uma requisição específica
     */
    public function getItens($requisicao_id)
    {
        return $this->where('requisicao_id', $requisicao_id)->findAll();
    }
}