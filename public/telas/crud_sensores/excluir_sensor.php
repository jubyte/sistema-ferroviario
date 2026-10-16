
?>
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
    responder('error', 'ID do sensor não informado.', 400);
}

if (!ctype_digit($id) || (int) $id < 1) {
    responder('error', 'ID do sensor inválido.', 400);
}

$id = (int) $id;

try {

    $sql = "DELETE FROM sensores WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Falha ao preparar a exclusão.');
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {

        $stmt->close();

        responder('success', 'Sensor excluído com sucesso!');

    } else {

        $stmt->close();

        responder('error', 'Sensor não encontrado.', 404);
    }

} catch (mysqli_sql_exception $e) {

    error_log($e->getMessage());

    responder(
        'error',
        'Não foi possível excluir o sensor. Verifique se existem registros vinculados.',
        409
    );

} catch (Throwable $e) {

    error_log($e->getMessage());

    responder('error', 'Erro ao excluir sensor.', 500);

} finally {

    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>
