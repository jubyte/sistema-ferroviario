
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
    responder('error', 'Método de requisição inválido.', 405);
}

verificarPermissaoAdmin();

$id = trim($_POST['id'] ?? '');
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$cep = trim($_POST['cep'] ?? '');
$logradouro = trim($_POST['logradouro'] ?? '');
$bairro = trim($_POST['bairro'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$estado = trim($_POST['estado'] ?? '');

if ($id === '' || $nome === '' || $email === '' || $telefone === '') {
    responder('error', 'Preencha todos os campos.', 400);
}

if ($cep !== '' && !preg_match('/^\d{5}-\d{3}$/', $cep)) {
    responder('error', 'CEP inválido. Informe um CEP no formato 00000-000.', 400);
}

if (!ctype_digit($id) || (int) $id < 1) {
    responder('error', 'Identificador de usuário inválido.', 400);
}

$id = (int) $id;

if (!preg_match('/^[\p{L}\s]+$/u', $nome) || mb_strlen($nome) > 100) {
    responder('error', 'Digite um nome válido, limite de 100 caracteres.', 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
    responder('error', 'Digite um e-mail válido, limite de 100 caracteres.', 400);
}

if (!preg_match('/^[0-9()\s+\-]+$/', $telefone) || strlen($telefone) > 20) {
    responder('error', 'Digite um telefone válido, limite de 20 caracteres.', 400);
}

try {
    $sqlVerificar = "SELECT id FROM usuarios WHERE id = ?";

    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bind_param('i', $id);
    $stmtVerificar->execute();
    $stmtVerificar->store_result();

    $existe = $stmtVerificar->num_rows > 0;

    $stmtVerificar->close();

    if (!$existe) {
        responder('error', 'Usuário não encontrado.', 404);
    }

    $cep = $cep !== '' ? $cep : null;
    $logradouro = $logradouro !== '' ? $logradouro : null;
    $bairro = $bairro !== '' ? $bairro : null;
    $cidade = $cidade !== '' ? $cidade : null;
    $estado = $estado !== '' ? $estado : null;

    $sql = "UPDATE usuarios
            SET nome = ?, email = ?, telefone = ?, cep = ?, logradouro = ?, bairro = ?, cidade = ?, estado = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssi', $nome, $email, $telefone, $cep, $logradouro, $bairro, $cidade, $estado, $id);
    $stmt->execute();
    $stmt->close();

    responder('success', 'Usuário atualizado com sucesso!');

} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());

    if ((int) $e->getCode() === 1062) {
        responder('error', 'Este e-mail já está cadastrado.', 409);
    }

    responder('error', 'Erro ao editar usuário.', 500);
}
?>
