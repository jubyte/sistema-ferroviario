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

$sql = "SELECT id, nome, email, telefone, status FROM usuarios
        WHERE id LIKE ? OR nome LIKE ? OR email LIKE ? OR telefone LIKE ?
        ORDER BY id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $textoBusca, $textoBusca, $textoBusca, $textoBusca);
$stmt->execute();
$usuarios = $stmt->get_result();

$editando = false;
$acao = "crud_usuarios/cadastrar_usuario.php";
$titulo = "CADASTRAR USUÁRIO";
$textoBotao = "+ ADICIONAR";
$id = "";
$nome = "";
$email = "";
$telefone = "";
$tipo = "";
$cep = "";
$logradouro = "";
$bairro = "";
$cidade = "";
$estado = "";

if (isset($_GET["editar"])) {
    verificarAdmin("usuarios.php");

    $idEditar = (int) $_GET["editar"];
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $idEditar);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();

    if ($usuario) {
        $editando = true;
        $acao = "crud_usuarios/editar_usuario.php";
        $titulo = "EDITAR USUÁRIO";
        $textoBotao = "SALVAR ALTERAÇÕES";
        $id = $usuario["id"];
        $nome = $usuario["nome"];
        $email = $usuario["email"];
        $telefone = $usuario["telefone"];
        $tipo = $usuario["tipo"];
        $cep = $usuario["cep"] ?? "";
        $logradouro = $usuario["logradouro"] ?? "";
        $bairro = $usuario["bairro"] ?? "";
        $cidade = $usuario["cidade"] ?? "";
        $estado = $usuario["estado"] ?? "";
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

    <title>Usuários | MockingRail</title>
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

            <a href="trens.php" class="menu-item">
                <i class="bi bi-train-front"></i>
                <span>TRENS</span>
            </a>

            <a href="usuarios.php" class="menu-item active">
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
            <h2>USUÁRIOS</h2>
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
                    <div class="col-12 col-lg-2">
                        <label for="nome" class="label-custom">NOME</label>
                        <input type="text" id="nome" name="nome" class="form-control input-custom" maxlength="100" value="<?= htmlspecialchars($nome) ?>" required>
                    </div>

                    <div class="col-12 col-lg-2">
                        <label for="email" class="label-custom">E-MAIL</label>
                        <input type="email" id="email" name="email" class="form-control input-custom" maxlength="100" value="<?= htmlspecialchars($email) ?>" required>
                    </div>

                    <div class="col-12 col-lg-2">
                        <label for="telefone" class="label-custom">TELEFONE</label>
                        <input type="tel" id="telefone" name="telefone" class="form-control input-custom" maxlength="20" value="<?= htmlspecialchars($telefone) ?>" required>
                    </div>

                    <?php if (!$editando) { ?>
                        <div class="col-12 col-lg-2">
                            <label for="senha" class="label-custom">SENHA</label>
                            <input type="password" id="senha" name="senha" class="form-control input-custom" minlength="8" maxlength="72" autocomplete="new-password" required>
                        </div>
                    <?php } ?>

                    <div class="col-12 col-lg-2">
                        <label for="tipo" class="label-custom">TIPO</label>
                        <select id="tipo" name="tipo" class="form-select input-custom select-custom" required>
                            <option value="" disabled <?php if ($tipo == "") { echo "selected"; } ?>>SELECIONE</option>
                            <option value="Administrador" <?php if ($tipo == "Administrador") { echo "selected"; } ?>>Administrador</option>
                            <option value="Usuário" <?php if ($tipo == "Usuário") { echo "selected"; } ?>>Usuário</option>
                        </select>
                    </div>

                    <div class="col-12 col-lg-2">
                        <button type="submit" class="btn botao-custom w-100"><?= $textoBotao ?></button>

                        <?php if ($editando) { ?>
                            <a href="usuarios.php" class="btn botao-cancelar w-100 mt-2">CANCELAR ALTERAÇÕES</a>
                        <?php } ?>
                    </div>

                    <div class="col-12 col-lg-2">
                        <label for="cep" class="label-custom">CEP</label>
                        <input type="text" id="cep" name="cep" class="form-control input-custom cep-input" maxlength="9" placeholder="00000-000" value="<?= htmlspecialchars($cep) ?>">
                    </div>

                    <div class="col-12 col-lg-3">
                        <label for="logradouro" class="label-custom">LOGRADOURO</label>
                        <input type="text" id="logradouro" class="form-control input-custom" value="<?= htmlspecialchars($logradouro) ?>" readonly>
                    </div>

                    <div class="col-12 col-lg-2">
                        <label for="bairro" class="label-custom">BAIRRO</label>
                        <input type="text" id="bairro" class="form-control input-custom" value="<?= htmlspecialchars($bairro) ?>" readonly>
                    </div>

                    <div class="col-12 col-lg-3">
                        <label for="cidade" class="label-custom">CIDADE</label>
                        <input type="text" id="cidade" class="form-control input-custom" value="<?= htmlspecialchars($cidade) ?>" readonly>
                    </div>

                    <div class="col-12 col-lg-2">
                        <label for="estado" class="label-custom">UF</label>
                        <input type="text" id="estado" class="form-control input-custom" value="<?= htmlspecialchars($estado) ?>" readonly>
                    </div>
                </div>
            </form>
        </div>

        <form method="GET" class="sensor_busca">
            <i class="bi bi-search"></i>
            <input type="text" name="busca" placeholder="Buscar por nome ou ID" value="<?= htmlspecialchars($busca) ?>">
        </form>

        <?php if ($usuarios->num_rows == 0) { ?>
            <div class="sensor-vazio" style="display: block;">
                <i class="bi bi-broadcast"></i>
                <h4>Nenhum usuário cadastrado ainda.</h4>
                <p>Cadastre um novo usuário para começar.</p>
            </div>
        <?php } ?>

        <div class="sensor_tabela">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOME</th>
                        <th>E-MAIL</th>
                        <th>TELEFONE</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($usuario = $usuarios->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $usuario["id"] ?></td>
                            <td><a class="usuario-link" href="usuario_detalhes.php?id=<?= $usuario["id"] ?>"><?= htmlspecialchars($usuario["nome"]) ?></a></td>
                            <td><?= htmlspecialchars($usuario["email"]) ?></td>
                            <td><?= htmlspecialchars($usuario["telefone"]) ?></td>
                            <td>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="status-ativo">● <?= htmlspecialchars($usuario["status"]) ?></span>

                                    <div>
                                        <a href="usuarios.php?editar=<?= $usuario["id"] ?>" class="btn-acao"><i class="bi bi-pencil-square icone-color"></i></a>

                                        <form method="POST" action="crud_usuarios/excluir_usuario.php" class="form-excluir" style="display: inline;" data-mensagem="Deseja realmente remover este usuário?">
                                            <input type="hidden" name="id" value="<?= $usuario["id"] ?>">
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