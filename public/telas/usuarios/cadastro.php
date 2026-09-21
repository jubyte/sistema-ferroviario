<?php
header('Content-Type: application/json');
require_once 'conexao.php';

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$tipo = $_POST['tipo'] ?? '';

if (empty($nome) || empty($email) || empty($telefone) || empty($tipo)) {
    echo json_encode(['status' => 'error', 'message' => 'Preencha todos os campos.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, telefone, tipo) VALUES (:nome, :email, :telefone, :tipo)");
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':telefone' => $telefone,
        ':tipo' => $tipo
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Usuário cadastrado com sucesso!']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>