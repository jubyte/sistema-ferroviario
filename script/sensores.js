let sensores = [];
let trensDisponiveis = [];
let sensorEditando = null;

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

    // Se estiver editando, envia pro o PHP de edição
    if (sensorEditando !== null) {
        dados.append("id", sensorEditando);

        fetch("crud_sensores/editar_sensor.php", {
            method: "POST",
            body: dados
        })
            .then(tratarResposta)
            .then(function(data) {
                alert(data.message || "Sensor atualizado com sucesso.");

                if (data.status === "success") {
                    limparFormulario();
                    carregarSensores();
                }
            })
            .catch(function(error) {
                console.error("Erro ao editar sensor:", error);
                alert("Não foi possível editar o sensor.");
            });

        return;
    }

    // Cadastro normal
    fetch("crud_sensores/cadastrar_sensor.php", {
        method: "POST",
        body: dados
    })
        .then(tratarResposta)
        .then(function(data) {
            alert(data.message || "Sensor cadastrado com sucesso.");

            if (data.status === "success") {
                limparFormulario();
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

    sensorEditando = sensor.id;

    nomeInput.value = sensor.nome;
    tipoInput.value = sensor.tipo;
    localInput.value = sensor.local;
    tremInput.value = sensor.trem_id ?? "";
    statusInput.value = sensor.status;

    botaoAdicionar.textContent = "Salvar alterações";

    const titulo = document.querySelector(".usuario__title");

    if (titulo) {
        titulo.textContent = "Editar sensor";
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

function limparFormulario() {
    sensorEditando = null;

    nomeInput.value = "";
    tipoInput.value = "";
    localInput.value = "";
    tremInput.value = "";
    statusInput.value = "Ativo";

    botaoAdicionar.textContent = "Adicionar";

    const titulo = document.querySelector(".usuario__title");

    if (titulo) {
        titulo.textContent = "Adicionar sensor";
    }

    const cancelar = document.getElementById("btnCancelar");

    if (cancelar) {
        cancelar.style.display = "none";
    }
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