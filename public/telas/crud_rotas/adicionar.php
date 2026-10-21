<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

$nome   = trim($_POST['nome'] ?? '');
$inicio = trim($_POST['localizacao_inicial'] ?? '');
$fim    = trim($_POST['localizacao_final'] ?? '');

if ($nome === '' || $inicio === '' || $fim === '') {
    echo json_encode([
        "status"  => "error",
        "message" => "Preencha todos os campos."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $sql = "INSERT INTO rotas (
                nome,
                localizacao_inicial,
                localizacao_final,
                descricao,
                distancia,
                tempo_estimado
            ) VALUES (?, ?, ?, NULL, NULL, NULL)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $inicio, $fim);
    $stmt->execute();

    echo json_encode([
        "status"  => "success",
        "message" => "Rota cadastrada com sucesso."
    ], JSON_UNESCAPED_UNICODE);

    $stmt->close();

} catch (Throwable $e) {
    error_log("Erro ao cadastrar rota: " . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        "status"  => "error",
        "message" => "Erro ao cadastrar rota."
    ], JSON_UNESCAPED_UNICODE);

} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}