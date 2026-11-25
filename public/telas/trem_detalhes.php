<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit;
}

include "../../infra/conexao.php";

$id = (int) ($_GET['id'] ?? 0);

if (!$id) {
    header('Location: trens.php');
    exit;
}

$stmt = $conn->prepare(
    "SELECT t.id, t.nome, t.empresa, t.tipo, t.numero_vagoes, t.status,
            u.nome AS responsavel_nome, u.email AS responsavel_email
     FROM trens t
     LEFT JOIN usuarios u ON u.id = t.responsavel_id
     WHERE t.id = ?"
);
$stmt->bind_param('i', $id);
$stmt->execute();
$resultado = $stmt->get_result();
$trem = $resultado->fetch_assoc();
$stmt->close();

if (!$trem) {
    header('Location: trens.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/style.css">
    <link rel="shortcut icon" type="imagex/png" href="../../assets/icons/logo_site.png">
    <link href="https://fonts.googleapis.com/css2?family=Science+Gothic:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Detalhes do trem | MockingRail</title>
</head>
<body>
    <aside class="sidebar">
        <div class="logo-box">
            <h1 class="logo">R<span>Λ</span>IL</h1>
            <div class="logo-line"></div>
        </div>

        <a href="usuario_detalhes.php?id=<?= htmlspecialchars($_SESSION["id"] ?? 0) ?>" class="usuario-box-link">
            <div class="usuario-box">
                <i class="bi bi-person-fill"></i>
                <div class="usuario-info">
                    <span>Usuário</span>
                    <p id="emailusuario"><?= htmlspecialchars($_SESSION["email"]) ?></p>
                </div>
            </div>
        </a>

        <nav class="menu">
            <a href="menu.php" class="menu-item">
                <i class="bi bi-house-door-fill"></i>
                <span>MENU</span>
            </a>

            <a href="rotas.php" class="menu-item">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>ROTAS</span>
            </a>

            <a href="sensores.php" class="menu-item">
                <i class="bi bi-cpu-fill"></i>
                <span>SENSORES</span>
            </a>

            <a href="trens.php" class="menu-item active">
                <i class="bi bi-train-front"></i>
                <span>TRENS</span>
            </a>

            <a href="usuarios.php" class="menu-item">
                <i class="bi bi-people-fill"></i>
                <span>USUÁRIOS</span>
            </a>
        </nav>

        <a href="login.php" class="sair">
            <i class="bi bi-box-arrow-left"></i>
            <span>SAIR</span>
        </a>
    </aside>

    <main class="conteudo">
        <header class="cabecalho">
            <h2>TRENS</h2>
            <h4>SISTEMA FERROVIÁRIO</h4>
        </header>

        <div class="sensor_caixa">
            <h2 class="usuario__title">
                <i class="bi bi-train-front"></i>
                DADOS DO TREM
            </h2>

            <div class="usuario-detalhes-grid">
                <div class="usuario-detalhe-item">
                    <span>ID</span>
                    <strong><?= htmlspecialchars($trem['id']) ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Nome</span>
                    <strong><?= htmlspecialchars($trem['nome']) ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Empresa</span>
                    <strong><?= htmlspecialchars($trem['empresa'] ?? 'Não informado') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Tipo</span>
                    <strong><?= htmlspecialchars($trem['tipo'] ?? 'Não informado') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Nº de vagões</span>
                    <strong><?= htmlspecialchars($trem['numero_vagoes'] ?? 'Não informado') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Responsável</span>
                    <strong><?= htmlspecialchars($trem['responsavel_nome'] ?? 'Não informado') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Status</span>
                    <strong><?= htmlspecialchars($trem['status'] ?? 'Ativo') ?></strong>
                </div>

            </div>

            <div class="mt-4">
                <a href="trens.php" class="btn botao-custom">VOLTAR PARA TRENS</a>
            </div>
        </div>
    </main>
</body>
</html>
