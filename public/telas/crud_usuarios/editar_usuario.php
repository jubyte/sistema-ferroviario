
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

// Aceitar somente requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder('error', 'Método de requisição inválido.', 405);
}

// Receber os dados
$id = trim($_POST['id'] ?? '');
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');

// Validar campos obrigatórios
if ($id === '' || $nome === '' || $email === '' || $telefone === '') {
    responder('error', 'Preencha todos os campos.', 400);
}

// Validar ID
if (!ctype_digit($id) || (int) $id < 1) {
    responder('error', 'Identificador de usuário inválido.', 400);
}

$id = (int) $id;

// Validar nome
if (!preg_match('/^[\p{L}\s]+$/u', $nome)) {
    responder('error', 'O nome deve conter apenas letras e espaços.', 400);
}

if (mb_strlen($nome) > 100) {
    responder('error', 'O nome deve ter no máximo 100 caracteres.', 400);
}

// Validar e-mail
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
    responder('error', 'Digite um e-mail válido.', 400);
}

// Validar telefone
if (
    !preg_match('/^[0-9()\s+\-]+$/', $telefone) ||
    strlen($telefone) > 20
) {
    responder('error', 'Digite um telefone válido.', 400);
}

try {

    // Verificar se o usuário existe
    $sqlVerificar = "SELECT id FROM usuarios WHERE id = ?";

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
        responder('error', 'Usuário não encontrado.', 404);
    }

    // Atualizar os dados com Prepared Statement
    $sql = "UPDATE usuarios
            SET nome = ?, email = ?, telefone = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Falha ao preparar a atualização.');
    }

    $stmt->bind_param(
        'sssi',
        $nome,
        $email,
        $telefone,
        $id
    );

    $stmt->execute();
    $stmt->close();

    responder('success', 'Usuário atualizado com sucesso!');

} catch (mysqli_sql_exception $e) {

    error_log($e->getMessage());

    // Código MySQL 1062: valor duplicado em coluna UNIQUE
    if ((int) $e->getCode() === 1062) {
        responder('error', 'Este e-mail já está cadastrado.', 409);
    }

    responder('error', 'Erro ao editar usuário.', 500);

} catch (Throwable $e) {

    error_log($e->getMessage());

    responder('error', 'Erro ao editar usuário.', 500);
}
?>
