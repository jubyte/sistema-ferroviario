<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

$id = $_POST['id'] ?? '';

if ($id === '') {

    echo json_encode([
        "status" => "error",
        "message" => "ID do usuário não informado."
    ]);

    exit;
}

try {

    $sql = "DELETE FROM usuarios WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    if ($stmt->rowCount() > 0) {

        echo json_encode([
            "status" => "success",
            "message" => "Usuário removido com sucesso!"
        ]);

    } else {

        echo json_encode([
            "status" => "error",
            "message" => "Usuário não encontrado."
        ]);
    }

} catch (PDOException $e) {

    error_log($e->getMessage());

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao excluir usuário."
    ]);
}

?>