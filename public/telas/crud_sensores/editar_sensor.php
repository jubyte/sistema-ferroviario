<?php
header('Content-Type: application/json');
require_once '../../infra/conexao.php';

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$local = $_POST['local'] ?? '';
$status = $_POST['status'] ?? '';

if (!$id || empty($nome) || empty($tipo) || empty($local) || empty($status)) {
    echo json_encode(['status' => 'error', 'message' => 'Dados incompletos para atualização.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE sensores SET nome = :nome, tipo = :tipo, local = :local, status = :status WHERE id = :id");
    $stmt->execute([
        ':id' => $id,
        ':nome' => $nome,
        ':tipo' => $tipo,
        ':local' => $local,
        ':status' => $status
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Sensor atualizado com sucesso!']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>