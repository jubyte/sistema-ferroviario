
<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "mockingrail";
$port = 3306;

$conn = new mysqli($servidor, $usuario, $senha, $banco, $port);

if ($conn->connect_error) {
    die("Erro ao conectar com o banco de dados.");
}

$conn->set_charset("utf8mb4");
?>