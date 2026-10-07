<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../../infra/conexao.php';

try {

    $sql = "SELECT id, nome, empresa, tipo, numero_vagoes, status
            FROM trens
            ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $trens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'trens' => $trens
    ]);

} catch (PDOException $e) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao carregar trens.'
    ]);

}