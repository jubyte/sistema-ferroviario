
<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

try {
    $busca = trim($_GET['busca'] ?? '');

    if ($busca !== '') {
        if (is_numeric($busca)) {
            $sql = "SELECT id, nome, localizacao_inicial,
                           localizacao_final
                    FROM rotas
                    WHERE nome LIKE ? OR id = ?
                    ORDER BY id ASC";

            $stmt = $conn->prepare($sql);
            $nomeBusca = "%$busca%";
            $idBusca = (int) $busca;

            $stmt->bind_param("si", $nomeBusca, $idBusca);
        } else {
            $sql = "SELECT id, nome, localizacao_inicial,
                           localizacao_final
                    FROM rotas
                    WHERE nome LIKE ?
                    ORDER BY id ASC";

            $stmt = $conn->prepare($sql);
            $nomeBusca = "%$busca%";

            $stmt->bind_param("s", $nomeBusca);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();
    } else {
        $sql = "SELECT id, nome, localizacao_inicial,
                       localizacao_final
                FROM rotas
                ORDER BY id ASC";

        $resultado = $conn->query($sql);
    }

    $rotas = $resultado->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'status' => 'success',
        'rotas' => $rotas
    ], JSON_UNESCAPED_UNICODE);

    if (isset($stmt)) {
        $stmt->close();
    }

    $conn->close();

} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => 'Não foi possível consultar as rotas.'
    ], JSON_UNESCAPED_UNICODE);
}
