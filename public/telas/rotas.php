<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title>Rotas | MockingRail</title>
</head>

<body>

    <aside class="sidebar">

        <div class="logo-box">
            <h1 class="logo">
                R<span>Λ</span>IL
            </h1>

            <div class="logo-line"></div>
        </div>

        <div class="usuario-box">
            <i class="bi bi-person-fill"></i>

            <div class="usuario-info">
                <span>Usuário</span>
                <p id="emailusuario"><?= htmlspecialchars($_SESSION["email"], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        </div>

        <nav class="menu">

            <a href="menu.php" class="menu-item">
                <i class="bi bi-house-door-fill"></i>
                <span>MENU</span>
            </a>

            <a href="rotas.php" class="menu-item active">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>ROTAS</span>
            </a>

            <a href="sensores.php" class="menu-item">
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
            <h2>ROTAS</h2>
            <h4>SISTEMA FERROVIÁRIO</h4>
        </header>


    
    <div class="sensor_caixa">

        <h2 class="usuario__title">
            <i class="bi bi-plus-circle"></i> CADASTRAR ROTA
        </h2>

        <form id="formRota">
    <div class="row g-4 align-items-end">

        <div class="col">
            <label for="nome" class="label-custom">NOME</label>
            <input type="text" id="nome"
                   class="form-control input-custom"
                   maxlength="100" required>
        </div>

        <div class="col">
            <label for="localizacao-inicial" class="label-custom">
                INÍCIO DA ROTA
            </label>
            <input type="text" id="localizacao-inicial"
                   class="form-control input-custom"
                   maxlength="150" required>
        </div>

        <div class="col">
            <label for="localizacao-final" class="label-custom">
                FIM DA ROTA
            </label>
            <input type="text" id="localizacao-final"
                   class="form-control input-custom"
                   maxlength="150" required>
        </div>

        <div class="col">
            <button type="button" id="btnAdicionar"
                    class="btn botao-custom w-100">
                + ADICIONAR
            </button>
        </div>

    </div>
</form>

    </div>




    <div class="sensor_busca">
        <i class="bi bi-search"></i>
        <input type="text" id="buscar" placeholder="Buscar por nome ou ID">
    </div>
     <div id="sensorVazio" class="sensor-vazio">
        <i class="bi bi-broadcast"></i>
        <h4> Nenhuma rota cadastrada ainda. </h4>
        <p> Cadastre uma nova rota para começar. </p>
    </div>
    <div class="sensor_tabela">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NOME</th>
                    <th>INÍCIO</th>
                    <th>FIM</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody id="tabelaRotas"></tbody>
        </table>
    </div>

</main>

<script src="../../script/rotas.js"></script>

</body>
</html>
