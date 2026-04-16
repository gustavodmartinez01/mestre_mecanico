<?php

namespace App\Models;

use CodeIgniter\Model;

class VeiculoModel extends Model
{
    protected $table            = 'veiculos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'cliente_id', 'empresa_id', 'proprietario', 'placa', 'marca', 'modelo', 
        'cor', 'ano', 'renavam', 'chassis', 'condicao_lataria', 'condicao_pintura', 
        'condicao_vidros', 'condicao_lanternas', 'condicao_estofamento', 
        'seguro_veicular', 'valor_fipe', 'observacoes', 'ativo'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id' => 'permit_empty|is_natural_no_zero',
        'cliente_id' => 'required|is_natural_no_zero',
        'empresa_id' => 'required|is_natural_no_zero',
        'placa'      => 'required|min_length[7]|max_length[10]',
        'marca'      => 'required',
        'modelo'     => 'required',
        'cor'        => 'required',
        'ano'        => 'required|numeric'
    ];
}