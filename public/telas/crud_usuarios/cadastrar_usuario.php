<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../infra/conexao.php';

function responder($status, $mensagem, $codigo = 200)
{
    http_response_code($codigo);

    echo json_encode([
        'status' => $status,
        'message' => $mensagem
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
$senha = $_POST['senha'] ?? ''; // sem trim: espaços podem fazer parte da senha

if ($nome === '' || $email === '' || $telefone === '' || $tipo === '' || $senha === '') {
    responder('error', 'Preencha todos os campos.', 400);
}

if (!preg_match('/^[\p{L}\s]+$/u', $nome) || mb_strlen($nome) > 100) {
    responder('error', 'Digite um nome válido, limite de caracteres.', 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
    responder('error', 'Digite um e-mail válido, limite de caracteres.', 400);
}

if (!preg_match('/^[0-9()\s+\-]+$/', $telefone) || strlen($telefone) > 20) {
    responder('error', 'Digite um telefone válido, limite de caracteres.', 400);
}

// bcrypt considera apenas os primeiros 72 bytes
if (strlen($senha) < 8 || strlen($senha) > 72) {
    responder('error', 'A senha deve ter entre 8 e 72 caracteres.', 400);
}

$tiposPermitidos = ['Administrador', 'Usuário'];

if (!in_array($tipo, $tiposPermitidos, true)) {
    responder('error', 'Tipo de usuário inválido.', 400);
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

try {
    $sql = "INSERT INTO usuarios (nome, email, telefone, senha, tipo, status) VALUES (?, ?, ?, ?, ?, 'Ativo')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $nome, $email, $telefone, $senhaHash, $tipo);
    $stmt->execute();
    $stmt->close();

    responder('success', 'Usuário cadastrado com sucesso!', 201);

} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());

    if ((int) $e->getCode() === 1062) {
        responder('error', 'Este e-mail já está cadastrado.', 409);
    }

    responder('error', 'Erro ao cadastrar usuário.', 500);
}
?>