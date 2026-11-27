<?php
session_start();
include "../../../infra/conexao.php";
include "../../../infra/funcoes.php";

verificarAdmin("../rotas.php");

$id = (int) ($_POST["id"] ?? 0);

$sql = "DELETE FROM rotas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    voltar("../rotas.php", "Rota excluída com sucesso!");
} else {
    voltar("../rotas.php", "Não foi possível excluir a rota.");
}
?>