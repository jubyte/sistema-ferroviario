<?php
header('Content-Type: application/json');
require_once '../../../infra/conexao.php';

$id = $_POST['id'] ?? null;
$nome = trim($_POST['nome'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$local = trim($_POST['local'] ?? '');
$status = trim($_POST['status'] ?? '');
 
if (!$id || $nome === '' || $tipo === '' || $local === '' || $status === '') {
 
    echo json_encode(['status' => 'error', 'message' => 'Preencha todos os campos.']);
    exit;
}

try {
    $stmt = "UPDATE sensores SET nome = :nome, tipo = :tipo, local = :local, status = :status WHERE id = :id";
    $stmt = $pdo->prepare($stmt);

    $stmt->execute([
        ':id' => $id,
        ':nome' => $nome,
        ':tipo' => $tipo,
        ':local' => $local,
        ':status' => $status
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Sensor atualizado com sucesso!'
    ]);

} catch (PDOException $e) {

    if ($e->getCode() == 23000) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Este sensor já está cadastrado para outro usuário.'
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Erro ao editar sensor: ' . $e->getMessage()
        ]);

    }

}
?>