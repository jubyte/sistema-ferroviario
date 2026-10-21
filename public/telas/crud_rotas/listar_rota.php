<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

$sql = "SELECT
            r.id,
            r.nome,
            r.descricao,
            r.distancia,
            r.tempo_estimado AS tempo,
            r.trem_id,
            t.nome AS trem_nome,
            r.status
        FROM rotas r
        LEFT JOIN trens t ON r.trem_id = t.id
        ORDER BY r.id ASC";

$resultado = $conn->query($sql);

if (!$resultado) {
    http_response_code(500);

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao listar rotas: " . $conn->error
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

$rotas = [];

while ($rota = $resultado->fetch_assoc()) {
    $rotas[] = $rota;
}

echo json_encode([
    "status" => "success",
    "rotas" => $rotas
], JSON_UNESCAPED_UNICODE);

$conn->close();
?>