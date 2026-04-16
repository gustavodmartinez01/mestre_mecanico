<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicoModel extends Model
{
    protected $table            = 'servicos';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'empresa_id', 'nome', 'descricao', 'preco_custo', 
        'preco_venda', 'tempo_dias', 'tempo_horas', 'tempo_minutos', 'ativo'
    ];

    protected $useTimestamps = true;

    // Retorna o tempo formatado para humanos
    public function getTempoFormatado($s)
    {
        $tempo = "";
        if ($s['tempo_dias'] > 0) $tempo .= $s['tempo_dias'] . "d ";
        if ($s['tempo_horas'] > 0 || $s['tempo_dias'] > 0) $tempo .= str_pad($s['tempo_horas'], 2, '0', STR_PAD_LEFT) . "h:";
        $tempo .= str_pad($s['tempo_minutos'], 2, '0', STR_PAD_LEFT) . "m";
        
        return $tempo;
    }
}