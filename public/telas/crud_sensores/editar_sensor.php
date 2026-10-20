
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
    responder('error', 'Método inválido.', 405);
}

$idTexto = $_POST['id'] ?? '';
$nome = trim($_POST['nome'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$local = trim($_POST['local'] ?? '');
$status = trim($_POST['status'] ?? '');
$tremTexto = trim($_POST['trem_id'] ?? '');

$tiposPermitidos = [
    'Velocidade', 'Localizacao', 'Energia',
    'Temperatura', 'Presenca', 'Falha'
];

if (!ctype_digit((string)$idTexto) || (int)$idTexto < 1) {
    responder('error', 'ID do sensor inválido.', 400);
}

$id = (int)$idTexto;

if ($nome === '' || $tipo === '' || $local === '') {
    responder('error', 'Preencha todos os campos obrigatórios.', 400);
}

if (mb_strlen($nome) > 100 ||
    mb_strlen($tipo) > 20 ||
    mb_strlen($local) > 100) {
    responder('error', 'Um campo ultrapassou o limite permitido.', 400);
}

if (!in_array($tipo, $tiposPermitidos, true)) {
    responder('error', 'Tipo de sensor inválido.', 400);
}

if (!in_array($status, ['Ativo', 'Inativo'], true)) {
    responder('error', 'Status inválido.', 400);
}

$tremId = null;

if ($tremTexto !== '') {
    if (!ctype_digit($tremTexto) || (int)$tremTexto < 1) {
        responder('error', 'ID do trem inválido.', 400);
    }

    $tremId = (int)$tremTexto;
}

try {
    $sql = "UPDATE sensores
            SET nome = ?, tipo = ?, localizacao = ?,
                trem_id = ?, status = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'sssisi',
        $nome,
        $tipo,
        $local,
        $tremId,
        $status,
        $id
    );

    $stmt->execute();
    $alteradas = $stmt->affected_rows;
    $stmt->close();

    if ($alteradas === 0) {
        $verificar = $conn->prepare(
            "SELECT id FROM sensores WHERE id = ?"
        );
        $verificar->bind_param('i', $id);
        $verificar->execute();
        $verificar->store_result();
        $existe = $verificar->num_rows > 0;
        $verificar->close();

        if (!$existe) {
            responder('error', 'Sensor não encontrado.', 404);
        }
    }

    responder('success', 'Sensor atualizado com sucesso!');

} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    responder('error', 'Não foi possível atualizar o sensor.', 500);
} catch (Throwable $e) {
    error_log($e->getMessage());
    responder('error', 'Erro interno ao editar sensor.', 500);
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>
