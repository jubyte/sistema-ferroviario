<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

$sql = "SELECT id, nome, localizacao_inicial, localizacao_final
        FROM rotas
        ORDER BY id ASC";

$resultado = $conn->query($sql);

if (!$resultado) {
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Erro ao listar rotas.",
        "detalhe" => $conn->error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$rotas = [];
while ($rota = $resultado->fetch_assoc()) {
    $rotas[] = $rota;
}

echo json_encode([
    "status" => "success",
    "rotas"  => $rotas
], JSON_UNESCAPED_UNICODE);

$conn->close();