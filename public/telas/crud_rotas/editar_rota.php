<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

$id = $_POST['id'] ?? '';
$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$distancia = $_POST['distancia'] ?? '';
$tempo = $_POST['tempo'] ?? '';

if (
    $id === '' ||
    $nome === '' ||
    $descricao === '' ||
    $distancia === '' ||
    $tempo === ''
) {

    echo json_encode([
        "status" => "error",
        "message" => "Preencha todos os campos."
    ]);

    exit;
}

$sql = "UPDATE rotas
        SET
            nome = ?,
            descricao = ?,
            distancia = ?,
            tempo_estimado = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssdii",
    $nome,
    $descricao,
    $distancia,
    $tempo,
    $id
);

if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" => "Rota editada com sucesso."
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao editar rota."
    ]);
}

$stmt->close();
$conn->close();
?>