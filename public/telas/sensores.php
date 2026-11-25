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

$sql = "SELECT sensores.id, sensores.nome, sensores.tipo, sensores.localizacao, sensores.status,
               trens.nome AS trem_nome, rotas.nome AS rota_nome
        FROM sensores
        LEFT JOIN trens ON trens.id = sensores.trem_id
        LEFT JOIN rotas ON rotas.id = sensores.rota_id
        WHERE sensores.id LIKE ? OR sensores.nome LIKE ?
        ORDER BY sensores.id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $textoBusca, $textoBusca);
$stmt->execute();
$sensores = $stmt->get_result();

$listaTrens = $conn->query("SELECT id, nome FROM trens ORDER BY nome");
$listaRotas = $conn->query("SELECT id, nome FROM rotas ORDER BY nome");

$tipos = [
    "Velocidade" => "Velocidade",
    "Localizacao" => "Localização",
    "Energia" => "Consumo de energia",
    "Temperatura" => "Temperatura",
    "Presenca" => "Presença",
    "Falha" => "Detecção de falhas"
];

$editando = false;
$acao = "crud_sensores/cadastrar_sensor.php";
$titulo = "CADASTRAR SENSOR";
$textoBotao = "+ ADICIONAR";
$id = "";
$nome = "";
$tipo = "";
$local = "";
$trem = "";
$rota = "";
$status = "Ativo";

