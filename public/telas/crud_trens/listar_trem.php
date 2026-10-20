
<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

try {
    $sql = "SELECT
                id,
                nome,
                empresa,
                numero_vagoes,
                tipo,
                status
            FROM trens
            ORDER BY nome ASC";

    $resultado = $conn->query($sql);

    if (!$resultado) {
        throw new RuntimeException('Falha ao consultar trens.');
    }

    $trens = $resultado->fetch_all(MYSQLI_ASSOC);
    $resultado->free();

    echo json_encode([
        'status' => 'success',
        'trens' => $trens
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao carregar trens.'
    ], JSON_UNESCAPED_UNICODE);

} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>