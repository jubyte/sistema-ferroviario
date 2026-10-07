<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../../infra/conexao.php';

$nome = trim($_POST['nome'] ?? '');
$empresa = trim($_POST['empresa'] ?? '');
$numero_vagoes = filter_var($_POST['numero_vagoes'] ?? '', FILTER_VALIDATE_INT);
$tipo = trim($_POST['tipo'] ?? '');

if ($nome === '' || $empresa === '' || $tipo === '' || $numero_vagoes === false) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Preencha todos os campos.'
    ]);

    exit;
}

if ($numero_vagoes < 1) {

    echo json_encode([
        'status' => 'error',
        'message' => 'O número de vagões deve ser maior que zero.'
    ]);

    exit;
}

if (!in_array($tipo, ['Eletrico', 'Diesel'], true)) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Tipo de trem inválido.'
    ]);

    exit;
}

try {

    $sql = "INSERT INTO trens
            (nome, empresa, tipo, numero_vagoes)
            VALUES
            (:nome, :empresa, :tipo, :numero_vagoes)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':empresa' => $empresa,
        ':tipo' => $tipo,
        ':numero_vagoes' => $numero_vagoes
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Trem cadastrado com sucesso!'
    ]);

} catch (PDOException $e) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao cadastrar trem: ' . $e->getMessage()
    ]);

}