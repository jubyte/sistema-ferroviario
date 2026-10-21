<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

function responder($status, $mensagem, $codigo = 200)
{
    http_response_code($codigo);

    echo json_encode([
        'status' => $status,
        'message' => $mensagem
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder('error', 'Método de requisição inválido.', 405);
}

$id = trim($_POST['id'] ?? '');

if ($id === '') {
    responder('error', 'ID da rota não informado.', 400);
}

if (!ctype_digit($id) || (int) $id < 1) {
    responder('error', 'ID da rota inválido.', 400);
}

$id = (int) $id;

try {

    $sql = "DELETE FROM rotas WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Erro ao preparar a exclusão.');
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {

        $stmt->close();

        responder(
            'success',
            'Rota removida com sucesso!'
        );

    } else {

        $stmt->close();

        responder(
            'error',
            'Rota não encontrada.',
            404
        );
    }

} catch (mysqli_sql_exception $e) {

    error_log($e->getMessage());

    responder(
        'error',
        'Não foi possível excluir a rota.',
        500
    );

} catch (Throwable $e) {

    error_log($e->getMessage());

    responder(
        'error',
        'Erro ao excluir rota.',
        500
    );

} finally {

    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>