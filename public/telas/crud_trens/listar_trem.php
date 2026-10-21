<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

try {
    $sql = "SELECT
                t.id,
                t.nome,
                t.empresa,
                t.numero_vagoes,
                t.tipo,
                t.status,
                t.responsavel_id,
                u.nome AS responsavel_nome
            FROM trens t
            LEFT JOIN usuarios u
                ON t.responsavel_id = u.id
            ORDER BY t.nome ASC";

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