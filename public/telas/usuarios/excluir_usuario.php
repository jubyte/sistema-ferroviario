<?php
header('Content-Type: application/json');
require_once 'conexao.php';

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'ID não informado.']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode(['status' => 'success', 'message' => 'Usuário removido com sucesso!']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>