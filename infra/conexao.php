<?php

$host = "localhost";
<<<<<<< HEAD
=======
$port = "3306";
>>>>>>> 139bd490ccd9b966eaf8e017675ca8988e42524e
$dbname = "MockingRail";
$user = "root";
$password = "";

$porta = [3306, 3389, 3307];

$pdo = null;

foreach ($portas as $porta) {

    try {

        $pdo = new PDO(
            "mysql:host=$host;port=$porta;dbname=$dbname;charset=utf8mb4",
            $user,
            $password
        );

        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $pdo->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );

        $pdo->setAttribute(
            PDO::ATTR_EMULATE_PREPARES,
            false
        );

        break;

    } catch (PDOException $e) {

        $pdo = null;
    }
}

if ($pdo === null) {

    die("Não foi possível conectar ao banco de dados.");
}

?>