
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
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');

if ($nome === '' || $email === '' || $telefone === '' || $tipo === '') {
    responder('error', 'Preencha todos os campos.', 400);
}

if (!preg_match('/^[\p{L}\s]+$/u', $nome)) {
    responder('error', 'O nome deve conter apenas letras e espaços.', 400);
}

if (mb_strlen($nome) > 100) {
    responder('error', 'O nome deve ter no máximo 100 caracteres.', 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
    responder('error', 'Digite um e-mail válido.', 400);
}

if (
    !preg_match('/^[0-9()\s+\-]+$/', $telefone) ||
    strlen($telefone) > 20
) {
    responder('error', 'Digite um telefone válido.', 400);
}

$tiposPermitidos = [
    'Administrador',
    'Usuário'
];

if (!in_array($tipo, $tiposPermitidos, true)) {
    responder('error', 'Tipo de usuário inválido.', 400);
}

try {

    $sql = "INSERT INTO usuarios
            (nome, email, telefone, tipo, status)
            VALUES (?, ?, ?, ?, 'Ativo')";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Falha ao preparar o cadastro.');
    }

    $stmt->bind_param(
        'ssss',
        $nome,
        $email,
        $telefone,
        $tipo
    );

    if (!$stmt->execute()) {
        throw new RuntimeException('Falha ao executar o cadastro.');
    }

    $stmt->close();

    responder('success', 'Usuário cadastrado com sucesso!', 201);

} catch (mysqli_sql_exception $e) {

    error_log($e->getMessage());

    if ((int) $e->getCode() === 1062) {
        responder('error', 'Este e-mail já está cadastrado.', 409);
    }

    responder('error', 'Erro ao cadastrar usuário.', 500);

} catch (Throwable $e) {

    error_log($e->getMessage());

    responder('error', 'Erro ao cadastrar usuário.', 500);
}
?>
