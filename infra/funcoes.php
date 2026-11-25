<?php
function voltar($pagina, $mensagem)
{
    $_SESSION["mensagem"] = $mensagem;
    header("Location: " . $pagina);
    exit;
}

function mostrarMensagem()
{
    if (isset($_SESSION["mensagem"])) {
        echo '<div class="alert alert-info">' . htmlspecialchars($_SESSION["mensagem"]) . '</div>';
        unset($_SESSION["mensagem"]);
    }
}

function verificarAdmin($pagina)
{
    if (!isset($_SESSION["tipo"]) || $_SESSION["tipo"] != "Administrador") {
        voltar($pagina, "Você não tem permissão para realizar tal ação");
    }
}

function pegarCep($texto)
{
    if (preg_match("/[0-9]{5}-[0-9]{3}/", $texto, $achou)) {
        return $achou[0];
    }

    return "";
}

function resumoLocal($texto)
{
    $cep = pegarCep($texto);

    if ($cep != "") {
        return "CEP: " . $cep;
    }

    return $texto;
}

function buscarCep($cep)
{
    $cep = str_replace("-", "", $cep);

    if (strlen($cep) != 8 || !is_numeric($cep)) {
        return null;
    }

    $resposta = @file_get_contents("https://viacep.com.br/ws/" . $cep . "/json/");

    if ($resposta == false) {
        return null;
    }

    $dados = json_decode($resposta, true);

    if (isset($dados["erro"])) {
        return null;
    }

    return [
        "cep" => $dados["cep"],
        "logradouro" => $dados["logradouro"],
        "bairro" => $dados["bairro"],
        "cidade" => $dados["localidade"],
        "estado" => $dados["uf"]
    ];
}

function montarEndereco($endereco)
{
    $texto = "CEP " . $endereco["cep"];

    if ($endereco["logradouro"] != "") {
        $texto = $texto . " - " . $endereco["logradouro"];
    }

    if ($endereco["bairro"] != "") {
        $texto = $texto . " - " . $endereco["bairro"];
    }

    $texto = $texto . " - " . $endereco["cidade"] . "/" . $endereco["estado"];

    return $texto;
}

function buscarClima($cidade)
{
    $url = "https://geocoding-api.open-meteo.com/v1/search?count=1&language=pt&format=json&name=" . urlencode($cidade);
    $resposta = @file_get_contents($url);

    if ($resposta == false) {
        return null;
    }

    $dadosCidade = json_decode($resposta, true);

    if (!isset($dadosCidade["results"])) {
        return null;
    }

    $local = $dadosCidade["results"][0];

    $url = "https://api.open-meteo.com/v1/forecast?timezone=auto"
        . "&latitude=" . $local["latitude"]
        . "&longitude=" . $local["longitude"]
        . "&current=temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,wind_speed_10m";
    $resposta = @file_get_contents($url);

    if ($resposta == false) {
        return null;
    }

    $dadosTempo = json_decode($resposta, true);
    $clima = $dadosTempo["current"];

    $regiao = "";
    if (isset($local["admin1"])) {
        $regiao = $local["admin1"] . " - ";
    }
    if (isset($local["country"])) {
        $regiao = $regiao . $local["country"];
    }

    return [
        "cidade" => $local["name"],
        "regiao" => $regiao,
        "temperatura" => $clima["temperature_2m"],
        "sensacao" => $clima["apparent_temperature"],
        "umidade" => $clima["relative_humidity_2m"],
        "chuva" => $clima["precipitation"],
        "vento" => $clima["wind_speed_10m"]
    ];
}
?>
