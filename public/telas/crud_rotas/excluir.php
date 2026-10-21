
<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            'status' => 'error',
            'message' => 'Método não permitido.'
        ]);
        exit;
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!$id || $id < 1) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'ID da rota inválido.'
        ]);
        exit;
    }

    $sql = "DELETE FROM rotas WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Rota não encontrada.'
        ]);

        $stmt->close();
        $conn->close();
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Rota excluída com sucesso!'
    ]);

    $stmt->close();
    $conn->close();

} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => 'Não foi possível excluir a rota.'
    ]);
}
