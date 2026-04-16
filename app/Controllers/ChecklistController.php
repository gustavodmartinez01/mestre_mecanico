<?php

namespace App\Controllers;

use App\Models\ChecklistModel;

class ChecklistController extends BaseController
{
    protected $checklistModel;

    public function __construct()
    {
        $this->checklistModel = new ChecklistModel();
    }

    public function index()
    {
        $data = [
            'titulo'     => 'Configuração de Checklists',
            'checklists' => $this->checklistModel->getOrganizado(),
        ];

        return view('checklists/index_v', $data);
    }

    public function salvar()
    {
        $id = $this->request->getPost('id');
        
        $dados = [
            'descricao'      => $this->request->getPost('descricao'),
            'categoria'      => $this->request->getPost('categoria'),
            'ordem_exibicao' => $this->request->getPost('ordem_exibicao') ?? 0,
        ];

        if ($id) {
            $this->checklistModel->update($id, $dados);
            $mensagem = 'Item atualizado com sucesso!';
        } else {
            $this->checklistModel->insert($dados);
            $mensagem = 'Novo item de checklist adicionado!';
        }

        return redirect()->to('checklists')->with('success', $mensagem);
    }

    public function excluir($id)
    {
        $this->checklistModel->delete($id);
        return redirect()->to('checklists')->with('success', 'Item removido.');
    }


public function novo() 
{
    $data['titulo'] = 'Novo Item de Checklist';
    return view('checklists/form_v', $data);
}

public function editar($id) 
{
    $item = $this->checklistModel->find($id);
    
    if (!$item) {
        return redirect()->to('checklists')->with('error', 'Item não encontrado.');
    }

    $data = [
        'titulo' => 'Editar Item',
        'item'   => $item
    ];

    return view('checklists/form_v', $data);
}

}