
let rotas = [];
let rotaEditando = null;

const nomeInput = document.getElementById("nome");
const inicioInput = document.getElementById("localizacao-inicial");
const fimInput = document.getElementById("localizacao-final");

const botaoAdicionar = document.getElementById("btnAdicionar");
const campoBusca = document.getElementById("buscar");
const tabela = document.getElementById("tabelaRotas");
const sensorVazio = document.getElementById("sensorVazio");


function mostrarMensagem(mensagem) {
    alert(mensagem);
}

function limparFormulario() {
    nomeInput.value = "";
    inicioInput.value = "";
    fimInput.value = "";

    rotaEditando = null;
    botaoAdicionar.textContent = "Adicionar rota";

    const titulo = document.querySelector(".usuario__title");
    if (titulo) {
        titulo.textContent = "Cadastro de Rotas";
    }

    const cancelar = document.getElementById("btnCancelar");
    if (cancelar) {
        cancelar.style.display = "none";
    }
}

async function requisicaoPHP(url, opcoes = {}) {
    const resposta = await fetch(url, opcoes);
    const texto = await resposta.text();

    let dados;

    try {
        dados = JSON.parse(texto);
    } catch (erro) {
        console.error("Resposta recebida do PHP:", texto);
        throw new Error(
            "O PHP não retornou JSON válido. Confira o console (F12)."
        );
    }

    if (!resposta.ok || dados.status !== "success") {
        console.error("Erro retornado pelo PHP:", dados);

        throw new Error(
            dados.detalhe ||
            dados.message ||
            "Não foi possível concluir a operação."
        );
    }

    return dados;
}

async function carregarRotas() {
    try {
        const dados = await requisicaoPHP("crud_rotas/listar.php");

        rotas = Array.isArray(dados.rotas) ? dados.rotas : [];

        mostrarRotas(rotas);
    } catch (erro) {
        console.error("Erro ao carregar rotas:", erro);
        mostrarMensagem("Erro ao carregar as rotas: " + erro.message);
    }
}

function mostrarRotas(lista) {
    tabela.innerHTML = "";

    if (lista.length === 0) {
        if (sensorVazio) {
            sensorVazio.style.display = "block";
        }
        return;
    }

    if (sensorVazio) {
        sensorVazio.style.display = "none";
    }

    lista.forEach((rota) => {
        const linha = document.createElement("tr");

        const colunaId = document.createElement("td");
        colunaId.textContent = rota.id;

        const colunaNome = document.createElement("td");
        colunaNome.textContent = rota.nome;

        const colunaInicio = document.createElement("td");
        colunaInicio.textContent = rota.localizacao_inicial;

        const colunaFim = document.createElement("td");
        colunaFim.textContent = rota.localizacao_final;

        const colunaAcoes = document.createElement("td");
        
        
        const botaoEditar = document.createElement("button");
        botaoEditar.type = "button";
        botaoEditar.innerHTML =  '<i class="bi bi-pencil-square"></i>';
        botaoEditar.className = "botao-rotas";
        botaoEditar.addEventListener("click", () => {
            editarRota(rota.id);
        });

        const botaoExcluir = document.createElement("button");
        botaoExcluir.type = "button";
        botaoExcluir.innerHTML = '<i class="bi bi-trash3"></i>';
        botaoExcluir.className = "botao-rotas";
        botaoExcluir.addEventListener("click", () => {
            excluirRota(rota.id);
        });

        colunaAcoes.appendChild(botaoEditar);
        colunaAcoes.appendChild(botaoExcluir);

        linha.appendChild(colunaId);
        linha.appendChild(colunaNome);
        linha.appendChild(colunaInicio);
        linha.appendChild(colunaFim);
        linha.appendChild(colunaAcoes);

        tabela.appendChild(linha);
    });
}


function filtrarRotas() {
    const busca = campoBusca.value.trim().toLowerCase();

    const filtradas = rotas.filter((rota) => {
        return (
            String(rota.id).includes(busca) ||
            String(rota.nome).toLowerCase().includes(busca) ||
            String(rota.localizacao_inicial).toLowerCase().includes(busca) ||
            String(rota.localizacao_final).toLowerCase().includes(busca)
        );
    });

    mostrarRotas(filtradas);
}

async function salvarRota() {
    const nome = nomeInput.value.trim();
    const inicio = inicioInput.value.trim();
    const fim = fimInput.value.trim();

    if (!nome || !inicio || !fim) {
        mostrarMensagem("Preencha todos os campos.");
        return;
    }

    // Os nomes enviados precisam corresponder aos nomes esperados pelo PHP.
    const dadosFormulario = new FormData();
    dadosFormulario.append("nome", nome);
    dadosFormulario.append("localizacao_inicial", inicio);
    dadosFormulario.append("localizacao_final", fim);

    let url = "crud_rotas/adicionar.php";

    if (rotaEditando !== null) {
        url = "crud_rotas/editar.php";
        dadosFormulario.append("id", rotaEditando);
    }

    botaoAdicionar.disabled = true;

    try {
        const dados = await requisicaoPHP(url, {
            method: "POST",
            body: dadosFormulario
        });

        mostrarMensagem(dados.message || "Rota salva com sucesso!");

        limparFormulario();
        await carregarRotas();

    } catch (erro) {
        console.error("Erro ao salvar a rota:", erro);
        mostrarMensagem("Erro ao salvar a rota: " + erro.message);
    } finally {
        botaoAdicionar.disabled = false;
    }
}


function editarRota(id) {
    const rota = rotas.find((item) => String(item.id) === String(id));

    if (!rota) {
        mostrarMensagem("Rota não encontrada.");
        return;
    }

    rotaEditando = rota.id;

    nomeInput.value = rota.nome;
    inicioInput.value = rota.localizacao_inicial;
    fimInput.value = rota.localizacao_final;

    botaoAdicionar.textContent = "Salvar alterações";

    const titulo = document.querySelector(".usuario__title");
    if (titulo) {
        titulo.textContent = "Editar rota";
    }

    let cancelar = document.getElementById("btnCancelar");

    if (!cancelar) {
        cancelar = document.createElement("button");
        cancelar.id = "btnCancelar";
        cancelar.type = "button";
        cancelar.textContent = "Cancelar edição";
        cancelar.className = "btn btn-secondary mt-2";

        botaoAdicionar.insertAdjacentElement("afterend", cancelar);

        cancelar.addEventListener("click", limparFormulario);
    }

    cancelar.style.display = "inline-block";

    nomeInput.focus();
}

async function excluirRota(id) {
    const confirmar = confirm(
        "Tem certeza de que deseja excluir esta rota?"
    );

    if (!confirmar) {
        return;
    }

    const dadosFormulario = new FormData();
    dadosFormulario.append("id", id);

    try {
        const dados = await requisicaoPHP("crud_rotas/excluir.php", {
            method: "POST",
            body: dadosFormulario
        });

        mostrarMensagem(dados.message || "Rota excluída com sucesso!");

        if (String(rotaEditando) === String(id)) {
            limparFormulario();
        }

        await carregarRotas();

    } catch (erro) {
        console.error("Erro ao excluir rota:", erro);
        mostrarMensagem("Erro ao excluir a rota: " + erro.message);
    }
}

botaoAdicionar.addEventListener("click", salvarRota);

campoBusca.addEventListener("input", filtrarRotas);

document.addEventListener("DOMContentLoaded", carregarRotas);

if (document.readyState !== "loading") {
    carregarRotas();
}
