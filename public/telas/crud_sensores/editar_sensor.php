
<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
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

function verificarPermissaoAdmin()
{
    if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'Administrador') {
        responder('error', 'Você não tem permissão para realizar tal ação', 403);
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder('error', 'Método inválido.', 405);
}

verificarPermissaoAdmin();

$idTexto = $_POST['id'] ?? '';
$nome = trim($_POST['nome'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$local = trim($_POST['local'] ?? '');
$status = trim($_POST['status'] ?? '');
$tremTexto = trim($_POST['trem_id'] ?? '');
$rotaTexto = trim($_POST['rota_id'] ?? '');

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

if ($rotaTexto === '' || !ctype_digit($rotaTexto) || (int)$rotaTexto < 1) {
    responder('error', 'Selecione uma rota válida.', 400);
}

$rotaId = (int)$rotaTexto;

try {
    $sqlVerificarRota = "SELECT id FROM rotas WHERE id = ?";
    $stmtVerificarRota = $conn->prepare($sqlVerificarRota);

    if (!$stmtVerificarRota) {
        throw new RuntimeException('Falha ao verificar a rota.');
    }

    $stmtVerificarRota->bind_param('i', $rotaId);
    $stmtVerificarRota->execute();
    $stmtVerificarRota->store_result();

    $rotaExiste = $stmtVerificarRota->num_rows > 0;
    $stmtVerificarRota->close();

    if (!$rotaExiste) {
        responder('error', 'A rota selecionada não existe.', 400);
    }

    $sql = "UPDATE sensores
            SET nome = ?, tipo = ?, localizacao = ?,
                trem_id = ?, rota_id = ?, status = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'sssiisi',
        $nome,
        $tipo,
        $local,
        $tremId,
        $rotaId,
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
