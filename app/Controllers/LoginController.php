<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\FuncionarioModel;
// Certifique-se de criar o EmpresaModel.php se ainda não criou
use App\Models\EmpresaModel; 

class LoginController extends BaseController
{
    /**
     * Exibe a tela de login
     */
    public function index()
    {
        // Se já estiver logado, manda direto para o dashboard
        if (session()->get('logado')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('login_v');
    }

    /**
     * Processa a autenticação
     */
    public function autenticar()
    {
        $session = session();
        $usuarioModel = new UsuarioModel();
        $funcionarioModel = new FuncionarioModel();
        $empresaModel = new \App\Models\EmpresaModel(); // Instância direta caso não queira o use no topo

        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        // 1. Busca o usuário pelo e-mail
        $usuario = $usuarioModel->where('email', $email)
                                ->where('status', 'ativo')
                                ->first();

        if ($usuario) {
            // 2. Verifica se a senha confere
            if (password_verify($senha, $usuario['senha'])) {
                
                // 3. Busca os dados do Funcionário (para pegar o Nome)
                $funcionario = $funcionarioModel->where('usuario_id', $usuario['id'])->first();
                
                // 4. Busca os dados da Empresa (para o Topo e Rodapé)
                $empresa = $empresaModel->find($usuario['empresa_id']);

              // 5. Monta a sessão com tudo o que precisamos
        $dadosSessao = [
            'id'           => $usuario['id'], // ID padrão para facilitar buscas
            'usuario_id'   => $usuario['id'],
            'nome'         => $funcionario ? $funcionario['nome'] : 'Usuário Sem Nome',
            'nivel_acesso' => $usuario['nivel_acesso'],
            'cargo'        => $funcionario ? $funcionario['cargo'] : 'Não Definido', 
            'empresa_id'   => $usuario['empresa_id'],
            'empresa_nome' => $empresa['nome_fantasia'] ?? 'Oficina',
            'empresa_cnpj' => $empresa['cnpj'] ?? '',
            'logado'       => true,
];

$session->set($dadosSessao);

                // Sucesso! Vai para o Dashboard
                return redirect()->to(base_url('dashboard'));
            }
        }

        // Se chegou aqui, algo deu errado
        return redirect()->back()->with('error', 'E-mail '. $email.' ou senha incorretos ou usuário inativo.');
    }

    /**
     * Encerra a sessão
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}