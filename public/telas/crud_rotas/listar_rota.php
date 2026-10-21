<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

$sql = "SELECT
            id,
            nome,
            descricao,
            distancia,
            tempo_estimado AS tempo,
            trem_id,
            status
        FROM rotas
        ORDER BY id ASC";

$resultado = $conn->query($sql);

if (!$resultado) {

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao listar rotas."
    ]);

    exit;
}

$rotas = [];

while ($rota = $resultado->fetch_assoc()) {
    $rotas[] = $rota;
}

echo json_encode([
    "status" => "success",
    "rotas" => $rotas
]);

$conn->close();
?>