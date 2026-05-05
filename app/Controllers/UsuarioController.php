<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class UsuarioController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Altera apenas a senha do usuário logado
     */
    public function alterarSenha()
    {
        // 1. Coleta os dados do POST
        $id = session()->get('id');
        $novaSenha = $this->request->getPost('nova_senha');
        $confirmar = $this->request->getPost('confirmar_senha');

        // 2. Validações básicas
        if (empty($novaSenha) || strlen($novaSenha) < 6) {
            return redirect()->back()->with('error', 'A senha deve conter pelo menos 6 caracteres.');
        }

        if ($novaSenha !== $confirmar) {
            return redirect()->back()->with('error', 'A confirmação de senha não confere.');
        }

        // 3. Prepara o dado criptografado
        $dados = [
            'id'    => $id,
            'senha' => password_hash($novaSenha, PASSWORD_DEFAULT)
        ];

        // 4. Salva e retorna
        if ($this->usuarioModel->save($dados)) {
            return redirect()->back()->with('success', 'Sua senha foi atualizada com sucesso!');
        }

        return redirect()->back()->with('error', 'Ocorreu um erro ao tentar atualizar a senha.');
    }
}