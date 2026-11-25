<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit;
}

include "../../infra/conexao.php";

$id = (int) ($_GET['id'] ?? 0);

if (!$id) {
    header('Location: sensores.php');
    exit;
}

$stmt = $conn->prepare(
    "SELECT s.id, s.nome, s.tipo, s.localizacao, s.status, s.data_criacao,
            t.nome AS trem_nome,
            r.nome AS rota_nome
     FROM sensores s
     LEFT JOIN trens t ON t.id = s.trem_id
     LEFT JOIN rotas r ON r.id = s.rota_id
     WHERE s.id = ?"
);
$stmt->bind_param('i', $id);
$stmt->execute();
$resultado = $stmt->get_result();
$sensor = $resultado->fetch_assoc();
$stmt->close();

if (!$sensor) {
    header('Location: sensores.php');
    exit;
}

$dataCadastro = date('d/m/Y H:i', strtotime($sensor['data_criacao']));
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
    <title>Detalhes do sensor | MockingRail</title>
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

            <a href="sensores.php" class="menu-item active">
                <i class="bi bi-cpu-fill"></i>
                <span>SENSORES</span>
            </a>

            <a href="trens.php" class="menu-item">
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
            <h2>SENSORES</h2>
            <h4>SISTEMA FERROVIÁRIO</h4>
        </header>

        <div class="sensor_caixa">
            <h2 class="usuario__title">
                <i class="bi bi-cpu-fill"></i>
                DADOS DO SENSOR
            </h2>

            <div class="usuario-detalhes-grid">
                <div class="usuario-detalhe-item">
                    <span>ID</span>
                    <strong><?= htmlspecialchars($sensor['id']) ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Nome</span>
                    <strong><?= htmlspecialchars($sensor['nome']) ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Tipo</span>
                    <strong><?= htmlspecialchars($sensor['tipo'] ?? 'Não informado') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Localização</span>
                    <strong><?= htmlspecialchars($sensor['localizacao'] ?? 'Não informado') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Trem associado</span>
                    <strong><?= htmlspecialchars($sensor['trem_nome'] ?? 'Não associado') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Rota responsável</span>
                    <strong><?= htmlspecialchars($sensor['rota_nome'] ?? 'Não associada') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Status</span>
                    <strong><?= htmlspecialchars($sensor['status'] ?? 'Ativo') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Data de criação</span>
                    <strong><?= htmlspecialchars($dataCadastro) ?></strong>
                </div>
            </div>

            <div class="mt-4">
                <a href="sensores.php" class="btn botao-custom">VOLTAR PARA SENSORES</a>
            </div>
        </div>
    </main>
</body>
</html>
