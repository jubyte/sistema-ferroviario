<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/style.css">
    <link rel="shortcut icon" type="imagex/png" href="../../assets/icons/logo_site.png">
    <link href="https://fonts.googleapis.com/css2?family=Science+Gothic:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Painel | MockingRail</title>
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
            <p>admin@mockingrail</p>
            </div>

        </div>

       <nav class="menu">

            <a href="menu.php" class="menu-item">
            <i class="bi bi-house-door-fill"></i>
            <span>MENU</span>
            </a>

            <a href="painel.php" class="menu-item active">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>PAINEL</span>
            </a>

            <a href="sensores.php" class="menu-item">
            <i class="bi bi-cpu-fill"></i>
            <span>SENSORES</span>
            </a>

            <a href="monitoramento.php" class="menu-item">
            <i class="bi bi-activity"></i>
            <span>MONITORAMENTO</span>
            </a>

            <a href="relatorios.php" class="menu-item">
            <i class="bi bi-file-earmark-bar-graph-fill"></i>
            <span>RELATÓRIOS</span>
            </a>

            <a href="usuarios.php" class="menu-item">
            <i class="bi bi-people-fill"></i>
            <span>USUÁRIOS</span>
            </a>

        </nav>

        <a href="login.php" class="sair">
        <i class="bi bi-box-arrow-left"></i>
        <span>SAIR</span> </a>

    </aside> 

    <main class="conteudo">

    <section class="cabecalho">
        <h1 class="titulo-menu">PAINEL</h1>
        <h2 class="titulo-sistema">SISTEMA FERROVIÁRIO</h2>
    </section>


    <section class="cards-painel">

        <div class="card-painel">

            <div class="icone-card laranja">
                <i class="bi bi-train-front"></i>
            </div>

            <div class="info-card">
                <h3>TRENS ATIVOS</h3>
                <strong>2/13</strong>
                <span>OPERAÇÃO NORMAL</span>
            </div>

        </div>


        <div class="card-painel">

            <div class="icone-card laranja">
                <i class="bi bi-cpu"></i>
            </div>

            <div class="info-card">
                <h3>SENSORES<br>ONLINE</h3>
                <strong>7</strong>
                <span>DE 9<br>CADASTRADOS</span>
            </div>

        </div>


        <div class="card-painel">

            <div class="icone-card vermelho">
                <i class="bi bi-activity"></i>
            </div>

            <div class="info-card">
                <h3>INTEGRI-<br>DADE</h3>
                <strong class="texto-vermelho">76%</strong>
                <span>STATUS DA<br>REDE</span>
            </div>

        </div>


        <div class="card-painel">

            <div class="icone-card vermelho">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div class="info-card">
                <h3>ALERTAS<br>CRÍTICOS</h3>
                <strong class="texto-vermelho">2</strong>
                <span>REQUER<br>ATENÇÃO</span>
            </div>

        </div>

    </section>

</main>
</body>
</html>