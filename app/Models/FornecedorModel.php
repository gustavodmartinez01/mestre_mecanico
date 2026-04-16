<?php

namespace App\Models;

use CodeIgniter\Model;

class FornecedorModel extends Model
{
    protected $table            = 'fornecedores';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'empresa_id', 'tipo_pessoa', 'nome_razao', 'nome_fantasia', 'documento',
        'ie_rg', 'email', 'telefone', 'celular', 'cep', 'logradouro', 
        'numero', 'complemento', 'bairro', 'cidade', 'estado', 
        'categoria', 'especialidade', 'observacoes', 'ativo'
    ];

    protected $useTimestamps = true;

   protected $validationRules = [
        'id'         => 'permit_empty|is_natural_no_zero',
        'nome_razao' => 'required|min_length[3]|max_length[255]',
        'documento'  => 'required|is_unique[fornecedores.documento,id,{id}]',
        'empresa_id' => 'required|is_natural_no_zero',
        'email'      => 'permit_empty|valid_email',
        'tipo_pessoa'=> 'required|in_list[F,J]'
    ];

    protected $validationMessages = [
        'nome_razao' => [
            'required'   => 'O Nome ou Razão Social é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.'
        ],
        'documento' => [
            'required'  => 'O CPF/CNPJ é obrigatório.',
            'is_unique' => 'Este documento já está cadastrado para outro fornecedor.'
        ],
        'empresa_id' => [
            'required' => 'Erro sistêmico: ID da empresa não identificado.'
        ]
    ];
}