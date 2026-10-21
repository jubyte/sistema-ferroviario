<?php
header('Content-Type: application/json; charset=utf-8');
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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder('error', 'Método de requisição inválido.', 405);
}

$id = trim($_POST['id'] ?? '');

if ($id === '') {
    responder('error', 'ID do usuário não informado.', 400);
}

if (!ctype_digit($id) || (int) $id < 1) {
    responder('error', 'ID do usuário inválido.', 400);
}

$id = (int) $id;

try {
    $sql = "DELETE FROM usuarios WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $stmt->close();
        responder('success', 'Usuário removido com sucesso.');
    }

    $stmt->close();
    responder('error', 'Usuário não encontrado.', 404);

} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    responder('error', 'Não foi possível excluir usuário.', 500);
}
?>
