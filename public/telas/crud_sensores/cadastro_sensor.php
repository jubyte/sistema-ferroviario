<?php
header('Content-Type: application/json');
require_once '../../infra/conexao.php';

$nome = $_POST['nome'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$local = $_POST['local'] ?? '';
$status = $_POST['status'] ?? '';

if (empty($nome) || empty($tipo) || empty($local) || empty($status)) {
    echo json_encode(['status' => 'error', 'message' => 'Preencha todos os campos.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO sensores (nome, tipo, local, status) VALUES (:nome, :tipo, :local, :status)");
    $stmt->execute([
        ':nome' => $nome,
        ':tipo' => $tipo,
        ':local' => $local,
        ':status' => $status
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Sensor cadastrado com sucesso!']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>