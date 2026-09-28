let sensores = [];

const nomeInput = document.getElementById("nome");
const tipoInput = document.getElementById("tipo");
const localInput = document.getElementById("local");
const statusInput = document.getElementById("status");

const botaoAdicionar = document.getElementById("btnAdicionar");
const campoBusca = document.getElementById("buscar");
const tabela = document.getElementById("tabelaSensores");
const sensorVazio = document.getElementById("sensorVazio");

function mostrarSensores(lista) {
    tabela.innerHTML = "";

    if (lista.length === 0) {
        sensorVazio.style.display = "block";
        return;
    }

    sensorVazio.style.display = "none";

    lista.forEach((sensor) => {
        const linha = document.createElement("tr");
        linha.innerHTML = `
            <td>${sensor.id}</td>
            <td>${sensor.nome}</td>
            <td>${sensor.tipo}</td>
            <td>${sensor.local}</td>
            <td>${sensor.status}</td>
            <td>
                <button onclick="editarSensor(${sensor.id})">Editar</button>
                <button onclick="excluirSensor(${sensor.id})">Excluir</button>
            </td> `;

        tabela.appendChild(linha);
    });
}

botaoAdicionar.addEventListener("click", () => {
    const nome = nomeInput.value.trim();
    const tipo = tipoInput.value.trim();
    const local = localInput.value.trim();
    const status = statusInput.value;

    if (!nome || !tipo || !local) {
        alert("Preencha todos os campos.");
        return;
    }

    const sensor = {
        id: sensores.length + 1,
        nome: nome,
        tipo: tipo,
        local: local,
        status: status
    };

    sensores.push(sensor);
    mostrarSensores(sensores);

    nomeInput.value = "";
    tipoInput.value = "";
    localInput.value = "";
});

campoBusca.addEventListener("input", () => {
    const busca = campoBusca.value.toLowerCase();
    const sensoresFiltrados = sensores.filter((sensor) =>
        sensor.nome.toLowerCase().includes(busca) ||
        sensor.tipo.toLowerCase().includes(busca) ||
        sensor.local.toLowerCase().includes(busca)
    );

    mostrarSensores(sensoresFiltrados);
});

function editarSensor(id) {
    const sensor = sensores.find((sensor) => sensor.id === id);

    if (!sensor) {
        return;
    }

    nomeInput.value = sensor.nome;
    tipoInput.value = sensor.tipo;
    localInput.value = sensor.local;
    statusInput.value = sensor.status;

    sensores = sensores.filter((sensor) => sensor.id !== id);
    mostrarSensores(sensores);
}

function excluirSensor(id) {
    sensores = sensores.filter((sensor) => sensor.id !== id);
    mostrarSensores(sensores);
}

mostrarSensores(sensores);