<?php
session_start();
include "../../../infra/conexao.php";
include "../../../infra/funcoes.php";

verificarAdmin("../usuarios.php");

$id = (int) ($_POST["id"] ?? 0);

$sql = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    voltar("../usuarios.php", "Usuário excluído com sucesso!");
} else {
    voltar("../usuarios.php", "Erro ao excluir usuário.");
}
?>
