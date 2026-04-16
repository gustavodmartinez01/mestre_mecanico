<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table            = 'produtos';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'empresa_id', 'codigo_barras', 'nome', 'descricao', 'marca', 
        'preco_custo', 'preco_venda', 'estoque_minimo', 
        'estoque_atual', 'unidade_medida', 'ativo'
    ];

    protected $useTimestamps = true;

    /**
     * Atualiza o saldo do estoque somando Entradas e subtraindo Saídas
     */
    public function atualizarSaldo($produtoId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('estoque_movimentacao');
        
        // Soma Entradas
        $entradas = $builder->selectSum('quantidade')
                            ->where(['produto_id' => $produtoId, 'tipo' => 'E'])
                            ->get()->getRow()->quantidade ?? 0;
                            
        // Soma Saídas
        $saidas = $builder->selectSum('quantidade')
                          ->where(['produto_id' => $produtoId, 'tipo' => 'S'])
                          ->get()->getRow()->quantidade ?? 0;

        $saldo = $entradas - $saidas;

        return $this->update($produtoId, ['estoque_atual' => $saldo]);
    }
}