<?php
header('Content-Type: application/json');
require_once 'conexao.php';

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

try {
    if (!empty($busca)) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nome LIKE :busca OR id = :id_exact ORDER BY id ASC");
        $stmt->bindValue(':busca', "%$busca%");
        $stmt->bindValue(':id_exact', is_numeric($busca) ? (int)$busca : 0);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios ORDER BY id ASC");
    }

    $stmt->execute();
    $usuarios = $stmt->fetchAll();

    echo json_encode(['status' => 'success', 'data' => $usuarios]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>