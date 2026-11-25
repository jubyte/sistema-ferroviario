<?php
session_start();
include "../../../infra/conexao.php";
include "../../../infra/funcoes.php";

verificarAdmin("../usuarios.php");

$id = (int) ($_POST["id"] ?? 0);
$voltarPara = "../usuarios.php?editar=" . $id;

$nome = trim($_POST["nome"] ?? "");
$email = trim($_POST["email"] ?? "");
$telefone = trim($_POST["telefone"] ?? "");
$tipo = $_POST["tipo"] ?? "";
$cep = trim($_POST["cep"] ?? "");

if ($nome == "" || $email == "" || $telefone == "" || $tipo == "") {
    voltar($voltarPara, "Preencha todos os campos.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    voltar($voltarPara, "Digite um e-mail válido.");
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

$sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, tipo = ?, cep = ?, logradouro = ?, bairro = ?, cidade = ?, estado = ?
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssi", $nome, $email, $telefone, $tipo, $cep, $logradouro, $bairro, $cidade, $estado, $id);

if ($stmt->execute()) {
    voltar("../usuarios.php", "Usuário atualizado com sucesso!");
} else if ($conn->errno == 1062) {
    voltar($voltarPara, "Este e-mail já está cadastrado.");
} else {
    voltar($voltarPara, "Erro ao editar usuário.");
}
?>
