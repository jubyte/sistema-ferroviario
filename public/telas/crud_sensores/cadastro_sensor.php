<?php
header('Content-Type: application/json');
require_once '../../../infra/conexao.php';

$nome = trim($_POST['nome'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$local = trim($_POST['local'] ?? '');
$status = trim($_POST['status'] ?? '');

if ($nome === '' || $tipo === '' || $local === '' || $status === '') {

    echo json_encode(['status' => 'error', 'message' => 'Preencha todos os campos.']);
    exit;
}

try {

    $stmt = $pdo->prepare("INSERT INTO sensores (nome, tipo, local, status) VALUES (:nome, :tipo, :local, :status)");
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':tipo' => $tipo,
        ':local' => $local,
        ':status' => $status
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Sensor cadastrado com sucesso!']);
} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        echo json_encode(['status' => 'error', 'message' => 'Este sensor já está cadastrado.']);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar sensor: ' . $e->getMessage()]);
    }
}

?>