<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../infra/conexao.php';

try {
    $busca = trim($_GET['busca'] ?? '');

    if ($busca !== '') {
        if (is_numeric($busca)) {
            $sql = "SELECT id, nome, email, telefone, tipo, status FROM usuarios WHERE nome LIKE ? OR id = ? ORDER BY id ASC";
            $stmt = $conn->prepare($sql);
            $nomeBusca = "%$busca%";

            $idBusca = (int) $busca;
            $stmt->bind_param('si', $nomeBusca, $idBusca);

        } else {
            $sql = "SELECT id, nome, email, telefone, tipo, status FROM usuarios WHERE nome LIKE ? ORDER BY id ASC";
            $stmt = $conn->prepare($sql);
            $nomeBusca = "%$busca%";

            $stmt->bind_param('s', $nomeBusca);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();

    } else {
        $sql = "SELECT id, nome, email, telefone, tipo, status FROM usuarios ORDER BY id ASC";
        $resultado = $conn->query($sql);
    }
    $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'status' => 'success',
        'usuarios' => $usuarios
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => 'Não foi possível consultar os usuários.'
    ], JSON_UNESCAPED_UNICODE);
}
?>
