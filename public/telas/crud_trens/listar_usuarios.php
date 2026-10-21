<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

try {
    $sql = "SELECT id, nome FROM usuarios ORDER BY nome ASC";

    $resultado = $conn->query($sql);

    if (!$resultado) {
        throw new RuntimeException('Erro ao consultar usuários.');
    }

    $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);

    $resultado->free();

    echo json_encode([
        'status' => 'success',
        'usuarios' => $usuarios
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    error_log($e->getMessage());

    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => 'Não foi possível carregar os usuários.'
    ], JSON_UNESCAPED_UNICODE);

} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
