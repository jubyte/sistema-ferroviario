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


     <section class="area-central">

        <div class="grafico-painel">

            <h2>VELOCIDADE MÉDIA - HOJE</h2>

            <div class="grafico">

                <div class="eixo-y">
                    <span>100</span>
                    <span>80</span>
                    <span>60</span>
                    <span>40</span>
                    <span>20</span>
                    <span>0</span>
                </div>

                <div class="area-grafico">

                    <div class="legenda-grafico">
                        <span></span>
                        Trem Dourado - Distrito 1
                    </div>

                    <svg viewBox="0 0 700 300">

                        <line x1="0" y1="20" x2="700" y2="20"></line>
                        <line x1="0" y1="75" x2="700" y2="75"></line>
                        <line x1="0" y1="130" x2="700" y2="130"></line>
                        <line x1="0" y1="185" x2="700" y2="185"></line>
                        <line x1="0" y1="240" x2="700" y2="240"></line>
                        <line x1="0" y1="295" x2="700" y2="295"></line>

                        <polyline
                            points="55,105 205,65 355,48 505,85 650,55"
                            fill="none"
                            stroke="#9bc8ff"
                            stroke-width="3">
                        </polyline>

                        <circle cx="55" cy="105" r="4"></circle>
                        <circle cx="205" cy="65" r="4"></circle>
                        <circle cx="355" cy="48" r="4"></circle>
                        <circle cx="505" cy="85" r="4"></circle>
                        <circle cx="650" cy="55" r="4"></circle>

                    </svg>

                    <div class="eixo-x">
                        <span>08h</span>
                        <span>09h</span>
                        <span>10h</span>
                        <span>11h</span>
                        <span>12h</span>
                    </div>

                </div>

            </div>

        </div>


        <div class="status-painel">

            <h2>STATUS</h2>

            <div class="status-trem">

                <strong>Trem Dourado</strong>
                <span>Linha Distrito 1</span>

                <div class="status operacional">
                    <i></i>
                    OPERACIONAL
                </div>

            </div>


            <div class="status-trem">

                <strong>Trem Carvão</strong>
                <span>Linha Distrito 12</span>

                <div class="status falha">
                    <i></i>
                    FALHA
                </div>

            </div>

        </div>

    </section>


        <section class="alertas-painel">

        <div class="titulo-alertas">

            <i class="bi bi-exclamation-triangle"></i>

            <h2>ALERTAS RECENTES</h2>

        </div>


        <div class="alerta">

            <span class="bolinha critica"></span>

            <p>
                Temperatura crítica no Trem carvão (102°C)
            </p>

        </div>


        <div class="alerta">

            <span class="bolinha critica"></span>

            <p>
                Velocidade acima do limite - Trem Madeira (142 km/h)
            </p>

        </div>


        <div class="alerta">

            <span class="bolinha aviso"></span>

            <p>
                Sensor SNS-F02 reportou falha na junção Distrito 7 - Distrito 12
            </p>

        </div>


        <div class="alerta">

            <span class="bolinha aviso"></span>

            <p>
                Consumo 18% acima da média - Trem Madeira
            </p>

        </div>

    </section>

</main>
</body>
</html>