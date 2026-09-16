//lista
let sensores = [];

//pegar elementos do HTML
const nomeInput = document.getElementById("nome");
const tipoInput = document.getElementById("tipo");
const localInput = document.getElementById("local");
const statusInput = document.getElementById("status");
const botaoAdicionar = document.getElementById("btnAdicionar");
const campoBusca = document.getElementById("buscar");
const tabela = document.getElementById("tabelaSensores");
const sensorVazio = document.getElementById("sensorVazio");

//mostrar sensores
function mostrarSensores(lista) {
    tabela.innerHTML = "";

    if (lista.length === 0) {
        sensorVazio.style.display = "block";
    } else {
        sensorVazio.style.display = "none";
    }

    lista.forEach(function(sensor) {
        const linha = document.createElement("tr");
        linha.innerHTML = `
            <td>
                ${String(sensor.id).padStart(2, "0")}
            </td>

            <td>
                ${sensor.nome}
            </td>
            
            <td>
                ${sensor.tipo}
            </td>

            <td>
                ${sensor.local}
            </td>

            <td>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="status-ativo">
                        ● ${sensor.status}
                    </span>

                    <div>

                        <button
                            class="btn-acao"
                            onclick="editarSensor(${sensor.id})">

                            <i class="bi bi-pencil-square icone-color"></i>

                        </button>


                        <button
                            class="btn-acao"
                            onclick="removerSensor(${sensor.id})">

                            <i class="bi bi-trash3 icone-color"></i>

                        </button>

                    </div>

                </div>

            </td>

        `;

        tabela.appendChild(linha);

    });

}


botaoAdicionar.addEventListener("click", function() {
    const nome = nomeInput.value.trim();
    const tipo = tipoInput.value.trim();
    const local = localInput.value.trim();
    const status = statusInput.value;

    if (nome === "") {
        alert("Digite o nome do sensor.");
        nomeInput.focus();
        return;
    }


    if (tipo === "") {
        alert("Digite o tipo do sensor.");
        tipoInput.focus();
        return;
    }


    if (local === "") {
        alert("Digite o local do sensor.");
        localInput.focus();
        return;
    }


    if (status === "") {
        alert("Selecione o status do sensor.");
        statusInput.focus();
        return;
    }


    let novoId = 1;
    if (sensores.length > 0) {
        novoId = sensores[sensores.length - 1].id + 1;
    }


    const novoSensor = {

        id: novoId,
        nome: nome,
        tipo: tipo,
        local: local,
        status: status

    };


    sensores.push(novoSensor);


    mostrarSensores(sensores);


    nomeInput.value = "";
    tipoInput.value = "";
    localInput.value = "";
    statusInput.value = "";


    alert("Sensor cadastrado com sucesso!");

});

//pesquisar sensor
campoBusca.addEventListener("input", function() {
    const texto = campoBusca.value.toLowerCase().trim();
    const sensoresFiltrados = sensores.filter(function(sensor) {
        return (
            sensor.nome.toLowerCase().includes(texto) || String(sensor.id).includes(texto)

        );

    });

    mostrarSensores(resultados);

});

//remover sensor
function removerSensor(id) {
    const sensor = sensores.find(function(sensor) {
        return sensor.id === id;
    });

    if (!sensor) {
        return;
    }

    const confirmar = confirm("Tem certeza que deseja remover o sensor " + sensor.nome + "?");

    if (confirmar) {
        sensores = sensores.filter(function(sensor) {
            return sensor.id !== id;
        });

        mostrarSensores(sensores);
    }
}

//editar sensor
function editarSensor(id) {
    const sensor = sensores.find(function(sensor) {
        return sensor.id === id;
    });

    if (!sensor) {
        return;
    }

    const novoNome = prompt("Digite o novo nome do sensor:", sensor.nome);
    if (novoNome === null || novoNome.trim() === "") {
        return;
    }

    const novoTipo = prompt("Digite o novo tipo do sensor:", sensor.tipo);
    if (novoTipo === null || novoTipo.trim() === "") {
        return;
    }

    const novoLocal = prompt("Digite o novo local do sensor:", sensor.local);
    if (novoLocal === null || novoLocal.trim() === "") {
        return;
    }

    const novoStatus = prompt("Digite o novo status do sensor (Ativo ou Inativo):", sensor.status);
    if (novoStatus === null || novoStatus.trim() === "") {
        return;
    }

    sensor.nome = novoNome.trim();
    sensor.tipo = novoTipo.trim();
    sensor.local = novoLocal.trim();
    sensor.status = novoStatus.trim();

    mostrarSensores(sensores);

}

mostrarSensores(sensores);
