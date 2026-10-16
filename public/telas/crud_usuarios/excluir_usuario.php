
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

// Aceitar somente requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder('error', 'Método de requisição inválido.', 405);
}

// Receber o ID
$id = trim($_POST['id'] ?? '');

// Verificar se o ID foi informado
if ($id === '') {
    responder('error', 'ID do usuário não informado.', 400);
}

// Validar se o ID é um número inteiro positivo
if (!ctype_digit($id) || (int) $id < 1) {
    responder('error', 'ID do usuário inválido.', 400);
}

$id = (int) $id;

try {

    // Preparar a exclusão para evitar SQL Injection
    $sql = "DELETE FROM usuarios WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Falha ao preparar a exclusão.');
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();

    // Verificar se algum registro foi excluído
    if ($stmt->affected_rows > 0) {

        $stmt->close();

        responder(
            'success',
            'Usuário removido com sucesso!'
        );

    } else {

        $stmt->close();

        responder(
            'error',
            'Usuário não encontrado.',
            404
        );
    }

} catch (mysqli_sql_exception $e) {

    error_log($e->getMessage());

    responder(
        'error',
        'Não foi possível excluir o usuário.',
        500
    );

} catch (Throwable $e) {

    error_log($e->getMessage());

    responder(
        'error',
        'Erro ao excluir usuário.',
        500
    );

} finally {

    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>
