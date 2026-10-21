<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

try {
    $busca = trim($_GET['busca'] ?? '');

    if ($busca !== '') {

        if (is_numeric($busca)) {

            $sql = "SELECT id, nome, descricao, distancia, tempo_estimado, trem_id, data_criacao
                    FROM rotas
                    WHERE nome LIKE ?
                    OR id = ?
                    ORDER BY id ASC";

            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new RuntimeException('Erro ao preparar a consulta.');
            }

            $nomeBusca = "%$busca%";
            $idBusca = (int) $busca;

            $stmt->bind_param('si', $nomeBusca, $idBusca);

        } else {

            $sql = "SELECT id, nome, descricao, distancia, tempo_estimado, trem_id, data_criacao
                    FROM rotas
                    WHERE nome LIKE ?
                    ORDER BY id ASC";

            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new RuntimeException('Erro ao preparar a consulta.');
            }

            $nomeBusca = "%$busca%";

            $stmt->bind_param('s', $nomeBusca);
        }

        if (!$stmt->execute()) {
            throw new RuntimeException('Erro ao executar a consulta.');
        }

        $resultado = $stmt->get_result();

    } else {

        $sql = "SELECT id, nome, descricao, distancia, tempo_estimado, trem_id, data_criacao
                FROM rotas
                ORDER BY id ASC";

        $resultado = $conn->query($sql);

        if (!$resultado) {
            throw new RuntimeException('Erro ao consultar as rotas.');
        }
    }

    $rotas = $resultado->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'status' => 'success',
        'rotas' => $rotas
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {

    error_log($e->getMessage());

    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => 'Não foi possível consultar as rotas.'
    ], JSON_UNESCAPED_UNICODE);

} finally {

    if (isset($stmt) && $stmt instanceof mysqli_stmt) {
        $stmt->close();
    }

    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>