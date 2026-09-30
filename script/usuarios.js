const nomeInput = document.getElementById("nome");
const emailInput = document.getElementById("email");
const telefoneInput = document.getElementById("telefone");
const tipoInput = document.getElementById("tipo");
const botaoAdicionar = document.getElementById("btnAdicionar");
const campoBusca = document.getElementById("buscar");
const tabela = document.getElementById("tabelaUsuarios");
const sensorVazio = document.getElementById("sensorVazio");

let usuarios = [];

function mostrarUsuarios(lista) {

    tabela.innerHTML = "";

    if (lista.length === 0) {

        sensorVazio.style.display = "block";
        return;

    }

    sensorVazio.style.display = "none";

    lista.forEach(function(usuario) {

        const linha = document.createElement("tr");

        linha.innerHTML = `
            <td>${String(usuario.id).padStart(2, "0")}</td>

            <td>${usuario.nome}</td>

            <td>${usuario.email}</td>

            <td>${usuario.telefone || "-"}</td>

            <td>

                <div class="d-flex justify-content-between align-items-center">

                    <span class="status-ativo">
                        ● ${usuario.status || "Ativo"}
                    </span>

                    <div>

                        <button
                            class="btn-acao"
                            onclick="editarUsuario(${usuario.id})">

                            <i class="bi bi-pencil-square icone-color"></i>

                        </button>

                        <button
                            class="btn-acao"
                            onclick="removerUsuario(${usuario.id})">

                            <i class="bi bi-trash3 icone-color"></i>

                        </button>

                    </div>

                </div>

            </td>
        `;

        tabela.appendChild(linha);

    });
}


function carregarUsuarios() {

    fetch("crud_usuarios/listar_usuario.php")

        .then(function(response) {

            if (!response.ok) {
                throw new Error("Erro HTTP: " + response.status);
            }

            return response.json();

        })

        .then(function(data) {

            if (data.status === "success") {

                usuarios = data.usuarios;

                mostrarUsuarios(usuarios);

            } else {

                console.error(data.message);

                alert(data.message || "Erro ao carregar usuários.");

            }

        })

        .catch(function(error) {

            console.error("Erro:", error);

            alert("Não foi possível carregar os usuários.");

        });
}


botaoAdicionar.addEventListener("click", function() {

    const nome = nomeInput.value.trim();
    const email = emailInput.value.trim();
    const telefone = telefoneInput.value.trim();
    const tipo = tipoInput.value;


    if (nome === "") {

        alert("Digite o nome do usuário.");
        nomeInput.focus();
        return;

    }


    if (email === "") {

        alert("Digite o e-mail.");
        emailInput.focus();
        return;

    }


    if (telefone === "") {

        alert("Digite o telefone.");
        telefoneInput.focus();
        return;

    }


    if (tipo === "") {

        alert("Selecione o tipo de usuário.");
        tipoInput.focus();
        return;

    }


    const dados = new FormData();

    dados.append("nome", nome);
    dados.append("email", email);
    dados.append("telefone", telefone);
    dados.append("tipo", tipo);


    fetch("crud_usuarios/cadastro.php", {

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
            emailInput.value = "";
            telefoneInput.value = "";
            tipoInput.value = "";

            carregarUsuarios();

        } else {

            alert(data.message || "Erro ao cadastrar usuário.");

        }

    })

    .catch(function(error) {

        console.error("Erro:", error);

        alert("Erro ao conectar com o servidor.");

    });

});


campoBusca.addEventListener("input", function() {

    const texto = campoBusca.value.toLowerCase().trim();

    const resultados = usuarios.filter(function(usuario) {

        return (

            String(usuario.nome)
                .toLowerCase()
                .includes(texto)

            ||

            String(usuario.id)
                .includes(texto)

        );

    });

    mostrarUsuarios(resultados);

});

function removerUsuario(id) {

    const usuario = usuarios.find(function(usuario) {

        return Number(usuario.id) === Number(id);

    });


    if (!usuario) {
        return;
    }


    const confirmar = confirm(
        "Deseja realmente remover o usuário " +
        usuario.nome +
        "?"
    );


    if (!confirmar) {
        return;
    }


    const dados = new FormData();

    dados.append("id", id);


    fetch("crud_usuarios/excluir_usuario.php", {

        method: "POST",
        body: dados

    })

    .then(function(response) {

        return response.json();

    })

    .then(function(data) {

        if (data.status === "success") {

            alert(data.message);

            carregarUsuarios();

        } else {

            alert(data.message || "Erro ao excluir usuário.");

        }

    })

    .catch(function(error) {

        console.error("Erro:", error);

        alert("Erro ao excluir usuário.");

    });

}

function editarUsuario(id) {

    const usuario = usuarios.find(function(usuario) {

        return Number(usuario.id) === Number(id);

    });


    if (!usuario) {
        return;
    }


    const novoNome = prompt(
        "Digite o novo nome:",
        usuario.nome
    );


    if (novoNome === null || novoNome.trim() === "") {
        return;
    }


    const novoEmail = prompt(
        "Digite o novo e-mail:",
        usuario.email
    );


    if (novoEmail === null || novoEmail.trim() === "") {
        return;
    }


    const novoTelefone = prompt(
        "Digite o novo telefone:",
        usuario.telefone || ""
    );


    if (novoTelefone === null || novoTelefone.trim() === "") {
        return;
    }


    const dados = new FormData();

    dados.append("id", id);
    dados.append("nome", novoNome.trim());
    dados.append("email", novoEmail.trim());
    dados.append("telefone", novoTelefone.trim());


    fetch("crud_usuarios/editar_usuario.php", {

        method: "POST",
        body: dados

    })

    .then(function(response) {

        return response.json();

    })

    .then(function(data) {

        if (data.status === "success") {

            alert(data.message);

            carregarUsuarios();

        } else {

            alert(data.message || "Erro ao editar usuário.");

        }

    })

    .catch(function(error) {

        console.error("Erro:", error);

        alert("Erro ao editar usuário.");

    });

}

carregarUsuarios();