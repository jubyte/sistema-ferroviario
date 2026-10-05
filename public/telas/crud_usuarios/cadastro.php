<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../../infra/conexao.php';

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');

if ($nome === '' || $email === '' || $telefone === '' || $tipo === '') {

    echo json_encode([
        'status' => 'error',
        'message' => 'Preencha todos os campos.'
    ]);

    exit;
}

try {

    $sql = "INSERT INTO usuarios
            (nome, email, telefone, tipo)
            VALUES
            (:nome, :email, :telefone, :tipo)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':telefone' => $telefone,
        ':tipo' => $tipo
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Usuário cadastrado com sucesso!'
    ]);

} catch (PDOException $e) {

    if ($e->getCode() == 23000) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Este e-mail já está cadastrado.'
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Erro ao cadastrar usuário: ' . $e->getMessage()
        ]);

    }

}