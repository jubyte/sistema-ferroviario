<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit;
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/style.css">
    <link rel="shortcut icon" type="imagex/png" href="../../assets/icons/logo_site.png">
    <link href="https://fonts.googleapis.com/css2?family=Science+Gothic:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Menu | MockingRail</title>
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

            <a href="menu.php" class="menu-item active">
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
        <span>SAIR</span> </a>

    </aside> 

    <main class="conteudo">

        <section class="cabecalho">
            <h1 class="titulo-menu">MENU</h1>
            <h2 class="titulo-sistema">SISTEMA FERROVIÁRIO</h2>
        </section>

    <script src="../../script/usuarios.js"></script>

</body>
</html>