if (isset($_GET["editar"])) {
    verificarAdmin("sensores.php");

    $idEditar = (int) $_GET["editar"];
    $stmt = $conn->prepare("SELECT * FROM sensores WHERE id = ?");
    $stmt->bind_param("i", $idEditar);
    $stmt->execute();
    $sensor = $stmt->get_result()->fetch_assoc();

    if ($sensor) {
        $editando = true;
        $acao = "crud_sensores/editar_sensor.php";
        $titulo = "EDITAR SENSOR";
        $textoBotao = "SALVAR ALTERAÇÕES";
        $id = $sensor["id"];
        $nome = $sensor["nome"];
        $tipo = $sensor["tipo"];
        $local = $sensor["localizacao"];
        $trem = $sensor["trem_id"];
        $rota = $sensor["rota_id"];
        $status = $sensor["status"];
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

    <title>Sensores | MockingRail</title>
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

        <a href="../../index.php" class="sair">
            <i class="bi bi-box-arrow-left"></i>
            <span>SAIR</span>
        </a>

    </aside>

    <main class="conteudo">

        <header class="cabecalho">
            <h2>SENSORES</h2>
            <h4>SISTEMA FERROVIÁRIO</h4>
        </header>

        <?php mostrarMensagem(); ?>

        <div class="sensor_caixa">

            <h2 class="usuario__title">
                <i class="bi bi-plus-circle"></i> <?= $titulo ?>
            </h2>

            <form method="POST" action="<?= $acao ?>" class="form-salvar" data-carregando="SALVANDO...">
                <input type="hidden" name="id" value="<?= $id ?>">

                <div class="row g-4 align-items-end">

                    <div class="col">
                        <label for="nome" class="label-custom">NOME</label>
                        <input type="text" id="nome" name="nome" class="form-control input-custom" maxlength="100" value="<?= htmlspecialchars($nome) ?>" required>
                    </div>

                    <div class="col">
                        <label for="tipo" class="label-custom">TIPO DE SENSOR</label>
                        <select id="tipo" name="tipo" class="form-select input-custom select-custom" required>
                            <option value="" disabled <?php if ($tipo == "") { echo "selected"; } ?>>SELECIONE</option>
                            <?php foreach ($tipos as $valor => $texto) { ?>
                                <option value="<?= $valor ?>" <?php if ($tipo == $valor) { echo "selected"; } ?>><?= $texto ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col">
                        <label for="local" class="label-custom">LOCALIZAÇÃO</label>
                        <input type="text" id="local" name="local" class="form-control input-custom" maxlength="100" value="<?= htmlspecialchars($local) ?>" required>
                    </div>

                    <div class="col">
                        <label for="trem_id" class="label-custom">TREM ASSOCIADO</label>
                        <select id="trem_id" name="trem_id" class="form-select input-custom select-custom">
                            <option value="">Nenhum trem associado</option>
                            <?php while ($item = $listaTrens->fetch_assoc()) { ?>
                                <option value="<?= $item["id"] ?>" <?php if ($item["id"] == $trem) { echo "selected"; } ?>><?= htmlspecialchars($item["nome"]) ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col">
                        <label for="rota_id" class="label-custom">ROTA RESPONSÁVEL</label>
                        <select id="rota_id" name="rota_id" class="form-select input-custom select-custom" required>
                            <option value="">Selecione a rota</option>
                            <?php while ($item = $listaRotas->fetch_assoc()) { ?>
                                <option value="<?= $item["id"] ?>" <?php if ($item["id"] == $rota) { echo "selected"; } ?>><?= htmlspecialchars($item["nome"]) ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col">
                        <label for="status" class="label-custom">STATUS</label>
                        <select id="status" name="status" class="form-select input-custom select-custom" required>
                            <option value="Ativo" <?php if ($status == "Ativo") { echo "selected"; } ?>>Ativo</option>
                            <option value="Inativo" <?php if ($status == "Inativo") { echo "selected"; } ?>>Inativo</option>
                        </select>
                    </div>

                    <div class="col">
                        <button type="submit" class="btn botao-custom w-100"><?= $textoBotao ?></button>

                        <?php if ($editando) { ?>
                            <a href="sensores.php" class="btn botao-cancelar w-100 mt-2">CANCELAR ALTERAÇÕES</a>
                        <?php } ?>
                    </div>

                </div>
            </form>
        </div>

        <form method="GET" class="sensor_busca">
            <i class="bi bi-search"></i>
            <input type="text" name="busca" placeholder="Buscar por nome ou ID" value="<?= htmlspecialchars($busca) ?>">
        </form>

        <?php if ($sensores->num_rows == 0) { ?>
            <div class="sensor-vazio" style="display: block;">
                <i class="bi bi-broadcast"></i>
                <h4>Nenhum sensor cadastrado ainda.</h4>
                <p>Cadastre um novo sensor para começar.</p>
            </div>
        <?php } ?>

        <div class="sensor_tabela">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOME</th>
                        <th>TIPO</th>
                        <th>LOCALIZAÇÃO</th>
                        <th>TREM ASSOCIADO</th>
                        <th>ROTA RESPONSÁVEL</th>
                        <th>STATUS</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($sensor = $sensores->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $sensor["id"] ?></td>
                            <td><a class="usuario-link" href="sensor_detalhes.php?id=<?= $sensor["id"] ?>"><?= htmlspecialchars($sensor["nome"]) ?></a></td>
                            <td><?= htmlspecialchars($sensor["tipo"]) ?></td>
                            <td><?= htmlspecialchars(resumoLocal($sensor["localizacao"])) ?></td>
                            <td>
                                <?php if ($sensor["trem_nome"] == null) { echo "Não associado"; } else { echo htmlspecialchars($sensor["trem_nome"]); } ?>
                            </td>
                            <td>
                                <?php if ($sensor["rota_nome"] == null) { echo "Não associada"; } else { echo htmlspecialchars($sensor["rota_nome"]); } ?>
                            </td>
                            <td><?= htmlspecialchars($sensor["status"]) ?></td>
                            <td>
                                <a href="sensores.php?editar=<?= $sensor["id"] ?>" class="btn-acao"><i class="bi bi-pencil-square"></i></a>

                                <form method="POST" action="crud_sensores/excluir_sensor.php" class="form-excluir" style="display: inline;" data-mensagem="Deseja excluir este sensor?">
                                    <input type="hidden" name="id" value="<?= $sensor["id"] ?>">
                                    <button type="submit" class="btn-acao"><i class="bi bi-trash3"></i></button>
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