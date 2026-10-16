
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
    responder('error', 'ID do trem não informado.', 400);
}

if (!ctype_digit($id) || (int) $id < 1) {
    responder('error', 'ID do trem inválido.', 400);
}

$id = (int) $id;

try {

    $sql = "DELETE FROM trens WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Falha ao preparar a exclusão.');
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {

        $stmt->close();

        responder('success', 'Trem excluído com sucesso!');

    } else {

        $stmt->close();

        responder('error', 'Trem não encontrado.', 404);
    }

} catch (mysqli_sql_exception $e) {

    error_log($e->getMessage());

    responder(
        'error',
        'Não foi possível excluir o trem. Verifique se ele está vinculado a outros registros.',
        409
    );

} catch (Throwable $e) {

    error_log($e->getMessage());

    responder('error', 'Erro ao excluir trem.', 500);

} finally {

    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>
