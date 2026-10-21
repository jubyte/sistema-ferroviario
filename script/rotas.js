let rotas = [];

const nomeInput = document.getElementById("nome");
const descricaoInput = document.getElementById("descricao");
const distanciaInput = document.getElementById("distancia");
const tempoInput = document.getElementById("tempo");
const tremInput = document.getElementById("trem");

const botaoAdicionar = document.getElementById("btnAdicionar");
const campoBusca = document.getElementById("buscar");
const tabela = document.getElementById("tabelaRotas");
const rotaVazia = document.getElementById("rotaVazia");

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

            <td>${rota.nome}</td>

            <td>${rota.descricao}</td>

            <td>${rota.distancia} km</td>

            <td>${rota.tempo} min</td>

            <td>${rota.trem_id}</td>

            <td>
                <div class="d-flex justify-content-between align-items-center">

                    <span class="status-ativo">
                        ● ${rota.status}
                    </span>

                    <div>
                        <button
                            class="btn-acao"
                            onclick="editarRota(${rota.id})">

                            <i class="bi bi-pencil-square icone-color"></i>
                        </button>

                        <button
                            class="btn-acao"
                            onclick="removerRota(${rota.id})">

                            <i class="bi bi-trash3 icone-color"></i>
                        </button>
                    </div>

                </div>
            </td>
        `;

        tabela.appendChild(linha);
    });
}

function carregarRotas() {

    fetch("crud_rotas/listar_rota.php")

        .then(function(response) {

            if (!response.ok) {
                throw new Error(
                    "Erro HTTP: " + response.status
                );
            }

            return response.json();
        })

        .then(function(data) {

            if (data.status === "success") {

                rotas = data.rotas;

                mostrarRotas(rotas);

            } else {

                alert(data.message);
            }
        })

        .catch(function(error) {

            console.error(error);

            alert(
                "Erro ao carregar rotas."
            );
        });
}

botaoAdicionar.addEventListener("click", function() {

    const nome = nomeInput.value.trim();
    const descricao = descricaoInput.value.trim();
    const distancia = distanciaInput.value.trim();
    const tempo = tempoInput.value.trim();
    const trem = tremInput.value.trim();

    if (nome === "") {

        alert(
            "Digite o nome da rota."
        );

        nomeInput.focus();

        return;
    }

    if (descricao === "") {

        alert(
            "Digite a descrição da rota."
        );

        descricaoInput.focus();

        return;
    }

    if (distancia === "") {

        alert(
            "Digite a distância da rota."
        );

        distanciaInput.focus();

        return;
    }

    if (tempo === "") {

        alert(
            "Digite o tempo da rota."
        );

        tempoInput.focus();

        return;
    }

    if (trem === "") {

        alert(
            "Digite o ID do trem."
        );

        tremInput.focus();

        return;
    }

    const dados = new FormData();

    dados.append("nome", nome);
    dados.append("descricao", descricao);
    dados.append("distancia", distancia);
    dados.append("tempo", tempo);
    dados.append("trem_id", trem);

    fetch("crud_rotas/cadastrar_rota.php", {

        method: "POST",
        body: dados
    })

    .then(function(response) {
        return response.json();
    })

    .then(function(data) {

        if (data.status === "success") {

            alert(data.message);

            nomeInput.value = "";
            descricaoInput.value = "";
            distanciaInput.value = "";
            tempoInput.value = "";
            tremInput.value = "";

            carregarRotas();

        } else {

            alert(data.message);
        }
    })

    .catch(function(error) {

        console.error(error);

        alert(
            "Erro ao conectar com o servidor."
        );
    });

});

campoBusca.addEventListener("input", function() {

    const texto =
        campoBusca.value.toLowerCase().trim();

    const resultados = rotas.filter(function(rota) {

        return (
            rota.nome.toLowerCase().includes(texto) ||
            String(rota.id).includes(texto)
        );

    });

    mostrarRotas(resultados);
});

function removerRota(id) {

    const rota = rotas.find(function(rota) {

        return Number(rota.id) === Number(id);

    });

    if (!rota) {
        return;
    }

    const confirmar = confirm(
        "Deseja realmente remover a rota " +
        rota.nome +
        "?"
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

    .then(function(response) {
        return response.json();
    })

    .then(function(data) {

        alert(data.message);

        if (data.status === "success") {
            carregarRotas();
        }
    })

    .catch(function(error) {

        console.error(error);

        alert(
            "Erro ao excluir rota."
        );
    });
}

function editarRota(id) {

    const rota = rotas.find(function(rota) {

        return Number(rota.id) === Number(id);

    });

    if (!rota) {
        return;
    }

    const novoNome = prompt(
        "Digite o novo nome:",
        rota.nome
    );

    if (
        novoNome === null ||
        novoNome.trim() === ""
    ) {
        return;
    }

    const novaDescricao = prompt(
        "Digite a nova descrição:",
        rota.descricao
    );

    if (
        novaDescricao === null ||
        novaDescricao.trim() === ""
    ) {
        return;
    }

    const novaDistancia = prompt(
        "Digite a nova distância:",
        rota.distancia
    );

    if (
        novaDistancia === null ||
        novaDistancia.trim() === ""
    ) {
        return;
    }

    const novoTempo = prompt(
        "Digite o novo tempo:",
        rota.tempo
    );

    if (
        novoTempo === null ||
        novoTempo.trim() === ""
    ) {
        return;
    }

    const dados = new FormData();

    dados.append("id", id);
    dados.append("nome", novoNome.trim());
    dados.append("descricao", novaDescricao.trim());
    dados.append("distancia", novaDistancia.trim());
    dados.append("tempo", novoTempo.trim());

    fetch("crud_rotas/editar_rota.php", {

        method: "POST",
        body: dados
    })

    .then(function(response) {
        return response.json();
    })

    .then(function(data) {

        alert(data.message);

        if (data.status === "success") {
            carregarRotas();
        }
    })

    .catch(function(error) {

        console.error(error);

        alert(
            "Erro ao editar rota."
        );
    });
}

carregarRotas();