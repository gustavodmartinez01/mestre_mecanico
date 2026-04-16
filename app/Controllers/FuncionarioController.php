<?php

namespace App\Controllers;

use App\Models\FuncionarioModel;
use App\Models\UsuarioModel;

class FuncionarioController extends BaseController
{
    protected $funcModel;
    protected $userModel;
    protected $empresa_id;

    public function __construct()
    {
        $this->funcModel = new FuncionarioModel();
        $this->userModel = new UsuarioModel();
        $this->empresa_id = session()->get('empresa_id');
    }

    public function index()
    {
        $data['funcionarios'] = $this->funcModel
            ->where('empresa_id', $this->empresa_id)
            ->findAll();

        return view('funcionarios/listar_v', $data);
    }

    public function novo()
    {
        return view('funcionarios/form_v');
    }

    public function editar($id)
{
    // 1. Busca o funcionário primeiro
    $funcionario = $this->funcModel
        ->where(['id' => $id, 'empresa_id' => $this->empresa_id])
        ->first();

    // 2. Valida a existência antes de qualquer outra operação
    if (!$funcionario) {
        return redirect()->to('funcionarios')->with('error', 'Funcionário não encontrado.');
    }

    // 3. Inicializa o nível de acesso como padrão (evita erro na View)
    $funcionario['nivel_acesso'] = 'funcionario'; 

    // 4. Busca o usuário apenas se existir um usuario_id vinculado
    if (!empty($funcionario['usuario_id'])) {
        $usuario = $this->userModel->find($funcionario['usuario_id']);
        
        if ($usuario) {
            $funcionario['nivel_acesso'] = $usuario['nivel_acesso'];
            // Opcional: se precisar do e-mail do login na view
            $funcionario['email_login'] = $usuario['email'];
        }
    }

    $data = [
        'titulo'      => 'Alteração de Cadastro',
        'funcionario' => $funcionario
    ];

    return view('funcionarios/form_v', $data);
}

  public function salvar()
{
    $db = \Config\Database::connect();
    
    // Pega os dados do POST
    $idFunc = $this->request->getPost('id');
    $permitirAcesso = $this->request->getPost('permitir_acesso');
    
    // 1. Preparar dados do Usuário (se houver acesso)
    $novoUsuarioId = $this->request->getPost('usuario_id') ?: null;

    if ($permitirAcesso) {
        $dadosUser = [
            'nome'         => $this->request->getPost('nome'),
            'email'        => $this->request->getPost('email'),
            'nivel_acesso' => $this->request->getPost('nivel_acesso'),
            'status'       => 'ativo',
            'empresa_id'   => session()->get('empresa_id')
        ];

        $senha = $this->request->getPost('senha');
        if (!empty($senha)) {
         /////qq $dadosUser['senha'] = password_hash(trim($senha), PASSWORD_DEFAULT);
         $dadosUser['senha'] = $senha;

        }else if (!$novoUsuarioId) {
        // Se for novo e não mandou senha, define uma padrão (também limpa)
        $dadosUser['senha'] = '123456';
    }

       if ($novoUsuarioId) {
        $this->userModel->update($novoUsuarioId, $dadosUser);
    } else {
        $novoUsuarioId = $this->userModel->insert($dadosUser);
    }
    }

    // 2. Preparar dados do Funcionário
    $dadosFunc = [
        'id'               => $idFunc ?: null,
        'nome'             => $this->request->getPost('nome'),
        'email'            => $this->request->getPost('email'),
        'usuario_id'       => $novoUsuarioId,
        'matricula'        => $this->request->getPost('matricula'),
        'empresa_id'       => session()->get('empresa_id'),
        'cargo'            => $this->request->getPost('cargo'),
        'cpf'              => preg_replace('/\D/', '', $this->request->getPost('cpf')),
        'cep'              => $this->request->getPost('cep'),
        'logradouro'       => $this->request->getPost('logradouro'),
        'numero'           => $this->request->getPost('numero'),
        'complemento'      => $this->request->getPost('complemento'),
        'bairro'           => $this->request->getPost('bairro'),
        'cidade'           => $this->request->getPost('cidade'),
        'estado'           => $this->request->getPost('estado'),
        'rg'               => $this->request->getPost('rg'),
        'data_nascimento'  => $this->request->getPost('data_nascimento') ?: null,
        'telefone'         => $this->request->getPost('telefone'),
        'celular'          => $this->request->getPost('celular'),
        'comissao_servico' => $this->request->getPost('comissao_servico') ?: 0,
        'comissao_produto' => $this->request->getPost('comissao_produto') ?: 0,
        'data_admissao'    => $this->request->getPost('data_admissao') ?: null,
        'data_demissao'    => $this->request->getPost('data_demissao') ?: null,
        'status'           => $this->request->getPost('status'),
        'observacoes'      => $this->request->getPost('observacoes'),
        'ativo'            => 1
    ];

    // 3. Tentar Salvar com tratamento de erro
    $resultado = false;
    if ($idFunc) {
        $resultado = $this->funcModel->update($idFunc, $dadosFunc);
    } else {
        $resultado = $this->funcModel->insert($dadosFunc);
    }

    if (!$resultado) {
        // Se falhou, volta para o formulário mostrando exatamente qual campo errou
        return redirect()->back()->withInput()->with('errors', $this->funcModel->errors());
    }

    return redirect()->to(base_url('funcionarios'))->with('success', 'Gravado com sucesso!');
}

    public function excluir($id)
    {
        $funcionario = $this->funcModel->where(['id' => $id, 'empresa_id' => $this->empresa_id])->first();
        
        if ($funcionario) {
            $this->funcModel->delete($id);
            return redirect()->to('funcionarios')->with('success', 'Funcionário removido.');
        }

        return redirect()->to('funcionarios')->with('error', 'Erro ao excluir.');
    }
}