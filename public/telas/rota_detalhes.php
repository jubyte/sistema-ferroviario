<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit;
}

include "../../infra/conexao.php";
include "../../infra/funcoes.php";

$id = (int) ($_GET["id"] ?? 0);

$stmt = $conn->prepare("SELECT r.id, r.nome, r.localizacao_inicial, r.localizacao_final, r.trem_id,
                               t.nome AS trem_nome, t.empresa AS trem_empresa, t.tipo AS trem_tipo
                        FROM rotas r
                        LEFT JOIN trens t ON t.id = r.trem_id
                        WHERE r.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$rota = $stmt->get_result()->fetch_assoc();

if (!$rota) {
    header("Location: rotas.php");
    exit;
}

$stmtSensores = $conn->prepare("SELECT id, nome, tipo, status FROM sensores WHERE rota_id = ? ORDER BY nome");
$stmtSensores->bind_param("i", $id);
$stmtSensores->execute();
$sensores = $stmtSensores->get_result();

$vazio = ["cep" => "", "logradouro" => "", "bairro" => "", "cidade" => "", "estado" => ""];

$enderecoInicial = buscarCep(pegarCep($rota["localizacao_inicial"]));
if ($enderecoInicial == null) {
    $enderecoInicial = $vazio;
}

$enderecoFinal = buscarCep(pegarCep($rota["localizacao_final"]));
if ($enderecoFinal == null) {
    $enderecoFinal = $vazio;
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
    <title>Detalhes da rota | MockingRail</title>
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
                <i class="bi bi-signpost-split"></i>
                DADOS DA ROTA
            </h2>

            <div class="usuario-detalhes-grid">
                <div class="usuario-detalhe-item">
                    <span>ID</span>
                    <strong><?= htmlspecialchars($rota['id']) ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Nome</span>
                    <strong><?= htmlspecialchars($rota['nome']) ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Origem</span>
                    <strong><?= htmlspecialchars($rota['localizacao_inicial'] ?? 'Não informado') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Destino</span>
                    <strong><?= htmlspecialchars($rota['localizacao_final'] ?? 'Não informado') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Trem vinculado</span>
                    <strong>
                        <?php if ($rota['trem_nome'] == null) { echo "Não informado"; } else { ?>
                            <a class="usuario-link" href="trem_detalhes.php?id=<?= $rota['trem_id'] ?>"><?= htmlspecialchars($rota['trem_nome']) ?></a>
                        <?php } ?>
                    </strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Empresa do trem</span>
                    <strong><?= htmlspecialchars($rota['trem_empresa'] ?? 'Não informado') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Tipo do trem</span>
                    <strong><?= htmlspecialchars($rota['trem_tipo'] ?? 'Não informado') ?></strong>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-12 col-lg-6">
                    <div class="endereco-card">
                        <h3>Endereço inicial</h3>
                        <div class="mb-3">
                            <label class="label-custom">CEP</label>
                            <input type="text" id="cep-inicial-detalhe" class="form-control input-custom" readonly value="<?= htmlspecialchars($enderecoInicial['cep']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Logradouro</label>
                            <input type="text" id="logradouro-inicial-detalhe" class="form-control input-custom" readonly value="<?= htmlspecialchars($enderecoInicial['logradouro']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Bairro</label>
                            <input type="text" id="bairro-inicial-detalhe" class="form-control input-custom" readonly value="<?= htmlspecialchars($enderecoInicial['bairro']) ?>">
                        </div>
                        <div class="row g-3">
                            <div class="col-8">
                                <label class="label-custom">Cidade</label>
                                <input type="text" id="cidade-inicial-detalhe" class="form-control input-custom" readonly value="<?= htmlspecialchars($enderecoInicial['cidade']) ?>">
                            </div>
                            <div class="col-4">
                                <label class="label-custom">UF</label>
                                <input type="text" id="estado-inicial-detalhe" class="form-control input-custom" readonly value="<?= htmlspecialchars($enderecoInicial['estado']) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="endereco-card">
                        <h3>Endereço final</h3>
                        <div class="mb-3">
                            <label class="label-custom">CEP</label>
                            <input type="text" id="cep-final-detalhe" class="form-control input-custom" readonly value="<?= htmlspecialchars($enderecoFinal['cep']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Logradouro</label>
                            <input type="text" id="logradouro-final-detalhe" class="form-control input-custom" readonly value="<?= htmlspecialchars($enderecoFinal['logradouro']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Bairro</label>
                            <input type="text" id="bairro-final-detalhe" class="form-control input-custom" readonly value="<?= htmlspecialchars($enderecoFinal['bairro']) ?>">
                        </div>
                        <div class="row g-3">
                            <div class="col-8">
                                <label class="label-custom">Cidade</label>
                                <input type="text" id="cidade-final-detalhe" class="form-control input-custom" readonly value="<?= htmlspecialchars($enderecoFinal['cidade']) ?>">
                            </div>
                            <div class="col-4">
                                <label class="label-custom">UF</label>
                                <input type="text" id="estado-final-detalhe" class="form-control input-custom" readonly value="<?= htmlspecialchars($enderecoFinal['estado']) ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="usuario__title mt-4">
                <i class="bi bi-cpu-fill"></i>
                SENSORES DESTA ROTA
            </h2>

            <?php if ($sensores->num_rows == 0) { ?>
                <p>Nenhum sensor vinculado a esta rota.</p>
            <?php } else { ?>
                <div class="sensor_tabela">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NOME</th>
                                <th>TIPO</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($sensor = $sensores->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $sensor['id'] ?></td>
                                    <td><a class="usuario-link" href="sensor_detalhes.php?id=<?= $sensor['id'] ?>"><?= htmlspecialchars($sensor['nome']) ?></a></td>
                                    <td><?= htmlspecialchars($sensor['tipo']) ?></td>
                                    <td><?= htmlspecialchars($sensor['status']) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

            <div class="mt-4">
                <a href="rotas.php" class="btn botao-custom">VOLTAR PARA ROTAS</a>
            </div>
        </div>
    </main>
</body>
</html>