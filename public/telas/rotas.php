<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit;
}

include "../../infra/conexao.php";
include "../../infra/funcoes.php";

$busca = $_GET["busca"] ?? "";
$textoBusca = "%" . $busca . "%";

$sql = "SELECT id, nome, localizacao_inicial, localizacao_final FROM rotas
        WHERE id LIKE ? OR nome LIKE ? OR localizacao_inicial LIKE ? OR localizacao_final LIKE ?
        ORDER BY id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $textoBusca, $textoBusca, $textoBusca, $textoBusca);
$stmt->execute();
$rotas = $stmt->get_result();

$editando = false;
$acao = "crud_rotas/adicionar.php";
$titulo = "CADASTRAR ROTA";
$textoBotao = "+ ADICIONAR";
$id = "";
$nome = "";
$cepInicial = "";
$cepFinal = "";

if (isset($_GET["editar"])) {
    verificarAdmin("rotas.php");

    $idEditar = (int) $_GET["editar"];
    $stmt = $conn->prepare("SELECT * FROM rotas WHERE id = ?");
    $stmt->bind_param("i", $idEditar);
    $stmt->execute();
    $rota = $stmt->get_result()->fetch_assoc();

    if ($rota) {
        $editando = true;
        $acao = "crud_rotas/editar.php";
        $titulo = "EDITAR ROTA";
        $textoBotao = "SALVAR ALTERAÇÕES";
        $id = $rota["id"];
        $nome = $rota["nome"];
        $cepInicial = pegarCep($rota["localizacao_inicial"]);
        $cepFinal = pegarCep($rota["localizacao_final"]);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
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

        <a href="../../index.php" class="sair">
            <i class="bi bi-box-arrow-left"></i>
            <span>SAIR</span>
        </a>

    </aside>

    <main class="conteudo">

        <header class="cabecalho">
            <h2>ROTAS</h2>
            <h4>SISTEMA FERROVIÁRIO</h4>
        </header>

        <?php mostrarMensagem(); ?>

        <div class="sensor_caixa">

            <h2 class="usuario__title">
                <i class="bi bi-plus-circle"></i> <?= $titulo ?>
            </h2>

            <form method="POST" action="<?= $acao ?>" class="form-salvar" data-carregando="SALVANDO...">
                <input type="hidden" name="id" value="<?= $id ?>">

                <div class="row g-4 align-items-space-between">

                    <div class="nome-rota">
                        <label for="nome" class="label-custom">NOME</label>
                        <input type="text" id="nome" name="nome" class="form-control input-custom" maxlength="100" value="<?= htmlspecialchars($nome) ?>" required>
                    </div>

                    <div class="endereco-rota">
                        <div class="rota-card">
                            <label for="cep-inicial" class="label-custom">CEP - Início</label>
                            <div class="input-cep-group">
                                <input type="text" id="cep-inicial" name="cep_inicial" class="form-control input-custom cep-input" maxlength="9" placeholder="00000-000" value="<?= htmlspecialchars($cepInicial) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="endereco-rota fim">
                        <div class="rota-card">
                            <label for="cep-final" class="label-custom">CEP - Fim</label>
                            <div class="input-cep-group">
                                <input type="text" id="cep-final" name="cep_final" class="form-control input-custom cep-input" maxlength="9" placeholder="00000-000" value="<?= htmlspecialchars($cepFinal) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-2">
                        <div class="acoes-rota">
                            <button type="submit" class="btn botao-custom w-100"><?= $textoBotao ?></button>

                            <?php if ($editando) { ?>
                                <a href="rotas.php" class="btn botao-cancelar w-100 mt-2">CANCELAR ALTERAÇÕES</a>
                            <?php } ?>
                        </div>
                    </div>

                </div>
            </form>

        </div>

        <form method="GET" class="sensor_busca">
            <i class="bi bi-search"></i>
            <input type="text" name="busca" placeholder="Buscar por nome ou ID" value="<?= htmlspecialchars($busca) ?>">
        </form>

        <?php if ($rotas->num_rows == 0) { ?>
            <div class="sensor-vazio" style="display: block;">
                <i class="bi bi-broadcast"></i>
                <h4>Nenhuma rota cadastrada ainda.</h4>
                <p>Cadastre uma nova rota para começar.</p>
            </div>
        <?php } ?>

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
                <tbody>
                    <?php while ($rota = $rotas->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $rota["id"] ?></td>
                            <td><a class="usuario-link" href="rota_detalhes.php?id=<?= $rota["id"] ?>"><?= htmlspecialchars($rota["nome"]) ?></a></td>
                            <td><?= htmlspecialchars(resumoLocal($rota["localizacao_inicial"])) ?></td>
                            <td><?= htmlspecialchars(resumoLocal($rota["localizacao_final"])) ?></td>
                            <td>
                                <a href="rotas.php?editar=<?= $rota["id"] ?>" class="botao-rotas"><i class="bi bi-pencil-square"></i></a>

                                <form method="POST" action="crud_rotas/excluir.php" class="form-excluir" style="display: inline;" data-mensagem="Tem certeza de que deseja excluir esta rota?">
                                    <input type="hidden" name="id" value="<?= $rota["id"] ?>">
                                    <button type="submit" class="botao-rotas"><i class="bi bi-trash3"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </main>


    <script src="../../script/script.js"></script>
</body>
</html>
