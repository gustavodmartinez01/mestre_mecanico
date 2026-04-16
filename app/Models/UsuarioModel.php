<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['empresa_id', 'email', 'senha', 'nivel_acesso', 'status'];
    
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    protected $beforeInsert     = ['hashPassword'];
    protected $beforeUpdate     = ['hashPassword'];

   protected function hashPassword(array $data)
{
    // Só criptografa se a senha existir no array e não estiver vazia
    if (isset($data['data']['senha']) && !empty($data['data']['senha'])) {
        $data['data']['senha'] = password_hash($data['data']['senha'], PASSWORD_DEFAULT);
    } else {
        // Se a senha veio vazia em um Update, removemos do array para não sobrescrever o que está no banco
        unset($data['data']['senha']);
    }

    return $data;
}
}