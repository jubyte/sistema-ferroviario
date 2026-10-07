<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

$id = $_POST['id'] ?? '';
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');

if (
    $id === '' ||
    $nome === '' ||
    $email === '' ||
    $telefone === ''
) {

    echo json_encode([
        "status" => "error",
        "message" => "Preencha todos os campos."
    ]);

    exit;
}


/* NOME */

if (!preg_match('/^[\p{L}\s]+$/u', $nome)) {

    echo json_encode([
        "status" => "error",
        "message" => "O nome deve conter apenas letras e espaços."
    ]);

    exit;
}


/* E-MAIL */

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo json_encode([
        "status" => "error",
        "message" => "Digite um e-mail válido."
    ]);

    exit;
}


/* TELEFONE */

if (!preg_match('/^[0-9()\s+\-]+$/', $telefone)) {

    echo json_encode([
        "status" => "error",
        "message" => "Digite um telefone válido."
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
        "status" => "success",
        "message" => "Usuário atualizado com sucesso!"
    ]);

} catch (PDOException $e) {

    error_log($e->getMessage());

    if ($e->getCode() == 23000) {

        echo json_encode([
            "status" => "error",
            "message" => "Este e-mail já está cadastrado."
        ]);

    } else {

        echo json_encode([
            "status" => "error",
            "message" => "Erro ao editar usuário."
        ]);
    }
}

?>