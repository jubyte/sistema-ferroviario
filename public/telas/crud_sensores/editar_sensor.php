
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

$id = trim($_POST['id'] ?? '');
$nome = trim($_POST['nome'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$local = trim($_POST['local'] ?? '');
$status = trim($_POST['status'] ?? '');

if (
    $id === '' ||
    $nome === '' ||
    $tipo === '' ||
    $local === '' ||
    $status === ''
) {
    responder('error', 'Preencha todos os campos.', 400);
}

if (!ctype_digit($id) || (int) $id < 1) {
    responder('error', 'ID do sensor inválido.', 400);
}

$id = (int) $id;

if (
    mb_strlen($nome) > 100 ||
    mb_strlen($tipo) > 50 ||
    mb_strlen($local) > 150 ||
    mb_strlen($status) > 30
) {
    responder('error', 'Um ou mais campos excedem o tamanho permitido.', 400);
}

try {

    $sqlVerificar = "SELECT id FROM sensores WHERE id = ?";

    $stmtVerificar = $conn->prepare($sqlVerificar);

    if (!$stmtVerificar) {
        throw new RuntimeException('Falha ao preparar a consulta.');
    }

    $stmtVerificar->bind_param('i', $id);
    $stmtVerificar->execute();
    $stmtVerificar->store_result();

    $existe = $stmtVerificar->num_rows > 0;

    $stmtVerificar->close();

    if (!$existe) {
        responder('error', 'Sensor não encontrado.', 404);
    }

    $sql = "UPDATE sensores
            SET nome = ?, tipo = ?, `local` = ?, status = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Falha ao preparar a atualização.');
    }

    $stmt->bind_param(
        'ssssi',
        $nome,
        $tipo,
        $local,
        $status,
        $id
    );

    $stmt->execute();
    $stmt->close();

    responder('success', 'Sensor atualizado com sucesso!');

} catch (mysqli_sql_exception $e) {

    error_log($e->getMessage());

    responder('error', 'Erro ao editar sensor.', 500);

} catch (Throwable $e) {

    error_log($e->getMessage());

    responder('error', 'Erro ao editar sensor.', 500);

} finally {

    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>
