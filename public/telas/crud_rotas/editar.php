
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
    $nome = trim($_POST['nome'] ?? '');
    $inicio = trim($_POST['localizacao_inicial'] ?? '');
    $fim = trim($_POST['localizacao_final'] ?? '');

    if (!$id || $id < 1) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'ID da rota inválido.'
        ]);
        exit;
    }

    if ($nome === '' || $inicio === '' || $fim === '') {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Preencha todos os campos.'
        ]);
        exit;
    }

    $sql = "UPDATE rotas
            SET nome = ?,
                localizacao_inicial = ?,
                localizacao_final = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nome, $inicio, $fim, $id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        // Pode significar que o ID não existe ou que
        // os dados enviados são iguais aos dados atuais.
        $verificar = $conn->prepare(
            "SELECT id FROM rotas WHERE id = ?"
        );
        $verificar->bind_param("i", $id);
        $verificar->execute();
        $resultado = $verificar->get_result();

        if ($resultado->num_rows === 0) {
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => 'Rota não encontrada.'
            ]);
            $verificar->close();
            $stmt->close();
            $conn->close();
            exit;
        }

        $verificar->close();
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Rota atualizada com sucesso!'
    ]);

    $stmt->close();
    $conn->close();

} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => 'Não foi possível atualizar a rota.'
    ]);
}
