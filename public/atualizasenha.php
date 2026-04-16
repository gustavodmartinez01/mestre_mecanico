<?php
// Configuração do banco
$host = "localhost";
$db   = "mestre_mecanico";
$user = "root";
$pass = "";

// Conexão com PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Senha nova
    $novaSenha = "senha123";
    $hashSenha = password_hash($novaSenha, PASSWORD_DEFAULT);

    // Atualizar usuários de id 1 até 10
    $sql = "UPDATE usuarios SET senha = :senha WHERE id BETWEEN 6 AND 10";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":senha", $hashSenha);
    $stmt->execute();

    echo "Senhas atualizadas com sucesso!<br>";

    // Exemplo de verificação
    $sql = "SELECT id, senha FROM usuarios WHERE id BETWEEN 1 AND 5";
    $stmt = $pdo->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($usuarios as $usuario) {
        if (password_verify($novaSenha, $usuario["senha"])) {
            echo "Usuário ID {$usuario['id']}: senha verificada com sucesso!<br>";
        } else {
            echo "Usuário ID {$usuario['id']}: falha na verificação da senha!<br>";
        }
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
