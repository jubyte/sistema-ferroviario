<?php
session_start();
include "../../../infra/conexao.php";
include "../../../infra/funcoes.php";

verificarAdmin("../sensores.php");

$id = (int) ($_POST["id"] ?? 0);

$sql = "DELETE FROM sensores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    voltar("../sensores.php", "Sensor excluído com sucesso!");
} else {
    voltar("../sensores.php", "Erro ao excluir sensor.");
}
?>
