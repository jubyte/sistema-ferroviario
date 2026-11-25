<?php
session_start();
include "../../../infra/conexao.php";
include "../../../infra/funcoes.php";

verificarAdmin("../trens.php");

$id = (int) ($_POST["id"] ?? 0);
$voltarPara = "../trens.php?editar=" . $id;

$nome = trim($_POST["nome"] ?? "");
$empresa = trim($_POST["empresa"] ?? "");
$vagoes = (int) ($_POST["numero_vagoes"] ?? 0);
$tipo = $_POST["tipo"] ?? "";
$responsavel = (int) ($_POST["responsavel_id"] ?? 0);

if ($nome == "" || $empresa == "") {
    voltar($voltarPara, "Digite o nome e a empresa do trem.");
}

if ($vagoes < 1) {
    voltar($voltarPara, "O número de vagões deve ser maior que zero.");
}

if ($tipo != "Eletrico" && $tipo != "Diesel") {
    voltar($voltarPara, "Selecione um tipo de trem válido.");
}

if ($responsavel <= 0) {
    voltar($voltarPara, "Selecione o responsável pelo trem.");
}

$sql = "UPDATE trens SET nome = ?, empresa = ?, numero_vagoes = ?, tipo = ?, responsavel_id = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisii", $nome, $empresa, $vagoes, $tipo, $responsavel, $id);

if ($stmt->execute()) {
    voltar("../trens.php", "Trem atualizado com sucesso!");
} else {
    voltar($voltarPara, "Erro ao atualizar o trem.");
}
?>
