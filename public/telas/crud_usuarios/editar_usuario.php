<?php
header('Content-Type: application/json');
require_once 'conexao.php';

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';

if (!$id || empty($nome) || empty($email) || empty($telefone)) {
    echo json_encode(['status' => 'error', 'message' => 'Dados incompletos para atualização.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone WHERE id = :id");
    $stmt->execute([
        ':id' => $id,
        ':nome' => $nome,
        ':email' => $email,
        ':telefone' => $telefone
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Usuário atualizado com sucesso!']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>