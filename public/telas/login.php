
<?php
session_start();

require_once __DIR__ . "/../../infra/conexao.php";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    // Procura o usuário pelo e-mail
    $sql = "SELECT id, nome, email, senha, tipo, status
            FROM usuarios
            WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    // Verifica se o usuário existe e se a senha está correta
    if (
        $usuario &&
        $usuario["senha"] === $senha &&
        $usuario["status"] === "Ativo"
    ) {

        // Cria a sessão do usuário
        session_regenerate_id(true);

        $_SESSION["id"] = $usuario["id"];
        $_SESSION["nome"] = $usuario["nome"];
        $_SESSION["email"] = $usuario["email"];
        $_SESSION["tipo"] = $usuario["tipo"];

        // Redireciona para a página inicial do sistema
        header("Location: menu.php");
        exit;

    } else {

        $erro = "E-mail ou senha incorretos!";
    }

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

    <title>Login | MockingRail</title>
</head>

<body>
    <a href="../../index.php">
        <img class="logo-login" src="../../assets/icons/logo.png" alt="logo da MockingRail">
    </a>

<div class="bg"></div>
<div class="overlay"></div>

<main class="container d-flex align-items-center justify-content-center vh-100">

    <section class="login w-100 d-flex justify-content-center">

        <div class="login__box">
            <header class="login__header text-center">
                <h1 id="titulo" class="login__title">LOGIN</h1>
            </header>

            <form id="form-login" action="login.php" method="POST">
                <div class="mb-2">
                    <label for="email" class="form-label login__label">EMAIL</label>
                    <input type="email" id="email" name="email" class="form-control login__input" required>
                </div>

                <div class="mb-2">
                    <label for="senha" class="form-label login__label">SENHA</label>
                    <input type="password" id="senha" name="senha" class="form-control login__input" required>
                </div>

                <button class="btn login__button w-100" type="submit">ENTRAR</button>
                    
                <div id="mensagem" class="text-center mt-3">
                    <?php if (isset($erro)): ?>
                        <span class="text-danger">
                            <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    <?php endif; ?>
                </div>

            </form>

        </div>

    </section>

</main>

<script src="../../script/script.js"></script>

</body>
</html>