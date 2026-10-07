<?php
header('Content-Type: application/json');
require_once '../../../infra/conexao.php';

try {

    $sql = "SELECT id, nome, tipo, local, status
            FROM sensores
            ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sensores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'sensores' => $sensores
    ]);

} catch (PDOException $e) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao listar sensores.'
    ]);

}

?>