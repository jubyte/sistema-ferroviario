<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

$nome   = trim($_POST['nome'] ?? '');
$inicio = trim($_POST['localizacao_inicial'] ?? '');
$fim    = trim($_POST['localizacao_final'] ?? '');

if ($nome === '' || $inicio === '' || $fim === '') {
    echo json_encode([
        "status"  => "error",
        "message" => "Preencha todos os campos."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO rotas (nome, localizacao_inicial, localizacao_final, descricao, distancia, tempo_estimado, trem_id)
     VALUES (?, ?, ?, NULL, NULL, NULL, NULL)"
);
$stmt->bind_param("sss", $nome, $inicio, $fim);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Rota cadastrada com sucesso."
    ], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Erro ao cadastrar rota.",
        "detalhe" => $stmt->error
    ], JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$conn->close();