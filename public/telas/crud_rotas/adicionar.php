
<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Método não permitido.'
    ]);
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$inicio = trim($_POST['localizacao_inicial'] ?? '');
$fim = trim($_POST['localizacao_final'] ?? '');

if ($nome === '' || $inicio === '' || $fim === '') {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Preencha todos os campos.'
    ]);
    exit;
}

try {
    $sql = "INSERT INTO rotas
            (nome, localizacao_inicial, localizacao_final)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $nome, $inicio, $fim);
    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'message' => 'Rota cadastrada com sucesso.',
        'id' => $conn->insert_id
    ], JSON_UNESCAPED_UNICODE);

    $stmt->close();
    $conn->close();

} catch (Throwable $e) {
    error_log('Erro ao cadastrar rota: ' . $e->getMessage());

    http_response_code(500);

    // Mensagem temporária para descobrir o erro durante o desenvolvimento.
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro no PHP ou no banco de dados.',
        'detalhe' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
