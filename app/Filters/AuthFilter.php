<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Se NÃO estiver logado, redireciona para o login com mensagem de erro
        if (!session()->get('logado')) {
            return redirect()
                ->to(base_url('login'))
                ->with('error', 'Por favor, faça login para acessar o sistema.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Não precisamos fazer nada após a execução
    }
}