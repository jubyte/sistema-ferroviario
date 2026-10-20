
<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../infra/conexao.php';

try {
    $sql = "SELECT s.id, s.nome, s.tipo,
                   s.localizacao AS local,
                   s.trem_id, t.nome AS trem_nome, s.status
            FROM sensores s
            LEFT JOIN trens t ON t.id = s.trem_id
            ORDER BY s.id DESC";

    $resultado = $conn->query($sql);

    if (!$resultado) {
        throw new RuntimeException('Falha ao consultar sensores.');
    }

    $sensores = $resultado->fetch_all(MYSQLI_ASSOC);
    $resultado->free();

    echo json_encode([
        'status' => 'success',
        'sensores' => $sensores
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao carregar sensores.'
    ], JSON_UNESCAPED_UNICODE);
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>

