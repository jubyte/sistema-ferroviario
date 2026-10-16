
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

if ($nome === '' || $tipo === '' || $local === '' || $status === '') {
    responder('error', 'Preencha todos os campos.', 400);
}

if (
    mb_strlen($nome) > 100 ||
    mb_strlen($tipo) > 50 ||
    mb_strlen($local) > 150 ||
    mb_strlen($status) > 30
) {
    responder('error', 'Um ou mais campos excedem o tamanho permitido.', 400);
}

try {

    $sql = "INSERT INTO sensores (nome, tipo, `local`, status)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Falha ao preparar o cadastro.');
    }

    $stmt->bind_param(
        'ssss',
        $nome,
        $tipo,
        $local,
        $status
    );

    $stmt->execute();
    $stmt->close();

    responder('success', 'Sensor cadastrado com sucesso!', 201);

} catch (mysqli_sql_exception $e) {

    error_log($e->getMessage());

    responder('error', 'Erro ao cadastrar sensor.', 500);

} catch (Throwable $e) {

    error_log($e->getMessage());

    responder('error', 'Erro ao cadastrar sensor.', 500);

} finally {

    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>
