
<?php
header('Content-Type: application/json; charset=utf-8');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    require_once __DIR__ . '/../../../infra/conexao.php';

    $sql = "SELECT id, nome FROM usuarios ORDER BY nome ASC";
    $resultado = $conn->query($sql);

    $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);
    $resultado->free();

    echo json_encode([
        'status' => 'success',
        'usuarios' => $usuarios
    ], JSON_UNESCAPED_UNICODE);

    $conn->close();


} catch (Throwable $e) {
    error_log('Erro em listar_usuarios.php: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}


