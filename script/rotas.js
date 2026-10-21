let rotas = [];

const nomeInput = document.getElementById("nome");
const descricaoInput = document.getElementById("descricao");
const distanciaInput = document.getElementById("distancia");
const tempoInput = document.getElementById("tempo_estimado");
const tremInput = document.getElementById("trem_id");

const botaoAdicionar = document.getElementById("btnAdicionar");
const campoBusca = document.getElementById("buscar");
const tabela = document.getElementById("tabelaRotas");
const rotaVazia = document.getElementById("rotaVazia");

function escapar(texto) {
    const div = document.createElement("div");
    div.textContent = texto ?? "";
    return div.innerHTML;
}

function tratarResposta(response) {
    return response.text().then(function(texto) {
        let data;

        try {
            data = JSON.parse(texto);
        } catch {
            console.error("Resposta recebida do PHP:", texto);
            throw new Error("O servidor não retornou JSON válido.");
        }

        if (!response.ok) {
            throw new Error(data.message || "Erro HTTP: " + response.status);
        }

        return data;
    });
}

function mostrarRotas(lista) {
    tabela.innerHTML = "";

    if (lista.length === 0) {
        rotaVazia.style.display = "block";
        return;
    }

    rotaVazia.style.display = "none";

    lista.forEach(function(rota) {

        const linha = document.createElement("tr");

        linha.innerHTML = `
            <td>${String(rota.id).padStart(2, "0")}</td>

            <td>${escapar(rota.nome)}</td>

            <td>${escapar(rota.descricao)}</td>

            <td>${escapar(rota.distancia)} km</td>

            <td>${escapar(rota.tempo_estimado)} min</td>

            <td>Trem ${escapar(rota.trem_id)}</td>

            <td>
                ${new Date(rota.data_criacao).toLocaleDateString("pt-BR")}
            </td>

            <td>
                <button
                    type="button"
                    class="btn-acao"
                    onclick="editarRota(${Number(rota.id)})"
                    aria-label="Editar rota">

                    <i class="bi bi-pencil-square"></i>
                </button>

                <button
                    type="button"
                    class="btn-acao"
                    onclick="excluirRota(${Number(rota.id)})"
                    aria-label="Excluir rota">

                    <i class="bi bi-trash3"></i>
                </button>
            </td>
        `;

        tabela.appendChild(linha);
    });
}

function aplicarBusca() {

    const texto = campoBusca.value.toLowerCase().trim();

    const resultados = rotas.filter(function(rota) {

        return (
            String(rota.nome).toLowerCase().includes(texto) ||
            String(rota.id).includes(texto)
        );

    });

    mostrarRotas(resultados);
}

function carregarRotas() {

    fetch("crud_rotas/listar_rota.php")

        .then(tratarResposta)

        .then(function(data) {

            if (
                data.status === "success" &&
                Array.isArray(data.rotas)
            ) {

                rotas = data.rotas;

                aplicarBusca();

            } else {

                throw new Error(
                    data.message || "Erro ao carregar rotas."
                );
            }
        })

        .catch(function(error) {

            console.error("Erro ao carregar rotas:", error);

            alert("Não foi possível carregar as rotas.");
        });
}

campoBusca.addEventListener("input", aplicarBusca);

botaoAdicionar.addEventListener("click", function() {

    const nome = nomeInput.value.trim();
    const descricao = descricaoInput.value.trim();
    const distancia = distanciaInput.value.trim();
    const tempo = tempoInput.value.trim();
    const tremId = tremInput.value.trim();

    if (!nome) {
        alert("Digite o nome da rota.");
        nomeInput.focus();
        return;
    }

    if (!descricao) {
        alert("Digite a descrição da rota.");
        descricaoInput.focus();
        return;
    }

    if (!distancia || Number(distancia) <= 0) {
        alert("Digite uma distância válida.");
        distanciaInput.focus();
        return;
    }

    if (!tempo || Number(tempo) <= 0) {
        alert("Digite o tempo previsto da rota.");
        tempoInput.focus();
        return;
    }

    if (!tremId || Number(tremId) <= 0) {
        alert("Informe o ID do trem.");
        tremInput.focus();
        return;
    }

    const dados = new FormData();

    dados.append("nome", nome);
    dados.append("descricao", descricao);
    dados.append("distancia", distancia);
    dados.append("tempo_estimado", tempo);
    dados.append("trem_id", tremId);

    fetch("crud_rotas/cadastrar_rota.php", {

        method: "POST",
        body: dados

    })

        .then(tratarResposta)

        .then(function(data) {

            alert(data.message);

            if (data.status === "success") {

                nomeInput.value = "";
                descricaoInput.value = "";
                distanciaInput.value = "";
                tempoInput.value = "";
                tremInput.value = "";

                carregarRotas();
            }
        })

        .catch(function(error) {

            console.error("Erro ao cadastrar rota:", error);

            alert(
                error.message ||
                "Não foi possível cadastrar a rota."
            );
        });
});

function editarRota(id) {

    const rota = rotas.find(function(item) {
        return Number(item.id) === Number(id);
    });

    if (!rota) {
        alert("Rota não encontrada.");
        return;
    }

    const nome = prompt(
        "Nome da rota:",
        rota.nome
    );

    if (nome === null || nome.trim() === "") {
        return;
    }

    const descricao = prompt(
        "Descrição da rota:",
        rota.descricao
    );

    if (descricao === null || descricao.trim() === "") {
        return;
    }

    const distancia = prompt(
        "Distância em quilômetros:",
        rota.distancia
    );

    if (distancia === null || Number(distancia) <= 0) {
        return;
    }

    const tempo = prompt(
        "Tempo previsto em minutos:",
        rota.tempo_estimado
    );

    if (tempo === null || Number(tempo) <= 0) {
        return;
    }

    const tremId = prompt(
        "ID do trem vinculado:",
        rota.trem_id
    );

    if (
        tremId === null ||
        tremId.trim() === "" ||
        Number(tremId) <= 0
    ) {
        return;
    }

    const dados = new FormData();

    dados.append("id", id);
    dados.append("nome", nome.trim());
    dados.append("descricao", descricao.trim());
    dados.append("distancia", distancia.trim());
    dados.append("tempo_estimado", tempo.trim());
    dados.append("trem_id", tremId.trim());

    fetch("crud_rotas/editar_rota.php", {

        method: "POST",
        body: dados

    })

        .then(tratarResposta)

        .then(function(data) {

            alert(data.message);

            if (data.status === "success") {
                carregarRotas();
            }
        })

        .catch(function(error) {

            console.error("Erro ao editar rota:", error);

            alert(
                error.message ||
                "Não foi possível editar a rota."
            );
        });
}

function excluirRota(id) {

    const rota = rotas.find(function(item) {
        return Number(item.id) === Number(id);
    });

    if (!rota) {
        alert("Rota não encontrada.");
        return;
    }

    const confirmar = confirm(
        'Deseja realmente excluir a rota "' +
        rota.nome +
        '"?'
    );

    if (!confirmar) {
        return;
    }

    const dados = new FormData();

    dados.append("id", id);

    fetch("crud_rotas/excluir_rota.php", {

        method: "POST",
        body: dados

    })

        .then(tratarResposta)

        .then(function(data) {

            alert(data.message);

            if (data.status === "success") {
                carregarRotas();
            }
        })

        .catch(function(error) {

            console.error("Erro ao excluir rota:", error);

            alert(
                error.message ||
                "Não foi possível excluir a rota."
            );
        });
}

carregarRotas();