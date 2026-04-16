<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table            = 'clientes';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'empresa_id', 'tipo_pessoa', 'nome_razao', 'documento', 'email', 
        'telefone', 'celular', 'cep', 'logradouro', 'numero', 'complemento', 
        'bairro', 'cidade', 'estado', 'score_historico', 'score_perfil', 
        'score_relacionamento', 'score_documentacao', 'score_total', 
        'classificacao', 'observacoes_financeiras', 'ativo'
    ];

    // Antes de salvar (insert ou update), calcula o score
    protected $beforeInsert = ['calcularScore'];
    protected $beforeUpdate = ['calcularScore'];

    protected function calcularScore(array $data)
    {
        if (!isset($data['data'])) return $data;

        $d = $data['data'];
        
        // Soma os blocos (garantindo que sejam inteiros)
        $historico = (int)($d['score_historico'] ?? 0);
        $perfil    = (int)($d['score_perfil'] ?? 0);
        $relacao   = (int)($d['score_relacionamento'] ?? 0);
        $docs      = (int)($d['score_documentacao'] ?? 0);

        $total = $historico + $perfil + $relacao + $docs;
        
        $data['data']['score_total'] = $total;

        // Define a Classificação Ouro/Prata/Bronze baseada na sua regra
        if ($total >= 80) {
            $data['data']['classificacao'] = 'Ouro';
        } elseif ($total >= 60) {
            $data['data']['classificacao'] = 'Prata';
        } else {
            $data['data']['classificacao'] = 'Bronze';
        }

        return $data;
    }
}