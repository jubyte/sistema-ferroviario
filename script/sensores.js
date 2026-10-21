let sensores = [];
let trensDisponiveis = [];

const nomeInput = document.getElementById("nome");
const tipoInput = document.getElementById("tipo");
const localInput = document.getElementById("local");
const tremInput = document.getElementById("trem_id");
const statusInput = document.getElementById("status");

const botaoAdicionar = document.getElementById("btnAdicionar");
const campoBusca = document.getElementById("buscar");
const tabela = document.getElementById("tabelaSensores");
const sensorVazio = document.getElementById("sensorVazio");

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

function carregarListaTrens() {
    fetch("crud_sensores/listar_trens.php")
        .then(tratarResposta)
        .then(function(data) {
            if (data.status !== "success" || !Array.isArray(data.trens)) {
                throw new Error(data.message || "Não foi possível carregar os trens.");
            }

            trensDisponiveis = data.trens;

            tremInput.innerHTML =
                '<option value="">Nenhum trem associado</option>';

            trensDisponiveis.forEach(function(trem) {
                const opcao = document.createElement("option");
                opcao.value = trem.id;
                opcao.textContent = trem.nome;
                tremInput.appendChild(opcao);
            });
        })
        .catch(function(error) {
            console.error("Erro ao carregar trens:", error);
        });
}

function carregarSensores() {
    fetch("crud_sensores/listar_sensor.php")
        .then(tratarResposta)
        .then(function(data) {
            if (data.status === "success" && Array.isArray(data.sensores)) {
                sensores = data.sensores;
                aplicarBusca();
            } else {
                throw new Error(data.message || "Erro ao carregar sensores.");
            }
        })
        .catch(function(error) {
            console.error("Erro ao carregar sensores:", error);
            alert("Não foi possível carregar os sensores.");
        });
}

function mostrarSensores(lista) {
    tabela.innerHTML = "";

    if (lista.length === 0) {
        sensorVazio.style.display = "block";
        return;
    }

    sensorVazio.style.display = "none";

    lista.forEach(function(sensor) {
        const linha = document.createElement("tr");

        linha.innerHTML = `
            <td>${String(sensor.id).padStart(2, "0")}</td>
            <td>${escapar(sensor.nome)}</td>
            <td>${escapar(sensor.tipo)}</td>
            <td>${escapar(sensor.local)}</td>
            <td>${escapar(sensor.trem_nome || "Não associado")}</td>
            <td>${escapar(sensor.status)}</td>
            <td>
                <button type="button" class="btn-acao"
                    onclick="editarSensor(${Number(sensor.id)})"
                    aria-label="Editar sensor">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button type="button" class="btn-acao"
                    onclick="excluirSensor(${Number(sensor.id)})"
                    aria-label="Excluir sensor">
                    <i class="bi bi-trash3"></i>
                </button>
            </td>
        `;

        tabela.appendChild(linha);
    });
}

function aplicarBusca() {
    const texto = campoBusca.value.toLowerCase().trim();

    const resultados = sensores.filter(function(sensor) {
        return String(sensor.nome).toLowerCase().includes(texto) ||
            String(sensor.id).includes(texto);
    });

    mostrarSensores(resultados);
}

campoBusca.addEventListener("input", aplicarBusca);

botaoAdicionar.addEventListener("click", function() {
    const nome = nomeInput.value.trim();
    const tipo = tipoInput.value;
    const local = localInput.value.trim();
    const tremId = tremInput.value;
    const status = statusInput.value;

    if (!nome || !tipo || !local) {
        alert("Preencha o nome, o tipo e a localização do sensor.");
        return;
    }

    const dados = new FormData();
    dados.append("nome", nome);
    dados.append("tipo", tipo);
    dados.append("local", local);
    dados.append("trem_id", tremId);
    dados.append("status", status);

    fetch("crud_sensores/cadastrar_sensor.php", {
        method: "POST",
        body: dados
    })
        .then(tratarResposta)
        .then(function(data) {
            alert(data.message || "Operação concluída.");

            if (data.status === "success") {
                nomeInput.value = "";
                tipoInput.value = "";
                localInput.value = "";
                tremInput.value = "";
                statusInput.value = "Ativo";

                carregarSensores();
            }
        })
        .catch(function(error) {
            console.error("Erro ao cadastrar sensor:", error);
            alert("Não foi possível cadastrar o sensor.");
        });
});

function editarSensor(id) {
    const sensor = sensores.find(function(item) {
        return Number(item.id) === Number(id);
    });

    if (!sensor) {
        alert("Sensor não encontrado.");
        return;
    }

    const nome = prompt("Nome do sensor:", sensor.nome);
    if (nome === null) return;

    const tipos = [
        "Velocidade", "Localizacao", "Energia",
        "Temperatura", "Presenca", "Falha"
    ];

    const tipoAtual = prompt(
        "Tipo (" + tipos.join(", ") + "):",
        sensor.tipo
    );
    if (tipoAtual === null) return;

    const tipo = tipoAtual.trim();
    if (!tipos.includes(tipo)) {
        alert("Tipo de sensor inválido.");
        return;
    }

    const local = prompt("Localização:", sensor.local);
    if (local === null) return;

    if (!nome.trim() || !local.trim()) {
        alert("Nome e localização são obrigatórios.");
        return;
    }

    const tremAtual = sensor.trem_id ?? "";
    const escolha = prompt(
        "Digite o ID do trem ou deixe vazio para não associar.\n" +
        trensDisponiveis.map(t => `${t.id} - ${t.nome}`).join("\n"),
        tremAtual
    );

    if (escolha === null) return;

    if (
        escolha.trim() !== "" &&
        !trensDisponiveis.some(t => String(t.id) === escolha.trim())
    ) {
        alert("Selecione um ID de trem existente.");
        return;
    }

    const status = prompt(
        "Status (Ativo ou Inativo):",
        sensor.status
    );

    if (status === null) return;

    if (!["Ativo", "Inativo"].includes(status.trim())) {
        alert("Status inválido.");
        return;
    }

    const dados = new FormData();
    dados.append("id", id);
    dados.append("nome", nome.trim());
    dados.append("tipo", tipo);
    dados.append("local", local.trim());
    dados.append("trem_id", escolha.trim());
    dados.append("status", status.trim());

    fetch("crud_sensores/editar_sensor.php", {
        method: "POST",
        body: dados
    })
        .then(tratarResposta)
        .then(function(data) {
            alert(data.message || "Operação concluída.");

            if (data.status === "success") {
                carregarSensores();
            }
        })
        .catch(function(error) {
            console.error("Erro ao editar sensor:", error);
            alert("Não foi possível editar o sensor.");
        });
}

function excluirSensor(id) {
    const sensor = sensores.find(function(item) {
        return Number(item.id) === Number(id);
    });

    if (!sensor) {
        alert("Sensor não encontrado.");
        return;
    }

    if (!confirm(`Deseja excluir o sensor "${sensor.nome}"?`)) {
        return;
    }

    const dados = new FormData();
    dados.append("id", id);

    fetch("crud_sensores/excluir_sensor.php", {
        method: "POST",
        body: dados
    })
        .then(tratarResposta)
        .then(function(data) {
            alert(data.message || "Operação concluída.");

            if (data.status === "success") {
                carregarSensores();
            }
        })
        .catch(function(error) {
            console.error("Erro ao excluir sensor:", error);
            alert("Não foi possível excluir o sensor.");
        });
}

carregarListaTrens();
carregarSensores();