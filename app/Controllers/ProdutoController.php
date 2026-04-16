<?php

namespace App\Controllers;

use App\Models\ProdutoModel;
use App\Models\EstoqueMovModel;

class ProdutoController extends BaseController
{
    protected $helpers = ['form', 'text'];

    public function index()
    {
        $model = new ProdutoModel();
        $data = [
            'titulo'   => 'Controle de Estoque',
            'produtos' => $model->where('empresa_id', session()->get('empresa_id'))
                                ->orderBy('nome', 'ASC')
                                ->findAll()
        ];

        return view('produtos/listar_v', $data);
    }



    public function novo()
    {
        return view('produtos/form_v', [
            'titulo' => 'Novo Produto'
        ]);
    }

    /**
     * Abre a tela para dar entrada ou saída manual
     */
    public function movimentar($id)
    {
        $model = new ProdutoModel();
        $produto = $model->where('empresa_id', session()->get('empresa_id'))->find($id);

        if (!$produto) return redirect()->back()->with('error', 'Produto não encontrado.');

        $movModel = new EstoqueMovModel();
        $historico = $movModel->where('produto_id', $id)->orderBy('data_movimento', 'DESC')->findAll();

        return view('produtos/movimentar_v', [
            'p' => $produto,
            'historico' => $historico
        ]);
    }

/**
 * Processa a Entrada ou Saída manual de estoque
 */
public function processar_movimentacao()
{
    $pId = $this->request->getPost('produto_id');
    $tipo = $this->request->getPost('tipo');
    $quantidade = $this->request->getPost('quantidade');
    
    $movModel = new \App\Models\EstoqueMovModel();
    $prodModel = new \App\Models\ProdutoModel();

    $data = [
        'produto_id'  => $pId,
        'empresa_id'  => session()->get('empresa_id'),
        'tipo'        => $tipo,
        'quantidade'  => $quantidade,
        'origem'      => 'Ajuste_Manual',
        'observacao'  => $this->request->getPost('observacao'),
        'data_movimento' => date('Y-m-d H:i:s')
    ];

    if ($movModel->insert($data)) {
        // Atualiza o saldo final na tabela de produtos
        $prodModel->atualizarSaldo($pId);
        
        return redirect()->to("produtos/movimentar/$pId")->with('success', 'Movimentação realizada com sucesso!');
    }

    return redirect()->back()->with('error', 'Erro ao processar movimentação.');
}
public function salvar()
{
    $model = new \App\Models\ProdutoModel();
    $dados = $this->request->getPost();
    
    // Limpeza de moeda
    $dados['preco_custo'] = str_replace(',', '.', str_replace('.', '', $dados['preco_custo']));
    $dados['preco_venda'] = str_replace(',', '.', str_replace('.', '', $dados['preco_venda']));
    $dados['empresa_id']  = session()->get('empresa_id');

    if ($model->save($dados)) {
        // Se for um novo produto e tiver estoque inicial, gera a primeira movimentação
        if (empty($dados['id']) && $dados['estoque_atual'] > 0) {
            $movModel = new \App\Models\EstoqueMovModel();
            $movModel->insert([
                'produto_id' => $model->getInsertID(),
                'empresa_id' => $dados['empresa_id'],
                'tipo'       => 'E',
                'quantidade' => $dados['estoque_atual'],
                'origem'     => 'Ajuste_Manual',
                'observacao' => 'Saldo inicial no cadastro'
            ]);
        }
        return redirect()->to('produtos')->with('success', 'Produto salvo com sucesso!');
    }
    return redirect()->back()->withInput()->with('error', 'Erro ao salvar.');
}
public function gerarPdf()
{
    $empresaId = session()->get('empresa_id');
    
    // Dados da oficina para o cabeçalho
    $empresaModel = new \App\Models\EmpresaModel();
    $oficina = $empresaModel->find($empresaId);

    // USAR O MODEL DE PRODUTOS CORRETAMENTE
    $model = new \App\Models\ProdutoModel();
    
    $produtos = $model->where('empresa_id', $empresaId)
                      ->where('ativo', 1)
                      ->orderBy('nome', 'ASC')
                      ->findAll();

    $data = [
        'oficina'  => $oficina,
        'produtos' => $produtos
    ];

    $lib = new \App\Libraries\PdfLib();
    $html = view('produtos/pdf_v', $data);

    // Limpa o buffer para evitar que lixo de memória corrompa o PDF
    while (ob_get_level() > 0) ob_end_clean();

    $lib->gerar($html, 'Inventario_Estoque.pdf');
    exit;
}

}