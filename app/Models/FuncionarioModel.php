<?php

namespace App\Models;

use CodeIgniter\Model;

class FuncionarioModel extends Model
{
    protected $table            = 'funcionarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    // Todos os campos da sua estrutura de tabela
    protected $allowedFields = [
        'nome', 'email', 'usuario_id', 'matricula', 'empresa_id', 
        'cargo', 'cpf', 'cep', 'logradouro', 'numero', 'complemento', 
        'bairro', 'cidade', 'estado', 'rg', 'data_nascimento', 
        'telefone', 'celular', 'comissao_servico', 'comissao_produto', 
        'data_admissao', 'data_demissao', 'status', 'observacoes', 'ativo'
    ];

    // Datas automáticas
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Regras de Validação Profissional
    // Nota: O placeholder {id} permite que a regra 'is_unique' ignore o próprio registro na edição
    protected $validationRules = [
        'id'                => 'permit_empty|is_natural_no_zero',
        'nome'              => 'required|min_length[3]|max_length[100]',
        'cpf'               => 'required|is_unique[funcionarios.cpf,id,{id}]',
        'matricula'         => 'permit_empty|is_unique[funcionarios.matricula,id,{id}]',
        'cargo'             => 'required',
        'empresa_id'        => 'required|is_natural_no_zero',
        'comissao_servico'  => 'permit_empty|decimal',
        'comissao_produto'  => 'permit_empty|decimal',
        'email'             => 'permit_empty|valid_email',
        'data_admissao'     =>'permit_empty|date'
    ];

    protected $validationMessages = [
        'cpf' => [
            'required'  => 'O CPF é obrigatório para o registro do funcionário.',
            'is_unique' => 'Desculpe, este CPF já está cadastrado no sistema.'
        ],
        'nome' => [
            'required'   => 'O nome do funcionário é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.'
        ],
        'matricula' => [
            'is_unique' => 'Esta matrícula já está em uso por outro colaborador.'
        ],
        'email' => [
            'valid_email' => 'Por favor, informe um endereço de e-mail válido.'
        ]
    ];

    protected $skipValidation = false;
}