let rotas = [];
let trens = [];

const nomeInput = document.getElementById("nome");
const descricaoInput = document.getElementById("descricao");
const distanciaInput = document.getElementById("distancia");
const tempoInput = document.getElementById("tempo_estimado");
const tremInput = document.getElementById("trem_id");

const botaoAdicionar = document.getElementById("btnAdicionar");
const campoBusca = document.getElementById("buscar");
const tabela = document.getElementById("tabelaRotas");
const rotaVazia = document.getElementById("rotaVazia");

// Escapa textos antes de exibi-los na tabela.
function escaparHTML(valor) {
    return String(valor ?? "").replace(/[&<>"']/g, caractere => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;"
    })[caractere]);
}

// Carrega os trens cadastrados para preencher o campo de seleção.
async function carregarTrens() {
    try {
        const resposta = await fetch("crud_trens/listar_trem.php", {
            method: "GET",
            headers: {
                "Accept": "application/json"
            }
        });

        if (!resposta.ok) {
            throw new Error(`Erro HTTP ${resposta.status} ao listar trens.`);
        }

        const dados = await resposta.json();

        if (dados.status !== "success" || !Array.isArray(dados.trens)) {
            throw new Error(dados.message || "Resposta inválida ao listar trens.");
        }

        trens = dados.trens;

        tremInput.innerHTML = '<option value="">Selecione um trem</option>';

        trens.forEach(trem => {
            const opcao = document.createElement("option");
            opcao.value = trem.id;
            opcao.textContent = trem.nome;
            tremInput.appendChild(opcao);
        });

        tremInput.disabled = trens.length === 0;

        if (trens.length === 0) {
            tremInput.innerHTML =
                '<option value="">Cadastre um trem primeiro</option>';
        }
    } catch (erro) {
        console.error("Erro ao carregar trens:", erro);
        tremInput.innerHTML =
            '<option value="">Erro ao carregar trens</option>';
        tremInput.disabled = true;
    }
}

// Mostra as rotas na tabela.
function mostrarRotas(lista) {
    tabela.innerHTML = "";

    if (!lista.length) {
        tabela.innerHTML = `
            <tr>
                <td colspan="8" style="text-align:center;">
                    Nenhuma rota encontrada.
                </td>
            </tr>
        `;

        if (rotaVazia) {
            rotaVazia.style.display = "block";
        }
        return;
    }

    if (rotaVazia) {
        rotaVazia.style.display = "none";
    }

    lista.forEach(rota => {
        const linha = document.createElement("tr");

        const id = escaparHTML(rota.id);
        const nome = escaparHTML(rota.nome);
        const descricao = escaparHTML(rota.descricao);
        const distancia = escaparHTML(rota.distancia);
        const tempo = escaparHTML(rota.tempo ?? rota.tempo_estimado);
        const tremNome = escaparHTML(rota.trem_nome || "Trem não informado");
        const status = escaparHTML(rota.status || "Ativo");

        linha.innerHTML = `
            <td>${id}</td>
            <td>${nome}</td>
            <td>${descricao}</td>
            <td>${distancia}</td>
            <td>${tempo}</td>
            <td>${tremNome}</td>
            <td>${status}</td>
            <td>
                <button type="button" class="btn-editar"
                    data-id="${id}">Editar</button>
                <button type="button" class="btn-excluir"
                    data-id="${id}">Excluir</button>
            </td>
        `;

        tabela.appendChild(linha);
    });
}

// Lista as rotas usando explicitamente GET.
async function carregarRotas() {
    try {
        const resposta = await fetch("crud_rotas/listar_rota.php", {
            method: "GET",
            headers: {
                "Accept": "application/json"
            }
        });

        if (!resposta.ok) {
            throw new Error(`Erro HTTP ${resposta.status} ao listar rotas.`);
        }

        const dados = await resposta.json();

        if (dados.status !== "success" || !Array.isArray(dados.rotas)) {
            throw new Error(dados.message || "Resposta inválida ao listar rotas.");
        }

        rotas = dados.rotas;
        filtrarRotas();
    } catch (erro) {
        console.error("Erro ao carregar rotas:", erro);

        tabela.innerHTML = `
            <tr>
                <td colspan="8" style="text-align:center;">
                    Não foi possível carregar as rotas.
                    Verifique o console e o arquivo listar_rota.php.
                </td>
            </tr>
        `;
    }
}

