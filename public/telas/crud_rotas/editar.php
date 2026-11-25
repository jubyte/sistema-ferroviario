<?php
session_start();
include "../../../infra/conexao.php";
include "../../../infra/funcoes.php";

verificarAdmin("../rotas.php");

$id = (int) ($_POST["id"] ?? 0);
$nome = trim($_POST["nome"] ?? "");
$cepInicial = trim($_POST["cep_inicial"] ?? "");
$cepFinal = trim($_POST["cep_final"] ?? "");

$voltarPara = "../rotas.php?editar=" . $id;

if ($nome == "" || $cepInicial == "" || $cepFinal == "") {
    voltar($voltarPara, "Preencha o nome e os CEPs de início e fim.");
}

$enderecoInicial = buscarCep($cepInicial);
$enderecoFinal = buscarCep($cepFinal);

if ($enderecoInicial == null) {
    voltar($voltarPara, "CEP de início não encontrado.");
}

if ($enderecoFinal == null) {
    voltar($voltarPara, "CEP de fim não encontrado.");
}

$inicio = montarEndereco($enderecoInicial);
$fim = montarEndereco($enderecoFinal);

$sql = "UPDATE rotas SET nome = ?, localizacao_inicial = ?, localizacao_final = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nome, $inicio, $fim, $id);

if ($stmt->execute()) {
    voltar("../rotas.php", "Rota editada com sucesso!");
} else {
    voltar($voltarPara, "Erro ao editar rota.");
}
?>
