<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once __DIR__ . '/../../../infra/conexao.php';

function responder($status, $message, $codigo = 200)
{
    http_response_code($codigo);

    echo json_encode([
        'status' => $status,
        'message' => $message
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

function verificarPermissaoAdmin()
{
    if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'Administrador') {
        responder('error', 'Você não tem permissão para realizar tal ação', 403);
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder('error', 'Método de requisição inválido.', 405);
}

verificarPermissaoAdmin();

$id = $_POST['id'] ?? '';

if ($id === '') {
    echo json_encode([
        "status"  => "error",
        "message" => "ID da rota não informado."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt = $conn->prepare("DELETE FROM rotas WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Rota excluída com sucesso."
    ], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Erro ao excluir rota.",
        "detalhe" => $stmt->error
    ], JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$conn->close();