// Cadastra uma nova rota usando POST.
if (botaoAdicionar) {
    botaoAdicionar.addEventListener("click", async evento => {
        evento.preventDefault();

        const nome = nomeInput.value.trim();
        const descricao = descricaoInput.value.trim();
        const distancia = distanciaInput.value.trim();
        const tempo = tempoInput.value.trim();
        const tremId = tremInput.value;

        if (!nome || !distancia || !tempo || !tremId) {
            alert("Preencha o nome, a distância, o tempo e selecione um trem.");
            return;
        }

        const dados = new FormData();
        dados.append("nome", nome);
        dados.append("descricao", descricao);
        dados.append("distancia", distancia);
        dados.append("tempo", tempo);
        dados.append("trem_id", tremId);

        try {
            const resposta = await fetch("crud_rotas/cadastrar_rota.php", {
                method: "POST",
                body: dados
            });

            if (!resposta.ok) {
                throw new Error(`Erro HTTP ${resposta.status} ao cadastrar rota.`);
            }

            const resultado = await resposta.json();

            if (resultado.status !== "success") {
                throw new Error(resultado.message || "Não foi possível cadastrar a rota.");
            }

            alert(resultado.message || "Rota cadastrada com sucesso!");

            nomeInput.value = "";
            descricaoInput.value = "";
            distanciaInput.value = "";
            tempoInput.value = "";
            tremInput.value = "";

            await carregarRotas();
        } catch (erro) {
            console.error("Erro ao cadastrar rota:", erro);
            alert("Erro ao cadastrar a rota. Confira o console e o PHP.");
        }
    });
}

// Pesquisa por nome, ID ou nome do trem.
function filtrarRotas() {
    const termo = campoBusca.value.trim().toLowerCase();

    const resultado = rotas.filter(rota =>
        String(rota.id ?? "").toLowerCase().includes(termo) ||
        String(rota.nome ?? "").toLowerCase().includes(termo) ||
        String(rota.trem_nome ?? "").toLowerCase().includes(termo)
    );

    mostrarRotas(resultado);
}

if (campoBusca) {
    campoBusca.addEventListener("input", filtrarRotas);
}

// Edita uma rota usando POST.
async function editarRota(id) {
    const rota = rotas.find(item => String(item.id) === String(id));

    if (!rota) {
        alert("Rota não encontrada.");
        return;
    }

    const nome = prompt("Nome da rota:", rota.nome);
    if (nome === null) return;

    const descricao = prompt("Descrição:", rota.descricao || "");
    if (descricao === null) return;

    const distancia = prompt("Distância em km:", rota.distancia);
    if (distancia === null) return;

    const tempo = prompt(
        "Tempo estimado em minutos:",
        rota.tempo ?? rota.tempo_estimado
    );
    if (tempo === null) return;

    const listaTrens = trens
        .map(trem => `${trem.id} - ${trem.nome}`)
        .join("\n");

    const tremId = prompt(
        `Digite o ID do trem associado:\n${listaTrens}`,
        rota.trem_id ?? ""
    );

    if (tremId === null) return;

    if (!trens.some(trem => String(trem.id) === tremId.trim())) {
        alert("Selecione um ID de trem válido conforme a lista.");
        return;
    }

    if (!nome.trim() || !distancia.trim() || !tempo.trim()) {
        alert("Nome, distância e tempo são obrigatórios.");
        return;
    }

    const dados = new FormData();
    dados.append("id", id);
    dados.append("nome", nome.trim());
    dados.append("descricao", descricao.trim());
    dados.append("distancia", distancia.trim());
    dados.append("tempo", tempo.trim());
    dados.append("trem_id", tremId.trim());

    try {
        const resposta = await fetch("crud_rotas/editar_rota.php", {
            method: "POST",
            body: dados
        });

        if (!resposta.ok) {
            throw new Error(`Erro HTTP ${resposta.status} ao editar rota.`);
        }

        const resultado = await resposta.json();

        if (resultado.status !== "success") {
            throw new Error(resultado.message || "Não foi possível editar a rota.");
        }

        alert(resultado.message || "Rota atualizada com sucesso!");
        await carregarRotas();
    } catch (erro) {
        console.error("Erro ao editar rota:", erro);
        alert("Erro ao editar a rota. Confira o console e o PHP.");
    }
}

// Exclui uma rota usando POST.
async function removerRota(id) {
    if (!confirm("Tem certeza de que deseja excluir esta rota?")) {
        return;
    }

    const dados = new FormData();
    dados.append("id", id);

    try {
        const resposta = await fetch("crud_rotas/excluir_rota.php", {
            method: "POST",
            body: dados
        });

        if (!resposta.ok) {
            throw new Error(`Erro HTTP ${resposta.status} ao excluir rota.`);
        }

        const resultado = await resposta.json();

        if (resultado.status !== "success") {
            throw new Error(resultado.message || "Não foi possível excluir a rota.");
        }

        alert(resultado.message || "Rota excluída com sucesso!");
        await carregarRotas();
    } catch (erro) {
        console.error("Erro ao excluir rota:", erro);
        alert("Erro ao excluir a rota. Confira o console e o PHP.");
    }
}

// Trata os cliques nos botões da tabela.
tabela.addEventListener("click", evento => {
    const botao = evento.target.closest("button");
    if (!botao) return;

    const id = botao.dataset.id;

    if (botao.classList.contains("btn-editar")) {
        editarRota(id);
    } else if (botao.classList.contains("btn-excluir")) {
        removerRota(id);
    }
});

// Inicializa a tela.
carregarTrens();
carregarRotas();