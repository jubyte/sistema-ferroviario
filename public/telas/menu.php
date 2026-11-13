<?php
session_start();
require_once __DIR__ . '/../../infra/conexao.php';

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit;
}

$totalTrens = 0;
$trensAtivos = 0;
$totalSensores = 0;
$sensoresOnline = 0;
$sensoresInativos = 0;
$integridade = 0;
$alertasCriticos = 0;
$usuariosAtivos = 0;
$totalUsuarios = 0;

$sqlContagemTrens = "SELECT COUNT(*) AS total_trens,
    SUM(CASE WHEN status = 'Ativo' THEN 1 ELSE 0 END) AS trens_ativos
    FROM trens";

$resultado = $conn->query($sqlContagemTrens);

if ($resultado && $resultado->num_rows > 0) {
    $dadosTrens = $resultado->fetch_assoc();
    $totalTrens = (int) ($dadosTrens['total_trens'] ?? 0);
    $trensAtivos = (int) ($dadosTrens['trens_ativos'] ?? 0);
}

$sqlContagemSensores = "SELECT COUNT(*) AS total_sensores,
    SUM(CASE WHEN status = 'Ativo' THEN 1 ELSE 0 END) AS sensores_online,
    SUM(CASE WHEN status = 'Inativo' THEN 1 ELSE 0 END) AS sensores_inativos
    FROM sensores";

$resultadoSensores = $conn->query($sqlContagemSensores);

if ($resultadoSensores && $resultadoSensores->num_rows > 0) {
    $dadosSensores = $resultadoSensores->fetch_assoc();
    $totalSensores = (int) ($dadosSensores['total_sensores'] ?? 0);
    $sensoresOnline = (int) ($dadosSensores['sensores_online'] ?? 0);
    $sensoresInativos = (int) ($dadosSensores['sensores_inativos'] ?? 0);
}

if ($totalSensores > 0) {
    $integridade = (int) round(($sensoresOnline / $totalSensores) * 100);
}

$alertasCriticos = $sensoresInativos;

$sqlContagemUsuarios = "SELECT COUNT(*) AS total_usuarios,
    SUM(CASE WHEN status = 'Ativo' THEN 1 ELSE 0 END) AS usuarios_ativos
    FROM usuarios";

$resultadoUsuarios = $conn->query($sqlContagemUsuarios);

if ($resultadoUsuarios && $resultadoUsuarios->num_rows > 0) {
    $dadosUsuarios = $resultadoUsuarios->fetch_assoc();
    $totalUsuarios = (int) ($dadosUsuarios['total_usuarios'] ?? 0);
    $usuariosAtivos = (int) ($dadosUsuarios['usuarios_ativos'] ?? 0);
}

$trensAtivosLista = [];
$sqlTrensAtivos = "SELECT nome, tipo, empresa FROM trens WHERE status = 'Ativo' ORDER BY nome ASC";
$resultadoTrensAtivos = $conn->query($sqlTrensAtivos);

if ($resultadoTrensAtivos) {
    while ($linha = $resultadoTrensAtivos->fetch_assoc()) {
        $trensAtivosLista[] = $linha;
    }
}

