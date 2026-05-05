<?php

namespace App\Controllers;

use App\Models\VeiculoModel;
use App\Models\OrdemServicoModel; // Memorizado: OrdemServicoModel
use CodeIgniter\Controller;

class VeiculoController extends BaseController
{
    protected $veiculoModel;
    protected $osModel;
    protected $session;

    public function __construct()
    {
        $this->veiculoModel = new VeiculoModel();
        $this->osModel = new OrdemServicoModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Listagem principal da frota de veículos
     */
    public function index()
    {
        $empresaId = $this->session->get('empresa_id');

        // Busca veículos trazendo o nome do cliente atual via JOIN
        $veiculos = $this->veiculoModel
            ->select('veiculos.*, clientes.nome_razao as nome_cliente')
            ->join('clientes', 'clientes.id = veiculos.cliente_id')
            ->where('veiculos.empresa_id', $empresaId)
            ->orderBy('veiculos.created_at', 'DESC')
            ->findAll();

        $data = [
            'title'    => 'Frota de Veículos',
            'veiculos' => $veiculos
        ];

        return view('veiculos/veiculos_v', $data);
    }

    /**
     * Detalhes e Prontuário (Histórico de OS) do Veículo
     */
    public function detalhes($id)
    {
        $empresaId = $this->session->get('empresa_id');

        $veiculo = $this->veiculoModel
            ->where(['id' => $id, 'empresa_id' => $empresaId])
            ->first();

        if (!$veiculo) {
            return redirect()->to('/veiculos')->with('error', 'Veículo não encontrado.');
        }

        // Histórico de passagens pela oficina (Ordens de Serviço)
        // Mesmo que mude o dono, o histórico do veículo permanece
        $historico = $this->osModel
            ->where('veiculo_id', $id)
            ->orderBy('data_abertura', 'DESC')
            ->findAll();

        $data = [
            'title'     => 'Histórico do Veículo: ' . $veiculo['placa'],
            'veiculo'   => $veiculo,
            'historico' => $historico
        ];

        return view('veiculos/veiculo_detalhes_v', $data);
    }

    /**
     * Salvar/Atualizar veículo (Formulário Completo)
     */
    public function salvar()
    {
        $dados = $this->request->getPost();
        $dados['empresa_id'] = $this->session->get('empresa_id');
        
        // Limpeza de placa (remover máscaras se houver)
        if (isset($dados['placa'])) {
            $dados['placa'] = strtoupper(trim(str_replace('-', '', $dados['placa'])));
        }

        if ($this->veiculoModel->save($dados)) {
            return redirect()->back()->with('success', 'Dados do veículo salvos com sucesso!');
        }

        return redirect()->back()->withInput()->with('error', 'Falha ao salvar veículo.');
    }

    /**
     * Cadastro Rápido (usado via AJAX na abertura de OS)
     */
    public function salvarRapido()
    {
        $empresaId = $this->session->get('empresa_id');
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

        try {
            if ($veiculoExistente) {
                $this->veiculoModel->update($veiculoExistente['id'], $data);
                return $this->response->setJSON(['status' => 'success', 'msg' => 'Vínculo do veículo atualizado!', 'id' => $veiculoExistente['id']]);
            } else {
                $idInserido = $this->veiculoModel->insert($data);
                if ($idInserido) {
                    return $this->response->setJSON(['status' => 'success', 'msg' => 'Novo veículo cadastrado!', 'id' => $idInserido]);
                }
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Erro técnico: ' . $e->getMessage()]);
        }

        return $this->response->setJSON(['status' => 'error', 'msg' => 'Erro ao processar veículo.']);
    }

    /**
     * API para buscar veículos de um cliente específico
     */
    public function buscarPorCliente($clienteId)
    {
        $veiculos = $this->veiculoModel
            ->where([
                'cliente_id' => $clienteId, 
                'empresa_id' => $this->session->get('empresa_id'),
                'ativo'      => 1
            ])->findAll();

        return $this->response->setJSON($veiculos);
    }

    /**
     * Exclusão lógica/física
     */
    public function excluir($id)
    {
        $empresaId = $this->session->get('empresa_id');
        
        // Verifica se o veículo pertence à empresa antes de deletar
        $veiculo = $this->veiculoModel->where(['id' => $id, 'empresa_id' => $empresaId])->first();
        
        if ($veiculo) {
            $this->veiculoModel->delete($id);
            return redirect()->to('/veiculos')->with('success', 'Veículo removido da frota.');
        }

        return redirect()->back()->with('error', 'Não autorizado ou veículo inexistente.');
    }
/**
 * Abre o formulário de edição com os dados do veículo e lista de clientes
 */
public function editar($id)
{
    $empresaId = session()->get('empresa_id');

    // 1. Busca o veículo garantindo que pertence à empresa logada
    $veiculo = $this->veiculoModel
        ->where(['id' => $id, 'empresa_id' => $empresaId])
        ->first();

    if (!$veiculo) {
        return redirect()->to('/veiculos')->with('error', 'Veículo não encontrado ou acesso negado.');
    }

    // 2. Busca todos os clientes para o dropdown (select) do formulário
    // Usamos o model de Clientes para isso
    $clienteModel = new \App\Models\ClienteModel();
    $clientes = $clienteModel
        ->where('empresa_id', $empresaId)
        ->orderBy('nome_razao', 'ASC')
        ->findAll();

    // 3. Monta o array de dados para a view
    $data = [
        'title'    => 'Editar Veículo - ' . $veiculo['placa'],
        'veiculo'  => $veiculo,
        'clientes' => $clientes
    ];

    return view('veiculos/veiculos_form_v', $data);
}

/**
 * Método opcional para criar um novo veículo usando a mesma view de formulário
 */
public function novo()
{
    $empresaId = session()->get('empresa_id');
    
    $clienteModel = new \App\Models\ClienteModel();
    $clientes = $clienteModel
        ->where('empresa_id', $empresaId)
        ->orderBy('nome_razao', 'ASC')
        ->findAll();

    $data = [
        'title'    => 'Novo Veículo',
        'clientes' => $clientes
        // Sem a variável $veiculo, o formulário saberá que é um novo cadastro
    ];

    return view('veiculos/veiculos_form_v', $data);
}


}