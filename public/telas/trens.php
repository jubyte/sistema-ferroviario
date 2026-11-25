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

$sql = "SELECT trens.id, trens.nome, trens.empresa, trens.numero_vagoes, trens.tipo, trens.status,
               usuarios.nome AS responsavel_nome
        FROM trens
        LEFT JOIN usuarios ON trens.responsavel_id = usuarios.id
        WHERE trens.id LIKE ? OR trens.nome LIKE ? OR trens.empresa LIKE ? OR usuarios.nome LIKE ?
        ORDER BY trens.nome";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $textoBusca, $textoBusca, $textoBusca, $textoBusca);
$stmt->execute();
$trens = $stmt->get_result();

$usuarios = $conn->query("SELECT id, nome FROM usuarios ORDER BY nome");

$editando = false;
$acao = "crud_trens/cadastrar_trem.php";
$titulo = "CADASTRAR TREM";
$textoBotao = "+ ADICIONAR";
$id = "";
$nome = "";
$empresa = "";
$vagoes = "";
$tipo = "";
$responsavel = "";

if (isset($_GET["editar"])) {
    verificarAdmin("trens.php");

    $idEditar = (int) $_GET["editar"];
    $stmt = $conn->prepare("SELECT * FROM trens WHERE id = ?");
    $stmt->bind_param("i", $idEditar);
    $stmt->execute();
    $trem = $stmt->get_result()->fetch_assoc();

    if ($trem) {
        $editando = true;
        $acao = "crud_trens/editar_trem.php";
        $titulo = "EDITAR TREM";
        $textoBotao = "SALVAR ALTERAÇÕES";
        $id = $trem["id"];
        $nome = $trem["nome"];
        $empresa = $trem["empresa"];
        $vagoes = $trem["numero_vagoes"];
        $tipo = $trem["tipo"];
        $responsavel = $trem["responsavel_id"];
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

    <title>Trens | MockingRail</title>
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

            <a href="sensores.php" class="menu-item">
                <i class="bi bi-cpu-fill"></i>
                <span>SENSORES</span>
            </a>

            <a href="trens.php" class="menu-item active">
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
            <h2>TRENS</h2>
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
                        <label for="empresa" class="label-custom">EMPRESA</label>
                        <input type="text" id="empresa" name="empresa" class="form-control input-custom" maxlength="100" value="<?= htmlspecialchars($empresa) ?>" required>
                    </div>

                    <div class="col">
                        <label for="vagoes" class="label-custom">Nº VAGÕES</label>
                        <input type="number" min="1" id="vagoes" name="numero_vagoes" class="form-control input-custom" value="<?= $vagoes ?>" required>
                    </div>

                    <div class="col">
                        <label for="tipo" class="label-custom">TIPO</label>
                        <select id="tipo" name="tipo" class="form-select input-custom select-custom" required>
                            <option value="" disabled <?php if ($tipo == "") { echo "selected"; } ?>>SELECIONE</option>
                            <option value="Eletrico" <?php if ($tipo == "Eletrico") { echo "selected"; } ?>>Elétrico</option>
                            <option value="Diesel" <?php if ($tipo == "Diesel") { echo "selected"; } ?>>Diesel</option>
                        </select>
                    </div>

                    <div class="col">
                        <label for="responsavel_id" class="label-custom">RESPONSÁVEL</label>
                        <select id="responsavel_id" name="responsavel_id" class="form-select input-custom select-custom" required>
                            <option value="">Selecione o responsável</option>
                            <?php while ($usuario = $usuarios->fetch_assoc()) { ?>
                                <option value="<?= $usuario["id"] ?>" <?php if ($usuario["id"] == $responsavel) { echo "selected"; } ?>><?= htmlspecialchars($usuario["nome"]) ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col">
                        <button type="submit" class="btn botao-custom w-100"><?= $textoBotao ?></button>

                        <?php if ($editando) { ?>
                            <a href="trens.php" class="btn botao-cancelar w-100 mt-2">CANCELAR ALTERAÇÕES</a>
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>

        <form method="GET" class="sensor_busca">
            <i class="bi bi-search"></i>
            <input type="text" name="busca" placeholder="Buscar por nome, ID ou responsável" value="<?= htmlspecialchars($busca) ?>">
        </form>

        <?php if ($trens->num_rows == 0) { ?>
            <div class="sensor-vazio" style="display: block;">
                <div class="icone_vazio">
                    <i class="bi bi-train-front"></i>
                </div>

                <h4>Nenhum trem cadastrado ainda.</h4>
                <p>Cadastre um novo trem para começar.</p>
            </div>
        <?php } ?>

        <div class="sensor_tabela">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOME</th>
                        <th>EMPRESA</th>
                        <th>Nº VAGÕES</th>
                        <th>TIPO</th>
                        <th>RESPONSÁVEL</th>
                        <th>STATUS</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($trem = $trens->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $trem["id"] ?></td>
                            <td><a class="usuario-link" href="trem_detalhes.php?id=<?= $trem["id"] ?>"><?= htmlspecialchars($trem["nome"]) ?></a></td>
                            <td><?= htmlspecialchars($trem["empresa"]) ?></td>
                            <td><?= $trem["numero_vagoes"] ?></td>
                            <td>
                                <?php if ($trem["tipo"] == "Eletrico") { echo "Elétrico"; } else { echo htmlspecialchars($trem["tipo"]); } ?>
                            </td>
                            <td>
                                <?php if ($trem["responsavel_nome"] == null) { echo "Não informado"; } else { echo htmlspecialchars($trem["responsavel_nome"]); } ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="status-ativo">● <?= htmlspecialchars($trem["status"]) ?></span>

                                    <div>
                                        <a href="trens.php?editar=<?= $trem["id"] ?>" class="btn-acao"><i class="bi bi-pencil-square icone-color"></i></a>

                                        <form method="POST" action="crud_trens/excluir_trem.php" class="form-excluir" style="display: inline;" data-mensagem="Deseja realmente remover este trem?">
                                            <input type="hidden" name="id" value="<?= $trem["id"] ?>">
                                            <button type="submit" class="btn-acao"><i class="bi bi-trash3 icone-color"></i></button>
                                        </form>
                                    </div>
                                </div>
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