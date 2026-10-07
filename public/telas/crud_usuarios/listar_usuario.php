<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../infra/conexao.php';

try {

    $busca = trim($_GET['busca'] ?? '');

    if ($busca !== '') {

        if (is_numeric($busca)) {

            $sql = "SELECT id, nome, email, telefone, tipo, status
                    FROM usuarios
                    WHERE nome LIKE :busca
                    OR id = :id
                    ORDER BY id ASC";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':busca' => "%$busca%",
                ':id' => (int)$busca
            ]);

        } else {

            $sql = "SELECT id, nome, email, telefone, tipo, status
                    FROM usuarios
                    WHERE nome LIKE :busca
                    ORDER BY id ASC";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':busca' => "%$busca%"
            ]);
        }

    } else {

        $stmt = $pdo->query(
            "SELECT id, nome, email, telefone, tipo, status
             FROM usuarios
             ORDER BY id ASC"
        );
    }

    $usuarios = $stmt->fetchAll();

    echo json_encode([
        "status" => "success",
        "usuarios" => $usuarios
    ]);

} catch (PDOException $e) {

    error_log($e->getMessage());

    echo json_encode([
        "status" => "error",
        "message" => "Erro ao carregar usuários."
    ]);
}

?>