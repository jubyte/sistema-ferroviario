<?php
session_start();
include "../../../infra/conexao.php";
include "../../../infra/funcoes.php";

verificarAdmin("../sensores.php");

$voltarPara = "../sensores.php";

$nome = trim($_POST["nome"] ?? "");
$tipo = $_POST["tipo"] ?? "";
$local = trim($_POST["local"] ?? "");
$status = $_POST["status"] ?? "";
$rota = (int) ($_POST["rota_id"] ?? 0);
$trem = (int) ($_POST["trem_id"] ?? 0);

if ($nome == "" || $tipo == "" || $local == "") {
    voltar($voltarPara, "Preencha o nome, o tipo e a localização do sensor.");
}

$tipos = ["Velocidade", "Localizacao", "Energia", "Temperatura", "Presenca", "Falha"];

if (!in_array($tipo, $tipos)) {
    voltar($voltarPara, "Tipo de sensor inválido.");
}

if ($status != "Ativo" && $status != "Inativo") {
    voltar($voltarPara, "Status inválido.");
}

if ($rota <= 0) {
    voltar($voltarPara, "Selecione a rota do sensor.");
}

if ($trem <= 0) {
    $trem = null;
}

$endereco = buscarCep($local);

if ($endereco != null) {
    $local = montarEndereco($endereco);
}

$local = mb_substr($local, 0, 100);

$sql = "INSERT INTO sensores (nome, tipo, localizacao, trem_id, rota_id, status) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssiis", $nome, $tipo, $local, $trem, $rota, $status);

if ($stmt->execute()) {
    voltar("../sensores.php", "Sensor cadastrado com sucesso!");
} else {
    voltar("../sensores.php", "Erro ao cadastrar sensor.");
}
?>
