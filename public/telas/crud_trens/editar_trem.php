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

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nome = trim($_POST['nome'] ?? '');
$empresa = trim($_POST['empresa'] ?? '');
$numeroVagoes = filter_input(INPUT_POST, 'numero_vagoes', FILTER_VALIDATE_INT);
$tipo = trim($_POST['tipo'] ?? '');
$responsavelId = filter_var(
    $_POST['responsavel_id'] ?? null,
    FILTER_VALIDATE_INT
);

if (!$id || $id < 1) {
    responder('error', 'ID do trem inválido.', 400);
}

if (
    $nome === '' ||
    $empresa === '' ||
    mb_strlen($nome) > 100 ||
    mb_strlen($empresa) > 100
) {
    responder('error', 'Preencha corretamente o nome e a empresa.', 400);
}

if (
    $numeroVagoes === false ||
    $numeroVagoes === null ||
    $numeroVagoes < 1
) {
    responder('error', 'O número de vagões deve ser maior que zero.', 400);
}

if (!in_array($tipo, ['Eletrico', 'Diesel'], true)) {
    responder('error', 'Selecione um tipo de trem válido.', 400);
}

if (
    $responsavelId === false ||
    $responsavelId === null ||
    $responsavelId < 1
) {
    responder('error', 'Selecione um responsável válido.', 400);
}

try {
    $sql = "UPDATE trens
            SET nome = ?,
                empresa = ?,
                numero_vagoes = ?,
                tipo = ?,
                responsavel_id = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Erro ao preparar a atualização.');
    }

    $stmt->bind_param(
        'ssisii',
        $nome,
        $empresa,
        $numeroVagoes,
        $tipo,
        $responsavelId,
        $id
    );

    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        $stmt->close();

        $verificar = $conn->prepare(
            "SELECT id FROM trens WHERE id = ?"
        );

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
    responder('error', 'Erro ao atualizar o trem. Verifique o responsável selecionado.', 500);

} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>