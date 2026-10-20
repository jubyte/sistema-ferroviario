
<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

function responder($status, $message, $codigo = 200)
{
    http_response_code($codigo);

    echo json_encode([
        'status' => $status,
        'message' => $message
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder('error', 'Método de requisição inválido.', 405);
}

$nome = trim($_POST['nome'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$local = trim($_POST['local'] ?? '');
$status = trim($_POST['status'] ?? '');
$tremIdRecebido = trim($_POST['trem_id'] ?? '');

if ($nome === '' || $tipo === '' || $local === '' || $status === '') {
    responder('error', 'Preencha nome, tipo, localização e status.', 400);
}

if (
    mb_strlen($nome) > 100 ||
    mb_strlen($tipo) > 20 ||
    mb_strlen($local) > 100 ||
    mb_strlen($status) > 20
) {
    responder('error', 'Um ou mais campos excedem o tamanho permitido.', 400);
}

$tiposPermitidos = [
    'Velocidade',
    'Localizacao',
    'Energia',
    'Temperatura',
    'Presenca',
    'Falha'
];

if (!in_array($tipo, $tiposPermitidos, true)) {
    responder('error', 'Tipo de sensor inválido.', 400);
}

if (!in_array($status, ['Ativo', 'Inativo'], true)) {
    responder('error', 'Status inválido.', 400);
}

$tremId = null;

if ($tremIdRecebido !== '') {
    if (
        !ctype_digit($tremIdRecebido) ||
        (int)$tremIdRecebido < 1
    ) {
        responder('error', 'Selecione um trem válido.', 400);
    }

    $tremId = (int)$tremIdRecebido;
}

try {
    // Confere se o trem selecionado realmente existe.
    if ($tremId !== null) {
        $sqlVerificar = "SELECT id FROM trens WHERE id = ?";
        $stmtVerificar = $conn->prepare($sqlVerificar);

        if (!$stmtVerificar) {
            throw new RuntimeException('Falha ao verificar o trem.');
        }

        $stmtVerificar->bind_param('i', $tremId);
        $stmtVerificar->execute();
        $stmtVerificar->store_result();

        $tremExiste = $stmtVerificar->num_rows > 0;
        $stmtVerificar->close();

        if (!$tremExiste) {
            responder('error', 'O trem selecionado não existe.', 400);
        }
    }

    $sql = "INSERT INTO sensores
            (nome, tipo, localizacao, trem_id, status)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Falha ao preparar o cadastro.');
    }

    $stmt->bind_param(
        'sssis',
        $nome,
        $tipo,
        $local,
        $tremId,
        $status
    );

    $stmt->execute();
    $stmt->close();

    responder('success', 'Sensor cadastrado com sucesso!', 201);

} catch (Throwable $e) {
    error_log($e->getMessage());

    responder(
        'error',
        'Erro ao cadastrar sensor. Verifique os dados e o banco.',
        500
    );

} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>
