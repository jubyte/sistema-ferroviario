<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
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

function verificarPermissaoAdmin()
{
    if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'Administrador') {
        responder('error', 'Você não tem permissão para realizar tal ação', 403);
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder('error', 'Método de requisição inválido.', 405);
}

verificarPermissaoAdmin();

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$cep = trim($_POST['cep'] ?? '');
$logradouro = trim($_POST['logradouro'] ?? '');
$bairro = trim($_POST['bairro'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$estado = trim($_POST['estado'] ?? '');
$senha = $_POST['senha'] ?? ''; // sem trim: espaços podem fazer parte da senha

if ($nome === '' || $email === '' || $telefone === '' || $tipo === '' || $senha === '') {
    responder('error', 'Preencha todos os campos.', 400);
}

if ($cep !== '' && !preg_match('/^\d{5}-\d{3}$/', $cep)) {
    responder('error', 'CEP inválido. Informe um CEP no formato 00000-000.', 400);
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

$cep = $cep !== '' ? $cep : null;
$logradouro = $logradouro !== '' ? $logradouro : null;
$bairro = $bairro !== '' ? $bairro : null;
$cidade = $cidade !== '' ? $cidade : null;
$estado = $estado !== '' ? $estado : null;

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

try {
    $sql = "INSERT INTO usuarios (nome, email, telefone, cep, logradouro, bairro, cidade, estado, senha, tipo, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Ativo')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssss', $nome, $email, $telefone, $cep, $logradouro, $bairro, $cidade, $estado, $senhaHash, $tipo);
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