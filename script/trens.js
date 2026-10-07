let trens = [];

const nomeInput = document.getElementById("nome");
const empresaInput = document.getElementById("empresa");
const vagoesInput = document.getElementById("vagoes");
const tipoInput = document.getElementById("tipo");

const botaoAdicionar = document.getElementById("btnAdicionar");
const campoBusca = document.getElementById("buscar");
const tabela = document.getElementById("tabelaTrens");
const trenVazio = document.getElementById("sensorVazio");

// evita que texto digitado vire HTML na tabela
function escapar(texto) {
    const div = document.createElement("div");
    div.textContent = texto;
    return div.innerHTML;
}

function mostrarTrens(lista) {

    tabela.innerHTML = "";

    if (lista.length === 0) {
        trenVazio.style.display = "block";
        return;
    }

    trenVazio.style.display = "none";

    lista.forEach(function(trem) {

        const linha = document.createElement("tr");

        linha.innerHTML = `
            <td>${String(trem.id).padStart(2, "0")}</td>
            <td>${escapar(trem.nome)}</td>
            <td>${escapar(trem.empresa)}</td>
            <td>${trem.numero_vagoes}</td>
            <td>${trem.tipo === "Eletrico" ? "Elétrico" : escapar(trem.tipo)}</td>
            <td>
                <div class="d-flex justify-content-between align-items-center">

                    <span class="status-ativo">
                        ● ${escapar(trem.status)}
                    </span>

                    <div>
                        <button class="btn-acao" onclick="editarTrem(${trem.id})">
                            <i class="bi bi-pencil-square icone-color"></i>
                        </button>

                        <button class="btn-acao" onclick="excluirTrem(${trem.id})">
                            <i class="bi bi-trash3 icone-color"></i>
                        </button>
                    </div>

                </div>
            </td>
        `;

        tabela.appendChild(linha);
    });
}

function carregarTrens() {

    fetch("crud_trens/listar_trem.php")

        .then(function(response) {

            if (!response.ok) {
                throw new Error("Erro HTTP: " + response.status);
            }

            return response.json();
        })

        .then(function(data) {

            if (data.status === "success") {

                trens = data.trens;
                mostrarTrens(trens);

            } else {

                alert(data.message);
            }
        })

        .catch(function(error) {

            console.error(error);
            alert("Erro ao carregar trens.");
        });
}

botaoAdicionar.addEventListener("click", function() {

    const nome = nomeInput.value.trim();
    const empresa = empresaInput.value.trim();
    const vagoes = vagoesInput.value.trim();
    const tipo = tipoInput.value;

    if (nome === "") {
        alert("Digite o nome do trem.");
        nomeInput.focus();
        return;
    }

    if (empresa === "") {
        alert("Digite a empresa.");
        empresaInput.focus();
        return;
    }

    if (vagoes === "" || Number(vagoes) < 1) {
        alert("Digite um número de vagões válido.");
        vagoesInput.focus();
        return;
    }

    if (tipo === "") {
        alert("Selecione o tipo do trem.");
        tipoInput.focus();
        return;
    }

    const dados = new FormData();

    dados.append("nome", nome);
    dados.append("empresa", empresa);
    dados.append("numero_vagoes", vagoes);
    dados.append("tipo", tipo);

    fetch("crud_trens/cadastro.php", {
        method: "POST",
        body: dados
    })

    .then(function(response) {
        return response.json();
    })

    .then(function(data) {

        alert(data.message);

        if (data.status === "success") {

            nomeInput.value = "";
            empresaInput.value = "";
            vagoesInput.value = "";
            tipoInput.value = "";

            carregarTrens();
        }
    })

    .catch(function(error) {

        console.error(error);
        alert("Erro ao conectar com o servidor.");
    });
});

campoBusca.addEventListener("input", function() {

    const texto = campoBusca.value.toLowerCase().trim();

    const resultados = trens.filter(function(trem) {

        return (
            trem.nome.toLowerCase().includes(texto) ||
            String(trem.id).includes(texto)
        );
    });

    mostrarTrens(resultados);
});

function excluirTrem(id) {

    const trem = trens.find(function(trem) {
        return Number(trem.id) === Number(id);
    });

    if (!trem) {
        return;
    }

    const confirmar = confirm("Deseja realmente remover o trem \"" + trem.nome + "\"?");

    if (!confirmar) {
        return;
    }

    const dados = new FormData();
    dados.append("id", id);

    fetch("crud_trens/excluir_trem.php", {
        method: "POST",
        body: dados
    })

    .then(function(response) {
        return response.json();
    })

    .then(function(data) {

        alert(data.message);

        if (data.status === "success") {
            carregarTrens();
        }
    })

    .catch(function(error) {

        console.error(error);
        alert("Erro ao excluir trem.");
    });
}

function editarTrem(id) {

    const trem = trens.find(function(trem) {
        return Number(trem.id) === Number(id);
    });

    if (!trem) {
        return;
    }

    const novoNome = prompt("Digite o novo nome do trem:", trem.nome);

    if (novoNome === null || novoNome.trim() === "") {
        return;
    }

    const novaEmpresa = prompt("Digite a nova empresa:", trem.empresa);

    if (novaEmpresa === null || novaEmpresa.trim() === "") {
        return;
    }

    const novosVagoes = prompt("Digite o novo número de vagões:", trem.numero_vagoes);

    if (novosVagoes === null || novosVagoes.trim() === "" || Number(novosVagoes) < 1) {
        return;
    }

    const novoTipo = prompt("Digite o novo tipo (Eletrico ou Diesel):", trem.tipo);

    if (novoTipo === null || novoTipo.trim() === "") {
        return;
    }

    const dados = new FormData();

    dados.append("id", id);
    dados.append("nome", novoNome.trim());
    dados.append("empresa", novaEmpresa.trim());
    dados.append("numero_vagoes", novosVagoes.trim());
    dados.append("tipo", novoTipo.trim());

    fetch("crud_trens/editar_trem.php", {
        method: "POST",
        body: dados
    })

    .then(function(response) {
        return response.json();
    })

    .then(function(data) {

        alert(data.message);

        if (data.status === "success") {
            carregarTrens();
        }
    })

    .catch(function(error) {

        console.error(error);
        alert("Erro ao editar trem.");
    });
}

carregarTrens();