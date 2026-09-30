<?php

require_once '../../../infra/conexao.php';

try {

    $sql = "SELECT * FROM usuarios ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "usuarios" => $usuarios
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}