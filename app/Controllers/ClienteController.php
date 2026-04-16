<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\VeiculoModel; // Já preparando para o próximo passo

class ClienteController extends BaseController
{
    protected $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
    }

    public function index()
    {
        $data['clientes'] = $this->clienteModel
            ->where('empresa_id', session()->get('empresa_id'))
            ->orderBy('nome_razao', 'ASC')
            ->findAll();

        return view('clientes/listar_v', $data);
    }

    public function novo()
    {
        return view('clientes/form_v', ['titulo' => 'Novo Cliente']);
    }

    public function editar($id)
    {
        $cliente = $this->clienteModel
            ->where('empresa_id', session()->get('empresa_id'))
            ->find($id);

        if (!$cliente) {
            return redirect()->to('clientes')->with('error', 'Cliente não encontrado.');
        }

        return view('clientes/form_v', [
            'titulo' => 'Editar Cliente',
            'c' => $cliente
        ]);
    }

    public function salvar()
    {
        $dados = $this->request->getPost();
        
        // Limpeza de máscaras para salvar apenas números no banco
        $dados['documento'] = preg_replace('/\D/', '', $dados['documento']);
        $dados['cep']       = preg_replace('/\D/', '', $dados['cep']);
        $dados['telefone']  = preg_replace('/\D/', '', $dados['telefone'] ?? '');
        $dados['celular']   = preg_replace('/\D/', '', $dados['celular'] ?? '');
        
        $dados['empresa_id'] = session()->get('empresa_id');

        // O cálculo do score e classificação ocorre automaticamente no Model (beforeInsert/Update)
        if ($this->clienteModel->save($dados)) {
            return redirect()->to('clientes')->with('success', 'Cliente salvo com sucesso!');
        }

        return redirect()->back()->withInput()->with('errors', $this->clienteModel->errors());
    }

    public function detalhes($id)
    {
        $cliente = $this->clienteModel
            ->where('empresa_id', session()->get('empresa_id'))
            ->find($id);

        if (!$cliente) return redirect()->to('clientes');

        $veiculoModel = new VeiculoModel();
        
        $data = [
            'titulo'   => 'Ficha do Cliente: ' . $cliente['nome_razao'],
            'c'        => $cliente,
            'veiculos' => $veiculoModel->where('cliente_id', $id)->findAll()
        ];

        return view('clientes/detalhes_v', $data);
    }

    public function excluir($id)
    {
        // Verificar se pertence à empresa e deletar
        $this->clienteModel->where('empresa_id', session()->get('empresa_id'))->delete($id);
        return redirect()->to('clientes')->with('success', 'Cliente removido.');
    }

public function gerarPdf($id)
{
    $empresaId = session()->get('empresa_id');
    
    $cliente = $this->clienteModel
                    ->where('empresa_id', $empresaId)
                    ->find($id);

    if (!$cliente) return redirect()->back();

    // Buscamos os dados da empresa/oficina
    $empresaModel = new \App\Models\EmpresaModel();
    $oficina = $empresaModel->find($empresaId);

    $veiculoModel = new \App\Models\VeiculoModel();
    
    $data = [
        'c'       => $cliente,
        'veiculos'=> $veiculoModel->where('cliente_id', $id)->findAll(),
        'oficina' => $oficina // Dados da oficina aqui
    ];

    $lib = new \App\Libraries\PdfLib();
    $html = view('clientes/pdf_v', $data);

    while (ob_get_level() > 0) ob_end_clean();
    $lib->gerar($html, 'Ficha_Cliente_' . $id . '.pdf');
    exit;
}

}