<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../../infra/conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: rotas.php');
    exit;
}

$stmt = $conn->prepare("SELECT id, nome, localizacao_inicial, localizacao_final FROM rotas WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$resultado = $stmt->get_result();
$rota = $resultado->fetch_assoc();
$stmt->close();

if (!$rota) {
    header('Location: rotas.php');
    exit;
}

function extrairCepDetalhe(string $texto): string {
    if (preg_match('/\d{5}-\d{3}/', $texto, $match)) {
        return $match[0];
    }

    return '';
}

$cepInicial = extrairCepDetalhe((string) ($rota['localizacao_inicial'] ?? ''));
$cepFinal = extrairCepDetalhe((string) ($rota['localizacao_final'] ?? ''));
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
<body data-usuario-tipo="<?= htmlspecialchars($_SESSION["tipo"] ?? "", ENT_QUOTES, 'UTF-8') ?>">
    <aside class="sidebar">
        <div class="logo-box">
            <h1 class="logo">R<span>Λ</span>IL</h1>
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
                    <strong><?= htmlspecialchars((string) $rota['id'], ENT_QUOTES, 'UTF-8') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Nome</span>
                    <strong><?= htmlspecialchars((string) $rota['nome'], ENT_QUOTES, 'UTF-8') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Origem</span>
                    <strong><?= htmlspecialchars((string) ($rota['localizacao_inicial'] ?? 'Não informado'), ENT_QUOTES, 'UTF-8') ?></strong>
                </div>

                <div class="usuario-detalhe-item">
                    <span>Destino</span>
                    <strong><?= htmlspecialchars((string) ($rota['localizacao_final'] ?? 'Não informado'), ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-12 col-lg-6">
                    <div class="endereco-card">
                        <h3>Endereço inicial</h3>
                        <div class="mb-3">
                            <label class="label-custom">CEP</label>
                            <input type="text" id="cep-inicial-detalhe" class="form-control input-custom" readonly value="<?= htmlspecialchars($cepInicial, ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Logradouro</label>
                            <input type="text" id="logradouro-inicial-detalhe" class="form-control input-custom" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Bairro</label>
                            <input type="text" id="bairro-inicial-detalhe" class="form-control input-custom" readonly>
                        </div>
                        <div class="row g-3">
                            <div class="col-8">
                                <label class="label-custom">Cidade</label>
                                <input type="text" id="cidade-inicial-detalhe" class="form-control input-custom" readonly>
                            </div>
                            <div class="col-4">
                                <label class="label-custom">UF</label>
                                <input type="text" id="estado-inicial-detalhe" class="form-control input-custom" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="endereco-card">
                        <h3>Endereço final</h3>
                        <div class="mb-3">
                            <label class="label-custom">CEP</label>
                            <input type="text" id="cep-final-detalhe" class="form-control input-custom" readonly value="<?= htmlspecialchars($cepFinal, ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Logradouro</label>
                            <input type="text" id="logradouro-final-detalhe" class="form-control input-custom" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Bairro</label>
                            <input type="text" id="bairro-final-detalhe" class="form-control input-custom" readonly>
                        </div>
                        <div class="row g-3">
                            <div class="col-8">
                                <label class="label-custom">Cidade</label>
                                <input type="text" id="cidade-final-detalhe" class="form-control input-custom" readonly>
                            </div>
                            <div class="col-4">
                                <label class="label-custom">UF</label>
                                <input type="text" id="estado-final-detalhe" class="form-control input-custom" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="rotas.php" class="btn botao-custom">VOLTAR PARA ROTAS</a>
            </div>
        </div>
    </main>

    <script>
        async function consultarEnderecoPorCep(cep, campoPrefixo) {
            if (!cep || !/^\d{5}-\d{3}$/.test(cep)) {
                return;
            }

            try {
                const resposta = await fetch(`https://viacep.com.br/ws/${cep.replace(/\D/g, '')}/json/`);
                const dados = await resposta.json();

                if (dados.erro) {
                    return;
                }

                const prefix = campoPrefixo;
                document.getElementById(`logradouro-${prefix}-detalhe`).value = dados.logradouro || '';
                document.getElementById(`bairro-${prefix}-detalhe`).value = dados.bairro || '';
                document.getElementById(`cidade-${prefix}-detalhe`).value = dados.localidade || '';
                document.getElementById(`estado-${prefix}-detalhe`).value = dados.uf || '';
            } catch (erro) {
                console.error('Erro ao consultar CEP do detalhe da rota:', erro);
            }
        }

        const cepInicial = document.getElementById('cep-inicial-detalhe')?.value;
        const cepFinal = document.getElementById('cep-final-detalhe')?.value;

        if (cepInicial) {
            consultarEnderecoPorCep(cepInicial, 'inicial');
        }

        if (cepFinal) {
            consultarEnderecoPorCep(cepFinal, 'final');
        }
    </script>
</body>
</html>
