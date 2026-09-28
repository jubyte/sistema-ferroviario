<?php
$host = 'localhost';
$banco = 'mockingrail';
$usuario = 'root';
$senha = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro na conexão com o banco de dados.'
    ]);
    exit;
}
?>