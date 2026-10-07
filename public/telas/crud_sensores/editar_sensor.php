<?php
header('Content-Type: application/json');
require_once '../../../infra/conexao.php';

$id = $_POST['id'] ?? null;
$nome = trim($_POST['nome'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$local = trim($_POST['local'] ?? '');
$status = trim($_POST['status'] ?? '');
 
if (!$id || $nome === '' || $tipo === '' || $local === '' || $status === '') {
 
    echo json_encode([
        'status' => 'error', 
        'message' => 'Preencha todos os campos.'
        ]);

    exit;
}

try {
    $sql = "UPDATE sensores SET nome = :nome, tipo = :tipo, local = :local, status = :status WHERE id = :id";
    $stmt = $pdo->prepare($stmt);

    $stmt->execute([
        ':nome' => $nome,
        ':tipo' => $tipo,
        ':local' => $local,
        ':status' => $status,
        ':id' => $id
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Sensor atualizado com sucesso!'
    ]);

} catch (PDOException $e) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao editar sensor: '
    ]);

}

?>