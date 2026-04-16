<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpresaModel extends Model
{
    protected $table            = 'empresas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true; // Segurança: nunca apaga de vez

    protected $allowedFields    = [
        'razao_social', 
        'nome_fantasia', 
        'cnpj', 
        'ie', 
        'email', 
        'telefone', 
        'cep', 
        'logradouro', 
        'numero', 
        'bairro', 
        'cidade', 
        'estado', 
        'logo'
    ];

    // Datas Automáticas
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Regras de Validação para um sistema profissional
    protected $validationRules = [
        'razao_social'  => 'required|min_length[5]|max_length[255]',
        'nome_fantasia' => 'required|min_length[3]|max_length[255]',
        'cnpj'          => 'required|is_unique[empresas.cnpj,id,{id}]',
        'email'         => 'permit_empty|valid_email',
    ];

    protected $validationMessages = [
        'cnpj' => [
            'is_unique' => 'Este CNPJ já está cadastrado em nossa base.'
        ],
        'email' => [
            'valid_email' => 'Por favor, informe um e-mail válido.'
        ]
    ];
}