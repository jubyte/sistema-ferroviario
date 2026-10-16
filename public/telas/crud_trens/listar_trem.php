
<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

try {

    $sql = "SELECT id, nome, empresa, tipo, numero_vagoes, status
            FROM trens
            ORDER BY id DESC";

    $resultado = $conn->query($sql);

    if (!$resultado) {
        throw new RuntimeException('Falha ao consultar os trens.');
    }

    $trens = $resultado->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'status' => 'success',
        'trens' => $trens
    ], JSON_UNESCAPED_UNICODE);

    $resultado->free();

} catch (Throwable $e) {

    error_log($e->getMessage());

    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao carregar os trens.'
    ], JSON_UNESCAPED_UNICODE);

} finally {

    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>
