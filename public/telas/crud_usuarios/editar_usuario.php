<?php

header('Content-Type: application/json');

require_once '../../../infra/conexao.php';

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';

if (!$id || empty($nome) || empty($email)) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Preencha os campos obrigatórios.'
    ]);

    exit;
}

try {

    $sql = "UPDATE usuarios
            SET nome = :nome,
                email = :email
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':id' => $id
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Usuário atualizado com sucesso!'
    ]);

} catch (PDOException $e) {

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);

}