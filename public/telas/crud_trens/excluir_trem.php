<?php
session_start();
include "../../../infra/conexao.php";
include "../../../infra/funcoes.php";

verificarAdmin("../trens.php");

$id = (int) ($_POST["id"] ?? 0);

$sql = "DELETE FROM trens WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    voltar("../trens.php", "Trem excluído com sucesso!");
} else {
    voltar("../trens.php", "Não foi possível excluir o trem. Verifique se ele está ligado a alguma rota.");
}
?>
