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
$descricao = trim($_POST['descricao'] ?? '');
$distancia = trim($_POST['distancia'] ?? '');
$tempoEstimado = trim($_POST['tempo_estimado'] ?? '');
$tremId = trim($_POST['trem_id'] ?? '');

if (
    $nome === '' ||
    $descricao === '' ||
    $distancia === '' ||
    $tempoEstimado === '' ||
    $tremId === ''
) {
    responder('error', 'Preencha todos os campos.', 400);
}

if (mb_strlen($nome) > 100) {
    responder('error', 'O nome da rota deve ter no máximo 100 caracteres.', 400);
}

if (mb_strlen($descricao) > 1000) {
    responder('error', 'A descrição deve ter no máximo 1000 caracteres.', 400);
}

if (!is_numeric($distancia) || (float) $distancia <= 0) {
    responder('error', 'Digite uma distância válida.', 400);
}

if (!ctype_digit($tempoEstimado) || (int) $tempoEstimado <= 0) {
    responder('error', 'Digite um tempo válido em minutos.', 400);
}

if (!ctype_digit($tremId) || (int) $tremId < 1) {
    responder('error', 'Informe um ID de trem válido.', 400);
}

$distancia = (float) $distancia;
$tempoEstimado = (int) $tempoEstimado;
$tremId = (int) $tremId;

try {

    $sql = "INSERT INTO rotas
            (nome, descricao, distancia, tempo_estimado, trem_id)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Erro ao preparar o cadastro.');
    }

    $stmt->bind_param(
        'ssdii',
        $nome,
        $descricao,
        $distancia,
        $tempoEstimado,
        $tremId
    );

    $stmt->execute();

    $stmt->close();

    responder(
        'success',
        'Rota cadastrada com sucesso!',
        201
    );

} catch (mysqli_sql_exception $e) {

    error_log($e->getMessage());

    if ((int) $e->getCode() === 1452) {
        responder(
            'error',
            'O trem informado não existe.',
            400
        );
    }

    responder(
        'error',
        'Erro ao cadastrar rota.',
        500
    );

} catch (Throwable $e) {

    error_log($e->getMessage());

    responder(
        'error',
        'Erro ao cadastrar rota.',
        500
    );
}
?>