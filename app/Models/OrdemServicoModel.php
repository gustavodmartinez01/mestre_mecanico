<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\OsItemModel;
use App\Models\OsFotoModel;
use App\Models\OsChecklistItemModel;

class OrdemServicoModel extends Model
{
    protected $table            = 'ordem_servicos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Pode alterar para true se configurar a coluna deleted_at

    protected $allowedFields    = [
        'empresa_id', 
        'numero_os', 
        'cliente_id', 
        'veiculo_id', 
        'status', 
        'data_abertura', 
        'data_aprovacao', 
        'data_fechamento', 
        'km_entrada', 
        'descricao_problema', 
        'diagnostico', 
        'observacoes', 
        'valor_servicos', 
        'valor_produtos', 
        'desconto', 
        'acrescimo', 
        'valor_total', 
        'criado_por',
        'tecnico_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at'; // Certifique-se que esta coluna existe no seu SQL
    protected $updatedField  = 'updated_at';

    /**
     * Busca a listagem de OS com dados básicos para o grid/tabela
     */
    public function getListagemCompleta($empresaId)
    {
        return $this->select('ordem_servicos.*, clientes.nome_razao as cliente_nome, veiculos.placa as veiculo_placa, veiculos.modelo as veiculo_modelo')
                    ->join('clientes', 'clientes.id = ordem_servicos.cliente_id')
                    ->join('veiculos', 'veiculos.id = ordem_servicos.veiculo_id')
                    ->where('ordem_servicos.empresa_id', $empresaId)
                    ->orderBy('ordem_servicos.id', 'DESC')
                    ->findAll();
    }

    /**
     * Busca todos os detalhes de uma OS específica (Cabeçalho completo)
     */
   public function getOsCompleta($id)
{
    return $this->select('ordem_servicos.*, 
                          clientes.nome_razao as cliente_nome, 
                          clientes.telefone as cliente_telefone, 
                          clientes.celular as cliente_celular, 
                          clientes.documento as cliente_documento,
                          veiculos.marca as veiculo_marca, 
                          veiculos.modelo as veiculo_modelo, 
                          veiculos.placa as veiculo_placa,
                          veiculos.ano as veiculo_ano,
                          veiculos.cor as veiculo_cor,
                          funcionarios.nome as tecnico_nome')
                ->join('clientes', 'clientes.id = ordem_servicos.cliente_id')
                ->join('veiculos', 'veiculos.id = ordem_servicos.veiculo_id')
                ->join('funcionarios', 'funcionarios.id = ordem_servicos.tecnico_id', 'left')
                ->where('ordem_servicos.id', $id)
                // Garante que a OS pertence à empresa logada
                ->where('ordem_servicos.empresa_id', session()->get('empresa_id')) 
                ->first();
}

    /**
     * Gera o próximo número de OS baseado no ano atual
     * Exemplo: 20260001, 20260002...
     */
    public function geraProximoNumero($empresaId)
    {
        $anoAtual = date('Y');
        
        $ultima = $this->where('empresa_id', $empresaId)
                       ->like('numero_os', $anoAtual, 'after')
                       ->orderBy('numero_os', 'DESC')
                       ->first();

        if (!$ultima) {
            return $anoAtual . "0001";
        }

        // Incrementa o número atual
        $novoNumero = intval($ultima['numero_os']) + 1;
        return (string)$novoNumero;
    }

    /**
     * Atualiza o status da OS validando a transição
     */
    public function atualizarStatus($id, $novoStatus)
    {
        $dados = ['status' => $novoStatus];

        if ($novoStatus == 'aprovada') {
            $dados['data_aprovacao'] = date('Y-m-d H:i:s');
        }

        if ($novoStatus == 'finalizada') {
            $dados['data_fechamento'] = date('Y-m-d H:i:s');
        }

        return $this->update($id, $dados);
    }

    public function salvar_abertura()
{
    // Validação básica
    if (!$this->request->getPost('cliente_id') || !$this->request->getPost('veiculo_id')) {
        return redirect()->back()->withInput()->with('error', 'Selecione o cliente e o veículo.');
    }

    $dados = [
        'empresa_id'         => session()->get('empresa_id'),
        'numero_os'          => $this->request->getPost('numero_os'),
        'cliente_id'         => $this->request->getPost('cliente_id'),
        'tecnico_id'         => $this->request->getPost('tecnico_id'),
        'veiculo_id'         => $this->request->getPost('veiculo_id'),
        'km_entrada'         => $this->request->getPost('km_entrada'),
        'descricao_problema' => $this->request->getPost('descricao_problema'),
        'status'             => 'orcamento',
        'data_abertura'      => date('Y-m-d H:i:s'),
        'criado_por'         => session()->get('user_id'),
        'valor_total'        => 0 // Inicia com zero
    ];

    if ($this->osModel->insert($dados)) {
        $id = $this->osModel->getInsertID();
        return redirect()->to("os/gerenciar/$id")->with('success', 'OS #' . $dados['numero_os'] . ' aberta! Agora você pode lançar os serviços e peças.');
    }

    return redirect()->back()->withInput()->with('error', 'Falha ao gravar OS no banco de dados.');


}

public function getListaCompleta($limite = null, $status = null)
{
    $builder = $this->select('ordem_servicos.*, clientes.nome_razao as cliente_nome, veiculos.placa as veiculo_placa, veiculos.modelo as veiculo_modelo')
                    ->join('clientes', 'clientes.id = ordem_servicos.cliente_id')
                    ->join('veiculos', 'veiculos.id = ordem_servicos.veiculo_id')
                    ->where('ordem_servicos.empresa_id', session()->get('empresa_id'));

    // Filtra por status se for passado (ex: 'aberto' ou 'finalizada')
    if ($status) {
        $builder->where('ordem_servico.status', $status);
    }

    // Ordena pelas mais recentes
    $builder->orderBy('ordem_servicos.data_abertura', 'DESC');

    // Aplica o limite (útil para o Dashboard mostrar apenas as últimas 5)
    if ($limite) {
        return $builder->findAll($limite);
    }

    return $builder->findAll();
}

/**
 * Busca os itens vinculados à OS na tabela ordem_servico_itens
 */
public function getItens($osId)
{
    return $this->db->table('ordem_servico_itens') 
        ->select('*, (quantidade * valor_unitario) as subtotal')
        ->where('ordem_servico_id', $osId)
        ->get()
        ->getResultArray();
}

/**
 * Busca as fotos/evidências vinculadas à OS
 */
public function getFotos($osId)
{
    // Verifique se o nome da sua tabela é exatamente 'ordem_servico_fotos'
    return $this->db->table('ordem_servico_fotos') 
        ->where('ordem_servico_id', $osId)
        ->get()
        ->getResultArray();
}


public function carregarChecklistPadrao($os_id) {
    $itensPadrao = $this->db->table('checklists')->get()->getResultArray();
    
    foreach ($itensPadrao as $item) {
        $this->db->table('os_checklists')->insert([
            'ordem_servico_id' => $os_id,
            'descricao'        => $item['descricao'],
            'tipo'             => 'entrada', // ou 'servico'
            'status'           => 'pendente'
        ]);
    }
}
}