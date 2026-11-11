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

function usuarioEhAdministrador() {
    return (document.body.dataset.usuarioTipo || "") === "Administrador";
}

function exigirPermissaoAdmin() {
    if (!usuarioEhAdministrador()) {
        alert("Você não tem permissão para realizar tal ação");
        return false;
    }

    return true;
}

function formatarCep(valor) {
    const numeros = (valor || "").replace(/\D/g, "").slice(0, 8);

    if (numeros.length <= 5) {
        return numeros;
    }

    return `${numeros.slice(0, 5)}-${numeros.slice(5)}`;
}

function extrairCep(texto) {
    if (!texto || typeof texto !== "string") {
        return null;
    }

    const match = texto.match(/\d{5}-\d{3}/);
    return match ? match[0] : null;
}

function montarDescricaoLocalizacao(dados) {
    const partes = [];

    if (dados.cep) {
        partes.push(`CEP: ${formatarCep(dados.cep)}`);
    }

    if (dados.logradouro) {
        partes.push(dados.logradouro);
    }

    if (dados.bairro) {
        partes.push(dados.bairro);
    }

    if (dados.localidade || dados.uf) {
        const cidadeUf = [dados.localidade, dados.uf].filter(Boolean).join("/");
        if (cidadeUf) {
            partes.push(cidadeUf);
        }
    }

    return partes.join(" - ");
}

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

        const localFormatado = extrairCep(sensor.local || "")
            ? `CEP: ${extrairCep(sensor.local || "")}`
            : sensor.local || "";

        linha.innerHTML = `
            <td>${String(sensor.id).padStart(2, "0")}</td>
            <td>${escapar(sensor.nome)}</td>
            <td>${escapar(sensor.tipo)}</td>
            <td>${escapar(localFormatado)}</td>
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

async function consultarCepSensor() {
    const cep = formatarCep(localInput ? localInput.value : "");

    if (!cep || !/^\d{5}-\d{3}$/.test(cep)) {
        return;
    }

    try {
        const resposta = await fetch(`https://viacep.com.br/ws/${cep.replace(/\D/g, "")}/json/`);

        if (!resposta.ok) {
            throw new Error("Falha ao consultar o CEP.");
        }

        const dados = await resposta.json();

        if (dados.erro) {
            alert("CEP não encontrado. Verifique o número informado.");
            return;
        }

        localInput.value = montarDescricaoLocalizacao(dados);
    } catch (erro) {
        console.error("Erro ao consultar CEP do sensor:", erro);
        alert("Não foi possível consultar o CEP neste momento. Verifique a conexão e tente novamente.");
    }
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

if (localInput) {
    localInput.addEventListener("input", function(event) {
        event.target.value = event.target.value.replace(/\s{2,}/g, " ").trimStart();
        const valorAtual = event.target.value.trim();

        if (/^\d{5,5}$/.test(valorAtual.replace(/\D/g, ""))) {
            event.target.value = formatarCep(valorAtual);
        }
    });

    localInput.addEventListener("blur", function() {
        const valor = (localInput.value || "").trim();

        if (valor && /^\d{5}-\d{3}$/.test(formatarCep(valor))) {
            consultarCepSensor();
        }
    });
}

botaoAdicionar.addEventListener("click", function() {
    if (!exigirPermissaoAdmin()) {
        return;
    }

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
                alert(error.message || "Não foi possível editar o sensor.");
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
            alert(error.message || "Não foi possível cadastrar o sensor.");
        });
});

function editarSensor(id) {
    if (!exigirPermissaoAdmin()) {
        return;
    }

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

    botaoAdicionar.textContent = "SALVAR ALTERAÇÕES";

    const titulo = document.querySelector(".usuario__title");

    if (titulo) {
        titulo.textContent = "EDITAR SENSOR";
    }

    let cancelar = document.getElementById("btnCancelar");

    if (!cancelar) {
        cancelar = document.createElement("button");

        cancelar.id = "btnCancelar";
        cancelar.type = "button";
        cancelar.textContent = "CANCELAR ALTERAÇÕES";
        cancelar.className = "btn botao-cancelar w-100 mt-2";

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

    botaoAdicionar.textContent = "+ ADICIONAR";

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
    if (!exigirPermissaoAdmin()) {
        return;
    }

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
            alert(error.message || "Não foi possível excluir o sensor.");
        });
}

carregarListaTrens();
carregarSensores();