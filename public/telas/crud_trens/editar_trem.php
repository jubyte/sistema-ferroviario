<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

function responder($status, $message, $codigo = 200) {
    http_response_code($codigo);

    echo json_encode([
        'status' => $status,
        'message' => $message
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// Aceita somente requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder('error', 'Método de requisição inválido.', 405);
}

// Recebe os dados enviados pelo JavaScript
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nome = trim($_POST['nome'] ?? '');
$empresa = trim($_POST['empresa'] ?? '');
$numeroVagoes = filter_input(INPUT_POST, 'numero_vagoes', FILTER_VALIDATE_INT);
$tipo = trim($_POST['tipo'] ?? '');

// Valida o ID
if (!$id || $id < 1) {
    responder('error', 'ID do trem inválido.', 400);
}

// Valida nome e empresa
if ($nome === '' || $empresa === '') {
    responder('error', 'Preencha o nome e a empresa.', 400);
}

if (mb_strlen($nome) > 100 || mb_strlen($empresa) > 100) {
    responder('error', 'Nome e empresa devem ter no máximo 100 caracteres.', 400);
}

// Valida o número de vagões
if (
    $numeroVagoes === false ||
    $numeroVagoes === null ||
    $numeroVagoes < 1
) {
    responder('error', 'O número de vagões deve ser maior que zero.', 400);
}

// Valida o tipo
$tiposPermitidos = ['Eletrico', 'Diesel'];

if (!in_array($tipo, $tiposPermitidos, true)) {
    responder('error', 'Selecione um tipo de trem válido.', 400);
}

try {

    // Atualiza os dados do trem
    $sql = "UPDATE trens
            SET nome = ?,
                empresa = ?,
                numero_vagoes = ?,
                tipo = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Erro ao preparar a atualização.');
    }

    $stmt->bind_param(
        'ssisi',
        $nome,
        $empresa,
        $numeroVagoes,
        $tipo,
        $id
    );

    if (!$stmt->execute()) {
        throw new RuntimeException('Erro ao executar a atualização.');
    }

    // Confere se o trem existe, mesmo se os dados não mudaram
    if ($stmt->affected_rows === 0) {

        $stmt->close();

        $verificar = $conn->prepare(
            "SELECT id FROM trens WHERE id = ?"
        );

        if (!$verificar) {
            throw new RuntimeException('Erro ao verificar o trem.');
        }

        $verificar->bind_param('i', $id);
        $verificar->execute();
        $verificar->store_result();

        $existe = $verificar->num_rows > 0;

        $verificar->close();

        if (!$existe) {
            responder('error', 'Trem não encontrado.', 404);
        }

    } else {
        $stmt->close();
    }

    responder('success', 'Trem atualizado com sucesso!');

} catch (Throwable $e) {

    error_log($e->getMessage());

    responder('error', 'Erro ao atualizar o trem.', 500);

} finally {

    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>

