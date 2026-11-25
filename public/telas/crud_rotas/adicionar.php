<?php
session_start();
include "../../../infra/conexao.php";
include "../../../infra/funcoes.php";

verificarAdmin("../rotas.php");

$nome = trim($_POST["nome"] ?? "");
$cepInicial = trim($_POST["cep_inicial"] ?? "");
$cepFinal = trim($_POST["cep_final"] ?? "");

if ($nome == "" || $cepInicial == "" || $cepFinal == "") {
    voltar("../rotas.php", "Preencha o nome e os CEPs de início e fim.");
}

$enderecoInicial = buscarCep($cepInicial);
$enderecoFinal = buscarCep($cepFinal);

if ($enderecoInicial == null) {
    voltar("../rotas.php", "CEP de início não encontrado.");
}

if ($enderecoFinal == null) {
    voltar("../rotas.php", "CEP de fim não encontrado.");
}

$inicio = montarEndereco($enderecoInicial);
$fim = montarEndereco($enderecoFinal);

$sql = "INSERT INTO rotas (nome, localizacao_inicial, localizacao_final) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $inicio, $fim);

if ($stmt->execute()) {
    voltar("../rotas.php", "Rota cadastrada com sucesso!");
} else {
    voltar("../rotas.php", "Erro ao cadastrar rota.");
}
?>
