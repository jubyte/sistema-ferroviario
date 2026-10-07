<?php
header('Content-Type: application/json');
require_once '../../../infra/conexao.php';

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'ID do sensor não informado.']);
    exit;
}

try {


    $sql = "DELETE FROM sensores WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);


    if ($stmt->rowCount() > 0) {

        echo json_encode([
            'status' => 'success',
            'message' => 'Sensor excluído com sucesso!'
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Sensor não encontrado.'
        ]);

    }

} catch (PDOException $e) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao excluir sensor: ' . $e->getMessage()
    ]);

}

?>