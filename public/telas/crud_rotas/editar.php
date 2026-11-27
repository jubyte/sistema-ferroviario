<?php
session_start();
include "../../../infra/conexao.php";
include "../../../infra/funcoes.php";

verificarAdmin("../rotas.php");

$id = (int) ($_POST["id"] ?? 0);
$nome = trim($_POST["nome"] ?? "");
$cepInicial = trim($_POST["cep_inicial"] ?? "");
$cepFinal = trim($_POST["cep_final"] ?? "");
$tremId = (int) ($_POST["trem_id"] ?? 0);

$voltarPara = "../rotas.php?editar=" . $id;

if ($nome == "" || $cepInicial == "" || $cepFinal == "" || $tremId <= 0) {
    voltar($voltarPara, "Preencha todos os campos obrigatórios.");
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

$sql = "UPDATE rotas SET nome = ?, localizacao_inicial = ?, localizacao_final = ?, trem_id = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $nome, $inicio, $fim, $tremId, $id);

if ($stmt->execute()) {
    voltar("../rotas.php", "Rota editada com sucesso!");
} else {
    voltar($voltarPara, "Erro ao editar rota.");
}
?>
