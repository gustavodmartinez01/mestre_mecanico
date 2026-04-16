<?php

namespace App\Controllers;

use App\Models\VeiculoModel;

class VeiculoController extends BaseController
{
    protected $veiculoModel;

    public function __construct()
    {
        // Isso garante que $this->veiculoModel funcione em toda a classe
        $this->veiculoModel = new VeiculoModel();
    }

    public function excluir($id)
    {
        $this->veiculoModel->where('empresa_id', session()->get('empresa_id'))->delete($id);
        return redirect()->back()->with('success', 'Veículo removido.');
    }

    public function salvar()
    {
        $dados = $this->request->getPost();
        $dados['empresa_id'] = session()->get('empresa_id');

        if ($this->veiculoModel->save($dados)) {
            return redirect()->back()->with('success', 'Veículo atualizado com sucesso!');
        }

        return redirect()->back()->withInput()->with('error', 'Erro ao salvar veículo.');
    }

    public function buscarPorCliente($clienteId)
    {
        // Agora $this->veiculoModel existe por causa do __construct
        $veiculos = $this->veiculoModel
            ->where([
                'cliente_id' => $clienteId, 
                'empresa_id' => session()->get('empresa_id'),
                'ativo'      => 1
            ])->findAll();

        return $this->response->setJSON($veiculos);
    }

    public function salvarRapido()
    {
        $empresaId = session()->get('empresa_id');
        $placa = strtoupper(trim(str_replace('-', '', $this->request->getPost('placa'))));

        $veiculoExistente = $this->veiculoModel
            ->where(['placa' => $placa, 'empresa_id' => $empresaId])
            ->first();

        $data = [
            'cliente_id'   => $this->request->getPost('cliente_id'),
            'empresa_id'   => $empresaId,
            'proprietario' => $this->request->getPost('proprietario'),
            'placa'        => $placa,
            'marca'        => $this->request->getPost('marca'),
            'modelo'       => $this->request->getPost('modelo'),
            'cor'          => $this->request->getPost('cor'),
            'ano'          => $this->request->getPost('ano'),
            'ativo'        => 1
        ];

        if ($veiculoExistente) {
            $this->veiculoModel->update($veiculoExistente['id'], $data);
            return $this->response->setJSON(['status' => 'success', 'msg' => 'Veículo atualizado com sucesso!']);
        } else {
            if ($this->veiculoModel->insert($data)) {
                return $this->response->setJSON(['status' => 'success', 'msg' => 'Novo veículo cadastrado!']);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'msg' => 'Erro ao processar veículo.']);
    }
}