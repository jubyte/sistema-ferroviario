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

    lista.forEach(function(sensor) {

        const linha = document.createElement("tr");
        
        linha.innerHTML = `
            
            <td>${String(sensor.id).padStart(2, "0")}</td>
            <td>${sensor.nome}</td>
            <td>${sensor.tipo}</td>
            <td>${sensor.local}</td>
            <td>
                <div class="d-flex justify-content-between align-items-center">

                        <span class="status-ativo">
                            ● ${sensor.status} 
                        </span>
                    <div>

                        <button class="btn-acao" onclick="editarSensor(${sensor.id})"><i class="bi bi-pencil-square icone-color"></i></button>
                        <button class="btn-acao" onclick="excluirSensor(${sensor.id})"><i class="bi bi-trash3 icone-color"></i></button>

                    </div>

                </div>
            </td>
        `;

        tabela.appendChild(linha);
    });
}



function carregarSensores() {

    fetch("crud_sensores/listar_sensor.php")

        .then(function(response) {

            if (!response.ok) {
                throw new Error("Erro HTTP: " + response.status);
            }

            return response.json();

        })

        .then(function(data) {

            if (data.status === "success") {

                sensores = data.sensores;

                mostrarSensores(sensores);

            } else {

                alert(data.message);

            }

        })

        .catch(function(error) {

            console.error(error);

            alert("Erro ao carregar sensores.");

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



    const dados = new FormData();
    dados.append("nome", nome);
    dados.append("tipo", tipo);
    dados.append("local", local);
    dados.append("status", status);

    fetch("crud_sensores/cadastrar_sensor.php", {
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
            tipoInput.value = "";
            localInput.value = "";
            statusInput.value = "Ativo";
        
            carregarSensores();
        }
    })

    .catch(function(error) {
        console.error(error);
        alert("Erro ao conectar com o servidor.");
    });
});

campoBusca.addEventListener("input", function() {

    const texto = campoBusca.value.toLowerCase().trim();
    const resultados = sensores.filter(function(sensor) {
        return (
            sensor.nome.toLowerCase().includes(texto) ||
            String(sensor.id).includes(texto)
        );
    });

    mostrarSensores(resultados);
});

function excluirSensor(id) {
    const sensor = sensores.find(function(sensor) {
        return Number(sensor.id) === Number(id);
    });

    if (!sensor) {
        return;
    }

    const confirmar = confirm("Deseja realmente remover o sensor \"" + sensor.nome + "?");

    if (!confirmar) {
        return;
    }

    const dados = new FormData();
    dados.append("id", id);

    fetch("crud_sensores/excluir_sensor.php", {
        method: "POST",
        body: dados
    })

    .then(function(response) {
        return response.json();
    })

    .then(function(data) {
        alert(data.message);
        if (data.status === "success") {
            carregarSensores();
        }
    })

    .catch(function(error) {
        console.error(error);
        alert("Erro ao excluir sensor.");
    });
}

function editarSensor(id) {
    const sensor = sensores.find(function(sensor) {
        return Number(sensor.id) === Number(id);
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

    const novoStatus = prompt("Digite o novo status do sensor:", sensor.status);

    if (novoStatus === null || novoStatus.trim() === "") {
        return;
    }

    const dados = new FormData();

    dados.append("id", id);
    dados.append("nome", novoNome.trim());
    dados.append("tipo", novoTipo.trim());
    dados.append("local", novoLocal.trim());
    dados.append("status", novoStatus.trim());

    fetch("crud_sensores/editar_sensor.php", {
        method: "POST",
        body: dados
    })

    .then(function(response) {
        return response.json();
    })

    .then(function(data) {
        alert(data.message);
        if (data.status === "success") {
            carregarSensores();
        }
    })
    .catch(function(error) {
        console.error(error);
        alert("Erro ao editar sensor.");
    });

}

carregarSensores();