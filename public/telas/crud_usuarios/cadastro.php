<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');

if (
    $nome === '' ||
    $email === '' ||
    $telefone === '' ||
    $tipo === ''
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


/* TIPO */

$tiposPermitidos = [
    "Administrador",
    "Usuário"
];

if (!in_array($tipo, $tiposPermitidos, true)) {

    echo json_encode([
        "status" => "error",
        "message" => "Tipo de usuário inválido."
    ]);

    exit;
}


try {

    $sql = "INSERT INTO usuarios
            (nome, email, telefone, tipo, status)
            VALUES
            (:nome, :email, :telefone, :tipo, 'Ativo')";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':telefone' => $telefone,
        ':tipo' => $tipo
    ]);

    echo json_encode([
        "status" => "success",
        "message" => "Usuário cadastrado com sucesso!"
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
            "message" => "Erro ao cadastrar usuário."
        ]);
    }
}

?>