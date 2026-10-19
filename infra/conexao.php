
<?php

$host = "localhost";
$dbname = "MockingRail";
$port = 3306;
$user = "root";
$password = "";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $dbname,
    $port
);

if ($conn->connect_error) {
    error_log($conn->connect_error);
    die("Erro ao conectar com o banco de dados.");
}

if (!$conn->set_charset("utf8mb4")) {
    error_log($conn->error);
    die("Erro ao configurar os caracteres do banco.");
}

?>
