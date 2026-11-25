<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit;
}

include "../../infra/conexao.php";
include "../../infra/funcoes.php";

function contar($conn, $sql)
{
    $resultado = $conn->query($sql);

    if ($resultado == false) {
        return 0;
    }

    $linha = $resultado->fetch_assoc();
    return $linha["total"];
}

$totalTrens = contar($conn, "SELECT COUNT(*) AS total FROM trens");
$trensAtivos = contar($conn, "SELECT COUNT(*) AS total FROM trens WHERE status = 'Ativo'");

$totalSensores = contar($conn, "SELECT COUNT(*) AS total FROM sensores");
$sensoresOnline = contar($conn, "SELECT COUNT(*) AS total FROM sensores WHERE status = 'Ativo'");
$sensoresInativos = contar($conn, "SELECT COUNT(*) AS total FROM sensores WHERE status = 'Inativo'");
$alertasCriticos = $sensoresInativos;

$totalUsuarios = contar($conn, "SELECT COUNT(*) AS total FROM usuarios");
$usuariosAtivos = contar($conn, "SELECT COUNT(*) AS total FROM usuarios WHERE status = 'Ativo'");

$trensAtivosLista = [];
$resultado = $conn->query("SELECT nome, tipo, empresa FROM trens WHERE status = 'Ativo' ORDER BY nome");

while ($trem = $resultado->fetch_assoc()) {
    $trensAtivosLista[] = $trem;
}

$tabelas = ["usuarios", "rotas", "sensores", "trens"];
$tabelasOk = 0;

foreach ($tabelas as $tabela) {
    if ($conn->query("SELECT 1 FROM " . $tabela . " LIMIT 1")) {
        $tabelasOk = $tabelasOk + 1;
    }
}

$integridade = round($tabelasOk / 4 * 100);

if ($integridade == 100) {
    $corIntegridade = "texto-azul";
    $statusIntegridade = "SISTEMA<br>ONLINE";
} else if ($integridade >= 75) {
    $corIntegridade = "texto-amarelo";
    $statusIntegridade = "ATENÇÃO<br>NO SISTEMA";
} else {
    $corIntegridade = "texto-vermelho";
    $statusIntegridade = "SISTEMA<br>CRÍTICO";
}

$cidade = trim($_GET["cidade"] ?? "");
$previsao = null;

if ($cidade != "") {
    $previsao = buscarClima($cidade);
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
                        <span class="ativos"><?= htmlspecialchars($sensoresOnline) ?> ativos</span>
                        <span class="manutencao"><?= htmlspecialchars($sensoresInativos) ?> em manutenção</span>
                    </div>
                </div>
            </div>
            <div class="cartao-alertas">
                <i class="bi bi-exclamation-triangle"></i>
                <div>
                    <h3>Alertas</h3>
                    <span><?= htmlspecialchars($alertasCriticos) ?> ativos</span>
                </div>
            </div>
            <div class="cartao-sensores">
                <i class="bi bi-people-fill"></i>
                <div>
                    <h3>Usuários</h3>
                    <div class="informacoes-sensores">
                        <span class="ativos"><?= htmlspecialchars($usuariosAtivos) ?> ativos</span>
                        <span class="manutencao"><?= htmlspecialchars($totalUsuarios - $usuariosAtivos) ?> inativos</span>
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
                    <strong><?= htmlspecialchars($trensAtivos) ?> / <?= htmlspecialchars($totalTrens) ?></strong>
                    <span>OPERAÇÃO NORMAL</span>
                </div>
            </div>
            <div class="card-painel">
                <div class="icone-card laranja">
                    <i class="bi bi-cpu"></i>
                </div>
                <div class="info-card">
                    <h3>SENSORES<br>ONLINE</h3>
                    <strong><?= htmlspecialchars($sensoresOnline) ?></strong>
                    <span>DE <?= htmlspecialchars($totalSensores) ?><br>CADASTRADOS</span>
                </div>
            </div>
            <div class="card-painel">
                <div id="iconeIntegridadeSistema" class="icone-card <?= $corIntegridade ?>">
                    <i class="bi bi-activity"></i>
                </div>
                <div class="info-card">
                    <h3>INTEGRI-<br>DADE</h3>
                    <strong id="integridadeSistema" class="<?= $corIntegridade ?>"><?= htmlspecialchars($integridade) ?>%</strong>
                    <span id="statusIntegridadeSistema"><?= $statusIntegridade ?></span>
                </div>
            </div>
            <div class="card-painel">
                <div class="icone-card vermelho">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="info-card">
                    <h3>ALERTAS<br>CRÍTICOS</h3>
                    <strong class="texto-vermelho"><?= htmlspecialchars($alertasCriticos) ?></strong>
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
                        <?php if ($cidade == "") { ?>
                            <h3>Nenhuma cidade selecionada</h3>
                            <p>Digite uma cidade para consultar a previsão.</p>
                        <?php } else if ($previsao == null) { ?>
                            <p>Cidade não encontrada.</p>
                        <?php } else { ?>
                            <h3><?= htmlspecialchars($previsao["cidade"]) ?></h3>
                            <p><?= htmlspecialchars($previsao["regiao"]) ?></p>
                            <p>Temperatura: <?= $previsao["temperatura"] ?> °C</p>
                            <p>Sensação térmica: <?= $previsao["sensacao"] ?> °C</p>
                            <p>Umidade: <?= $previsao["umidade"] ?>%</p>
                            <p>Chuva: <?= $previsao["chuva"] ?> mm</p>
                            <p>Vento: <?= $previsao["vento"] ?> km/h</p>
                        <?php } ?>
                    </div>

                    <form method="GET" class="clima-busca form-salvar" data-carregando="BUSCANDO...">
                        <input type="text" name="cidade" class="input-clima" placeholder="Digite uma cidade" value="<?= htmlspecialchars($cidade) ?>">
                        <button type="submit" class="btn-clima">Buscar</button>
                    </form>

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
                                <strong><?= htmlspecialchars($tremAtivo['nome']) ?></strong>
                                <small><?= htmlspecialchars($tremAtivo['tipo']) ?></small>
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


    

    <script src="../../script/script.js"></script>
</body>
</html>