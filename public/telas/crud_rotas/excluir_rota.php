<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

$id = $_POST['id'] ?? '';

if ($id === '') {

    echo json_encode([
        "status" => "error",
        "message" => "ID da rota não informado."
    ]);

    exit;
}

$sql = "DELETE FROM rotas WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" => "Rota excluída com sucesso."
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao excluir rota."
    ]);
}

$stmt->close();
$conn->close();
?>