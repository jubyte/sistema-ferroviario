// ===============================
// LISTA DE USUÁRIOS
// ===============================

let usuarios = [
    {
        id: 1,
        nome: "Guilherme Goll",
        email: "guilherme@email.com",
        telefone: "(47) 99999-9999",
        tipo: "Administrador",
        status: "Ativo"
    }
];


// ===============================
// PEGAR ELEMENTOS DO HTML
// ===============================

const nomeInput = document.getElementById("nome");
const emailInput = document.getElementById("email");
const telefoneInput = document.getElementById("telefone");
const tipoInput = document.getElementById("tipo");

const botaoAdicionar = document.getElementById("btnAdicionar");

const campoBusca = document.getElementById("buscar");

const tabela = document.getElementById("tabelaUsuarios");


// ===============================
// MOSTRAR USUÁRIOS
// ===============================

function mostrarUsuarios(lista) {

    tabela.innerHTML = "";

    lista.forEach(function(usuario) {

        const linha = document.createElement("tr");

        linha.innerHTML = `
            
            <td>
                ${String(usuario.id).padStart(2, "0")}
            </td>

            <td>
                ${usuario.nome}
            </td>

            <td>
                ${usuario.email}
            </td>

            <td>
                ${usuario.telefone}
            </td>

            <td>

                <div class="d-flex justify-content-between align-items-center">

                    <span class="status-ativo">
                        ● ${usuario.status}
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


// ===============================
// ADICIONAR USUÁRIO
// ===============================

botaoAdicionar.addEventListener("click", function() {

    const nome = nomeInput.value.trim();
    const email = emailInput.value.trim();
    const telefone = telefoneInput.value.trim();
    const tipo = tipoInput.value;


    // Verificar campos

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


    // Criar novo ID

    let novoId = 1;

    if (usuarios.length > 0) {

        novoId = usuarios[usuarios.length - 1].id + 1;

    }


    // Criar usuário

    const novoUsuario = {

        id: novoId,

        nome: nome,

        email: email,

        telefone: telefone,

        tipo: tipo,

        status: "Ativo"

    };


    // Adicionar na lista

    usuarios.push(novoUsuario);


    // Atualizar tabela

    mostrarUsuarios(usuarios);


    // Limpar campos

    nomeInput.value = "";

    emailInput.value = "";

    telefoneInput.value = "";

    tipoInput.value = "";


    alert("Usuário cadastrado com sucesso!");

});


// ===============================
// PESQUISAR USUÁRIO
// ===============================

campoBusca.addEventListener("input", function() {

    const texto = campoBusca.value.toLowerCase().trim();


    const resultados = usuarios.filter(function(usuario) {

        return (

            usuario.nome.toLowerCase().includes(texto)

            ||

            String(usuario.id).includes(texto)

        );

    });


    mostrarUsuarios(resultados);

});


// ===============================
// REMOVER USUÁRIO
// ===============================

function removerUsuario(id) {

    const usuario = usuarios.find(function(usuario) {

        return usuario.id === id;

    });


    if (!usuario) {
        return;
    }


    const confirmar = confirm(
        "Deseja realmente remover o usuário " + usuario.nome + "?"
    );


    if (confirmar) {

        usuarios = usuarios.filter(function(usuario) {

            return usuario.id !== id;

        });


        mostrarUsuarios(usuarios);

    }

}


// ===============================
// EDITAR USUÁRIO
// ===============================

function editarUsuarios(id) {

    const usuario = usuarios.find(function(usuario) {

        return usuario.id === id;

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
        usuario.telefone
    );


    if (novoTelefone === null || novoTelefone.trim() === "") {
        return;
    }


    usuario.nome = novoNome.trim();

    usuario.email = novoEmail.trim();

    usuario.telefone = novoTelefone.trim();


    mostrarUsuarios(usuarios);

}


// ===============================
// INICIAR TABELA
// ===============================

mostrarUsuarios(usuarios);

alert("Bem-vindo(a) ao sistema de gerenciamento de usuários!");