<?php
session_start();
include "../../../infra/conexao.php";
include "../../../infra/funcoes.php";

verificarAdmin("../rotas.php");

$nome = trim($_POST["nome"] ?? "");
$cepInicial = trim($_POST["cep_inicial"] ?? "");
$cepFinal = trim($_POST["cep_final"] ?? "");
$tremId = (int) ($_POST["trem_id"] ?? 0);

if ($nome == "" || $cepInicial == "" || $cepFinal == "" || $tremId <= 0)  {
    voltar("../rotas.php", "Preencha todos os campos obrigatórios.");
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

$inicio = mb_substr($inicio, 0, 150);
$fim = mb_substr($fim, 0, 150);

$sql = "INSERT INTO rotas (nome, localizacao_inicial, localizacao_final, trem_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nome, $inicio, $fim, $tremId);

if ($stmt->execute()) {
    voltar("../rotas.php", "Rota cadastrada com sucesso!");
} else {
    voltar("../rotas.php", "Erro ao cadastrar rota: " . $stmt->error);
}
?>