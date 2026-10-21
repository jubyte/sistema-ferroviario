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
$empresa = trim($_POST['empresa'] ?? '');
$numeroVagoes = filter_var(
    $_POST['numero_vagoes'] ?? null,
    FILTER_VALIDATE_INT
);
$tipo = trim($_POST['tipo'] ?? '');
$responsavelId = filter_var(
    $_POST['responsavel_id'] ?? null,
    FILTER_VALIDATE_INT
);

if (
    $nome === '' ||
    $empresa === '' ||
    $tipo === '' ||
    $numeroVagoes === false ||
    $numeroVagoes === null ||
    $responsavelId === false ||
    $responsavelId === null ||
    $responsavelId < 1
) {
    responder('error', 'Preencha todos os campos corretamente.', 400);
}

if (mb_strlen($nome) > 100 || mb_strlen($empresa) > 100) {
    responder('error', 'Nome e empresa devem ter no máximo 100 caracteres.', 400);
}

if ($numeroVagoes < 1) {
    responder('error', 'O número de vagões deve ser maior que zero.', 400);
}

if (!in_array($tipo, ['Eletrico', 'Diesel'], true)) {
    responder('error', 'Tipo de trem inválido.', 400);
}

try {
    $sql = "INSERT INTO trens
            (nome, empresa, tipo, numero_vagoes, responsavel_id)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Falha ao preparar o cadastro.');
    }

    $stmt->bind_param(
        'sssii',
        $nome,
        $empresa,
        $tipo,
        $numeroVagoes,
        $responsavelId
    );

    $stmt->execute();
    $stmt->close();

    responder('success', 'Trem cadastrado com sucesso!', 201);

} catch (Throwable $e) {
    error_log($e->getMessage());
    responder('error', 'Erro ao cadastrar trem. Verifique o responsável selecionado.', 500);

} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>