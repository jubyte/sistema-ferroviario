<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

$id     = $_POST['id'] ?? '';
$nome   = trim($_POST['nome'] ?? '');
$inicio = trim($_POST['localizacao_inicial'] ?? '');
$fim    = trim($_POST['localizacao_final'] ?? '');

if ($id === '' || $nome === '' || $inicio === '' || $fim === '') {
    echo json_encode([
        "status"  => "error",
        "message" => "Preencha todos os campos."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt = $conn->prepare(
    "UPDATE rotas
     SET nome = ?, localizacao_inicial = ?, localizacao_final = ?
     WHERE id = ?"
);
$stmt->bind_param("sssi", $nome, $inicio, $fim, $id);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Rota editada com sucesso."
    ], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Erro ao editar rota.",
        "detalhe" => $stmt->error
    ], JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$conn->close();