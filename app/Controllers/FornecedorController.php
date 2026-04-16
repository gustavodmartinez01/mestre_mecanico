<?php

namespace App\Controllers;

use App\Models\FornecedorModel;

class FornecedorController extends BaseController
{
    protected $fornecedorModel;

    public function __construct()
    {
        $this->fornecedorModel = new FornecedorModel();
    }

    public function index()
    {
        $data['fornecedores'] = $this->fornecedorModel
            ->where('empresa_id', session()->get('empresa_id'))
            ->findAll();

        return view('fornecedores/listar_v', $data);
    }

    public function novo()
    {
        return view('fornecedores/form_v', ['titulo' => 'Novo Fornecedor']);
    }

    public function editar($id)
    {
        $fornecedor = $this->fornecedorModel
            ->where('empresa_id', session()->get('empresa_id'))
            ->find($id);

        if (!$fornecedor) return redirect()->to('fornecedores')->with('error', 'Fornecedor não encontrado.');

        return view('fornecedores/form_v', [
            'titulo' => 'Editar Fornecedor',
            'forn' => $fornecedor
        ]);
    }

    public function salvar()
    {
        $dados = $this->request->getPost();
        $dados['empresa_id'] = session()->get('empresa_id');

        if ($this->fornecedorModel->save($dados)) {
            return redirect()->to('fornecedores')->with('success', 'Dados salvos com sucesso!');
        }

        return redirect()->back()->withInput()->with('error', 'Erro ao salvar fornecedor.');
    }

public function gerarPdf($id)
{
    $fornecedor = $this->fornecedorModel
                       ->where('empresa_id', session()->get('empresa_id'))
                       ->find($id);

    if (!$fornecedor) {
        return redirect()->back()->with('error', 'Fornecedor não encontrado.');
    }

    $dados['f'] = $fornecedor;
    $lib = new \App\Libraries\PdfLib();
    $html = view('fornecedores/pdf_v', $dados);

    // Limpeza de buffer para evitar erros de binário no navegador
    while (ob_get_level() > 0) ob_end_clean();

    header("Content-type: application/pdf");
    $lib->gerar($html, 'Ficha_Fornecedor_' . $id . '.pdf');
    exit;
}


}