$conn->close();
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

        <a href="usuario_detalhes.php?id=<?= htmlspecialchars((string) ($_SESSION["id"] ?? 0), ENT_QUOTES, 'UTF-8') ?>" class="usuario-box-link">
            <div class="usuario-box">
                <i class="bi bi-person-fill"></i>

                <div class="usuario-info">
                    <span>Usuário</span>
                    <p id="emailusuario"><?= htmlspecialchars($_SESSION["email"], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            </div>
        </a>

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

        <a href="../../index.php" class="sair">
        <i class="bi bi-box-arrow-left"></i>
        <span>SAIR</span> </a>

    </aside> 

    <main class="conteudo">

        <section class="cabecalho">
            <h1 class="titulo-menu">MENU</h1>
            <h4 class="titulo-sistema">SISTEMA FERROVIÁRIO</h4>
        </section>
        <section class="resumo-superior">
            <div class="cartao-sensores">
                <i class="bi bi-broadcast"></i>
                <div>
                    <h3>Sensores</h3>
                    <div class="informacoes-sensores">
                        <span class="ativos"><?= htmlspecialchars((string) $sensoresOnline, ENT_QUOTES, 'UTF-8') ?> ativos</span>
                        <span class="manutencao"><?= htmlspecialchars((string) $sensoresInativos, ENT_QUOTES, 'UTF-8') ?> em manutenção</span>
                    </div>
                </div>
            </div>
            <div class="cartao-alertas">
                <i class="bi bi-exclamation-triangle"></i>
                <div>
                    <h3>Alertas</h3>
                    <span><?= htmlspecialchars((string) $alertasCriticos, ENT_QUOTES, 'UTF-8') ?> ativos</span>
                </div>
            </div>
            <div class="cartao-sensores">
                <i class="bi bi-people-fill"></i>
                <div>
                    <h3>Usuários</h3>
                    <div class="informacoes-sensores">
                        <span class="ativos"><?= htmlspecialchars((string) $usuariosAtivos, ENT_QUOTES, 'UTF-8') ?> ativos</span>
                        <span class="manutencao"><?= htmlspecialchars((string) ($totalUsuarios - $usuariosAtivos), ENT_QUOTES, 'UTF-8') ?> inativos</span>
                    </div>
                </div>
            </div>
        </section>
        <section class="cards-painel">
            <div class="card-painel">
                <div class="icone-card laranja">
                    <i class="bi bi-train-front"></i>
                </div>
                <div class="info-card">
                    <h3>TRENS ATIVOS</h3>
                    <strong><?= htmlspecialchars((string) $trensAtivos, ENT_QUOTES, 'UTF-8') ?> / <?= htmlspecialchars((string) $totalTrens, ENT_QUOTES, 'UTF-8') ?></strong>
                    <span>OPERAÇÃO NORMAL</span>
                </div>
            </div>
            <div class="card-painel">
                <div class="icone-card laranja">
                    <i class="bi bi-cpu"></i>
                </div>
                <div class="info-card">
                    <h3>SENSORES<br>ONLINE</h3>
                    <strong><?= htmlspecialchars((string) $sensoresOnline, ENT_QUOTES, 'UTF-8') ?></strong>
                    <span>DE <?= htmlspecialchars((string) $totalSensores, ENT_QUOTES, 'UTF-8') ?><br>CADASTRADOS</span>
                </div>
            </div>
            <div class="card-painel">
                <div id="iconeIntegridadeSistema" class="icone-card vermelho">
                    <i class="bi bi-activity"></i>
                </div>
                <div class="info-card">
                    <h3>INTEGRI-<br>DADE</h3>
                    <strong id="integridadeSistema" class="texto-vermelho"><?= htmlspecialchars((string) $integridade, ENT_QUOTES, 'UTF-8') ?>%</strong>
                    <span id="statusIntegridadeSistema">STATUS DO<br>SISTEMA</span>
                </div>
            </div>
            <div class="card-painel">
                <div class="icone-card vermelho">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="info-card">
                    <h3>ALERTAS<br>CRÍTICOS</h3>
                    <strong class="texto-vermelho"><?= htmlspecialchars((string) $alertasCriticos, ENT_QUOTES, 'UTF-8') ?></strong>
                    <span>REQUER<br>ATENÇÃO</span>
                </div>
            </div>
        </section>


        <section class="painel-monitoramento">

            <div class="titulo-secao">
                <i class="bi bi-cloud"></i>
                <span>Clima nas regiões da ferrovia:</span>
            </div>

            <div id="previsaoTempo" class="area-monitoramento">

                <div class="clima-box">

                    <div id="resultadoTempo">
                        <h3>Nenhuma cidade selecionada</h3>
                        <p>Digite uma cidade para consultar a previsão.</p>
                    </div>

                    <div class="clima-busca">
                        <input type="text" id="cidade" class="input-clima" placeholder="Digite uma cidade">
                        <button id="btnBuscar" class="btn-clima">Buscar</button>
                    </div>

                </div>

            </div>

        </section>


        <section class="painel-trens">
            <div class="titulo-secao">
                <i class="bi bi-train-front"></i>
                <span>Trens</span>
            </div>
            <div class="lista-trens">
                <?php if (!empty($trensAtivosLista)): ?>
                    <?php foreach ($trensAtivosLista as $tremAtivo): ?>
                        <div class="item-trem">
                            <div class="item-trem-conteudo">
                                <strong><?= htmlspecialchars((string) $tremAtivo['nome'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <small><?= htmlspecialchars((string) $tremAtivo['tipo'], ENT_QUOTES, 'UTF-8') ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="item-trem">
                        <div class="item-trem-conteudo">
                            <strong>Nenhum trem ativo</strong>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </main> 


    
    <script src="../../script/previsao_clima.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const integridadeSistema = document.getElementById('integridadeSistema');
            const statusIntegridadeSistema = document.getElementById('statusIntegridadeSistema');
            const iconeIntegridadeSistema = document.getElementById('iconeIntegridadeSistema');

            if (!integridadeSistema || !statusIntegridadeSistema || !iconeIntegridadeSistema) {
                return;
            }

            const endpoints = [
                'crud_usuarios/listar_usuario.php',
                'crud_rotas/listar.php',
                'crud_sensores/listar_sensor.php',
                'crud_trens/listar_trem.php'
            ];

            let atendidos = 0;

            for (const endpoint of endpoints) {
                try {
                    const resposta = await fetch(endpoint, {
                        method: 'GET',
                        cache: 'no-store'
                    });

                    if (!resposta.ok) {
                        throw new Error(`HTTP ${resposta.status}`);
                    }

                    const dados = await resposta.json();

                    if (!dados || dados.status !== 'success') {
                        throw new Error('Resposta inválida');
                    }

                    atendidos += 1;
                } catch (error) {
                    console.warn('CRUD indisponível:', endpoint, error);
                }
            }

            const percentual = Math.round((atendidos / endpoints.length) * 100);
            integridadeSistema.textContent = `${percentual}%`;
            integridadeSistema.classList.remove('texto-vermelho', 'texto-amarelo', 'texto-azul');
            iconeIntegridadeSistema.classList.remove('vermelho', 'texto-vermelho', 'texto-amarelo', 'texto-azul');

            if (percentual === 100) {
                statusIntegridadeSistema.innerHTML = 'SISTEMA<br>ONLINE';
                integridadeSistema.classList.add('texto-azul');
                iconeIntegridadeSistema.classList.add('texto-azul');
            } else if (percentual >= 75) {
                statusIntegridadeSistema.innerHTML = 'ATENÇÃO<br>NO SISTEMA';
                integridadeSistema.classList.add('texto-amarelo');
                iconeIntegridadeSistema.classList.add('texto-amarelo');
            } else {
                statusIntegridadeSistema.innerHTML = 'SISTEMA<br>CRÍTICO';
                integridadeSistema.classList.add('texto-vermelho');
                iconeIntegridadeSistema.classList.add('texto-vermelho');
            }
        });
    </script>
</body>
</html>