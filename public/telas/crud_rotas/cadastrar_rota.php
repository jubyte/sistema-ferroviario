<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$distancia = $_POST['distancia'] ?? '';
$tempo = $_POST['tempo'] ?? '';
$tremId = $_POST['trem_id'] ?? '';

if (
    $nome === '' ||
    $descricao === '' ||
    $distancia === '' ||
    $tempo === '' ||
    $tremId === ''
) {

    echo json_encode([
        "status" => "error",
        "message" => "Preencha todos os campos."
    ]);

    exit;
}

$sql = "INSERT INTO rotas
        (
            nome,
            descricao,
            distancia,
            tempo_estimado,
            trem_id,
            status
        )
        VALUES (?, ?, ?, ?, ?, 'Ativo')";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssdii",
    $nome,
    $descricao,
    $distancia,
    $tempo,
    $tremId
);

if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" => "Rota cadastrada com sucesso."
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao cadastrar rota."
    ]);
}

$stmt->close();
$conn->close();
?>