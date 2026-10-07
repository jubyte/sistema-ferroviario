<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../../infra/conexao.php';

$id = $_POST['id'] ?? null;

if (!$id) {

    echo json_encode([
        'status' => 'error',
        'message' => 'ID do trem não informado.'
    ]);

    exit;
}

try {

    $sql = "DELETE FROM trens WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    if ($stmt->rowCount() > 0) {

        echo json_encode([
            'status' => 'success',
            'message' => 'Trem excluído com sucesso!'
        ]);

    } else {

        echo json_encode([
            'status' => 'error',
            'message' => 'Trem não encontrado.'
        ]);

    }

} catch (PDOException $e) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao excluir trem: ' . $e->getMessage()
    ]);

}