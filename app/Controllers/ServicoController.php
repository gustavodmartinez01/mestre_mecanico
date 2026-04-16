<?php

namespace App\Controllers;

use App\Models\ServicoModel;

class ServicoController extends BaseController
{
    protected $servicoModel;
    protected $helpers = ['text', 'form'];
    public function __construct()
    {
        $this->servicoModel = new ServicoModel();
    }

    /**
     * Lista todos os serviços da empresa
     */
    public function index()
    {
        $data = [
            'titulo'   => 'Catálogo de Serviços',
            'servicos' => $this->servicoModel
                            ->where('empresa_id', session()->get('empresa_id'))
                            ->orderBy('nome', 'ASC')
                            ->findAll()
        ];

        return view('servicos/listar_v', $data);
    }

    /**
     * Abre o formulário para novo serviço
     */
    public function novo()
    {
        return view('servicos/form_v', [
            'titulo' => 'Novo Serviço'
        ]);
    }

    /**
     * Abre o formulário para edição
     */
    public function editar($id)
    {
        $servico = $this->servicoModel
                        ->where('empresa_id', session()->get('empresa_id'))
                        ->find($id);

        if (!$servico) {
            return redirect()->to('servicos')->with('error', 'Serviço não encontrado.');
        }

        return view('servicos/form_v', [
            'titulo' => 'Editar Serviço',
            's'      => $servico
        ]);
    }

    /**
     * Processa o Insert e o Update
     */
    public function salvar()
    {
        $dados = $this->request->getPost();
        
        // Formata os preços: remove pontos de milhar e troca vírgula por ponto
        $dados['preco_custo'] = $this->limparMoeda($dados['preco_custo']);
        $dados['preco_venda'] = $this->limparMoeda($dados['preco_venda']);
        
        // Garante que o serviço pertence à empresa atual
        $dados['empresa_id'] = session()->get('empresa_id');

        // Validação simples
        if (empty($dados['nome'])) {
            return redirect()->back()->withInput()->with('error', 'O nome do serviço é obrigatório.');
        }

        if ($this->servicoModel->save($dados)) {
            return redirect()->to('servicos')->with('success', 'Serviço salvo com sucesso!');
        }

        return redirect()->back()->withInput()->with('error', 'Ocorreu um erro ao salvar o serviço.');
    }

    /**
     * Remove um serviço (ou desativa)
     */
    public function excluir($id)
    {
        // Verifica se o serviço pertence à empresa antes de deletar
        $servico = $this->servicoModel
                        ->where('empresa_id', session()->get('empresa_id'))
                        ->find($id);

        if ($servico) {
            $this->servicoModel->delete($id);
            return redirect()->to('servicos')->with('success', 'Serviço removido com sucesso.');
        }

        return redirect()->to('servicos')->with('error', 'Erro ao excluir: Serviço não encontrado.');
    }

    /**
     * Função auxiliar para tratar campos de moeda (BRL -> USD)
     */
    private function limparMoeda($valor)
    {
        if (empty($valor)) return 0.00;
        $valor = str_replace('.', '', $valor); // Remove ponto de milhar
        $valor = str_replace(',', '.', $valor); // Troca vírgula por ponto decimal
        return (float) $valor;
    }

    public function gerarPdf()
{
    $empresaId = session()->get('empresa_id');
    
    // Busca dados da oficina
    $empresaModel = new \App\Models\EmpresaModel();
    $oficina = $empresaModel->find($empresaId);

    // Busca todos os serviços da empresa
    $servicos = $this->servicoModel
                     ->where('empresa_id', $empresaId)
                     ->where('ativo', 1)
                     ->orderBy('nome', 'ASC')
                     ->findAll();

    $data = [
        'oficina'  => $oficina,
        'servicos' => $servicos
    ];

    $lib = new \App\Libraries\PdfLib();
    $html = view('servicos/pdf_v', $data);

    // Limpa o buffer para evitar erros de saída de dados
    while (ob_get_level() > 0) ob_end_clean();

    $lib->gerar($html, 'Catalogo_Servicos.pdf');
    exit;
}
}