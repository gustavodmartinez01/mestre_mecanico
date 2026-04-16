<?php

namespace App\Models;

use CodeIgniter\Model;

class ContasPagarModel extends Model
{
    protected $table            = 'contas_pagar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'empresa_id', 'fornecedor_id', 'os_id', 'categoria_id', 'centro_custo_id',
        'nota_fiscal_id', 'descricao', 'valor_original', 'valor_pago', 'desconto',
        'juros_mora', 'multa_mora', 'atualizacao_monetaria', 'parcela_atual',
        'total_parcelas', 'id_agrupador', 'data_vencimento', 'data_pagamento',
        'status', 'completa', 'conta_bancaria_id', 'forma_pagamento',
        'observacoes', 'comprovante_arquivo'
    ];

    protected $useTimestamps = true;

    /**
     * Busca agrupada para a listagem principal (estilo o que fizemos no Receber)
     */
    public function listarAgrupado($empresa_id)
    {
        return $this->select('
                id_agrupador, 
                descricao, 
                MAX(total_parcelas) as total_parcelas,
                SUM(valor_original) as valor_total,
                MIN(data_vencimento) as primeira_vencimento,
                MAX(data_vencimento) as ultima_vencimento,
                COUNT(id) as qtd_parcelas,
                CASE 
                    WHEN SUM(CASE WHEN status = "cancelada" THEN 1 ELSE 0 END) > 0 THEN "cancelada"
                    WHEN SUM(CASE WHEN status != "paga" THEN 1 ELSE 0 END) = 0 THEN "quitado"
                    WHEN SUM(CASE WHEN status = "vencida" THEN 1 ELSE 0 END) > 0 THEN "atrasado"
                    ELSE "em_dia"
                END as situacao_grupo
            ')
            ->where('empresa_id', $empresa_id)
            ->groupBy('id_agrupador')
            ->orderBy('primeira_vencimento', 'ASC')
            ->findAll();
    }
    /**
 * Busca a próxima parcela pendente de um agrupador
 */
public function obter_proxima($id_agrupador)
{
    $parcela = $this->contaPagarModel
        ->where('id_agrupador', $id_agrupador)
        ->where('status !=', 'paga')
        ->where('status !=', 'cancelada')
        ->orderBy('parcela_atual', 'ASC')
        ->first();

    if ($parcela) {
        return $this->response->setJSON($parcela);
    }

    return $this->response->setJSON(['status_operacao' => false]);
}

/**
 * Processa o pagamento da parcela
 */
public function confirmar_pagamento()
{
    $id = $this->request->getPost('id_parcela');
    $valor_pago = $this->request->getPost('valor_pagamento');
    
    $dados = [
        'valor_pago'     => $valor_pago,
        'data_pagamento' => $this->request->getPost('data_pagamento'),
        'forma_pagamento'=> $this->request->getPost('forma_pagamento'),
        'status'         => 'paga',
        'observacoes'    => $this->request->getPost('observacoes')
    ];

    // Lógica de Upload do Comprovante
    $arquivo = $this->request->getFile('comprovante');
    if ($arquivo && $arquivo->isValid() && !$arquivo->hasMoved()) {
        $novoNome = $arquivo->getRandomName();
        $arquivo->move(FCPATH . 'uploads/comprovantes_pagar/', $novoNome);
        $dados['comprovante_arquivo'] = $novoNome;
    }

    if ($this->contaPagarModel->update($id, $dados)) {
        return $this->response->setJSON(['status' => true, 'msg' => 'Pagamento registrado com sucesso!']);
    }

    return $this->response->setJSON(['status' => false, 'msg' => 'Erro ao registrar pagamento.']);
}
}