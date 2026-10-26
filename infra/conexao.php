<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "mockingrail";
$porta = 3306;

$conn = new mysqli($servidor, $usuario, $senha, $banco, $porta);

if ($conn->connect_error) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Erro ao conectar com o banco de dados.",
        "detalhe" => $conn->connect_error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$conn->set_charset("utf8mb4");
?>