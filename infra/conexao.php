<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "mockingrail";
$port = 3309;

$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Erro ao conectar com o banco de dados.");
}

$conn->set_charset("utf8mb4");
?>