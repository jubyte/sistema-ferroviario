<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../../infra/conexao.php';

try {

    $sql = "SELECT id, nome, email, telefone, tipo, status
            FROM usuarios
            ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'usuarios' => $usuarios
    ]);

} catch (PDOException $e) {

   echo json_encode([
    'status' => 'error',
    'message' => 'Erro ao carregar usuários.'
]);

}