<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "mockingrail";
$porta = 3306;

mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli($servidor, $usuario, $senha, $banco, $porta);

if ($conn->connect_error) {
    die("Erro ao conectar com o banco de dados: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>