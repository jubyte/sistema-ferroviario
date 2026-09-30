<?php

require_once '../../../infra/conexao.php';

header('Content-Type: application/json');

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';

if (empty($nome) || empty($email) || empty($telefone)) {

    echo json_encode([
        "status" => "error",
        "message" => "Preencha todos os campos."
    ]);

    exit;
}

try {

    $sql = "INSERT INTO usuarios (nome, email, senha)
            VALUES (:nome, :email, :senha)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senha
    ]);

    echo json_encode([
        "status" => "success",
        "message" => "Usuário cadastrado com sucesso!"
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}