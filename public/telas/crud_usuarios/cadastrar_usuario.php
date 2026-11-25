<?php
session_start();
include "../../../infra/conexao.php";
include "../../../infra/funcoes.php";

verificarAdmin("../usuarios.php");

$voltarPara = "../usuarios.php";

$nome = trim($_POST["nome"] ?? "");
$email = trim($_POST["email"] ?? "");
$telefone = trim($_POST["telefone"] ?? "");
$tipo = $_POST["tipo"] ?? "";
$senha = $_POST["senha"] ?? "";
$cep = trim($_POST["cep"] ?? "");

if ($nome == "" || $email == "" || $telefone == "" || $tipo == "" || $senha == "") {
    voltar($voltarPara, "Preencha todos os campos.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    voltar($voltarPara, "Digite um e-mail válido.");
}

if (strlen($senha) < 8) {
    voltar($voltarPara, "A senha deve ter no mínimo 8 caracteres.");
}

if ($tipo != "Administrador" && $tipo != "Usuário") {
    voltar($voltarPara, "Tipo de usuário inválido.");
}

$logradouro = null;
$bairro = null;
$cidade = null;
$estado = null;

if ($cep == "") {
    $cep = null;
} else {
    $endereco = buscarCep($cep);

    if ($endereco == null) {
        voltar($voltarPara, "CEP não encontrado.");
    }

    $cep = $endereco["cep"];
    $logradouro = $endereco["logradouro"];
    $bairro = $endereco["bairro"];
    $cidade = $endereco["cidade"];
    $estado = $endereco["estado"];
}

$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, email, telefone, cep, logradouro, bairro, cidade, estado, senha, tipo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssss", $nome, $email, $telefone, $cep, $logradouro, $bairro, $cidade, $estado, $senhaCriptografada, $tipo);

if ($stmt->execute()) {
    voltar("../usuarios.php", "Usuário cadastrado com sucesso!");
} else if ($conn->errno == 1062) {
    voltar($voltarPara, "Este e-mail já está cadastrado.");
} else {
    voltar($voltarPara, "Erro ao cadastrar usuário.");
}
?>
