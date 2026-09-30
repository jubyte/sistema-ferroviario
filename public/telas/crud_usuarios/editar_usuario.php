<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../../infra/conexao.php';

$id = $_POST['id'] ?? null;
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');

if (!$id || $nome === '' || $email === '' || $telefone === '') {

    echo json_encode([
        'status' => 'error',
        'message' => 'Preencha todos os campos.'
    ]);

    exit;
}

try {

    $sql = "UPDATE usuarios
            SET nome = :nome,
                email = :email,
                telefone = :telefone
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':telefone' => $telefone,
        ':id' => $id
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Usuário atualizado com sucesso!'
    ]);

} catch (PDOException $e) {

    if ($e->getCode() == 23000) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Este e-mail já está cadastrado para outro usuário.'
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Erro ao editar usuário: ' . $e->getMessage()
        ]);

    }

}