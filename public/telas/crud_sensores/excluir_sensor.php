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
    responder('error', 'Método inválido.', 405);
}

$idTexto = $_POST['id'] ?? '';

if (!ctype_digit((string)$idTexto) || (int)$idTexto < 1) {
    responder('error', 'ID do sensor inválido.', 400);
}

$id = (int)$idTexto;

try {
    $stmt = $conn->prepare("DELETE FROM sensores WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $excluidos = $stmt->affected_rows;
    $stmt->close();

    if ($excluidos === 0) {
        responder('error', 'Sensor não encontrado.', 404);
    }

    responder('success', 'Sensor excluído com sucesso!');

} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    responder(
        'error',
        'Não foi possível excluir. Verifique se existem leituras ou alertas associados.',
        409
    );
} catch (Throwable $e) {
    error_log($e->getMessage());
    responder('error', 'Erro interno ao excluir sensor.', 500);
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>