
let usuarios = [];
let usuarioEditando = null;

const nomeInput = document.getElementById("nome");
const emailInput = document.getElementById("email");
const telefoneInput = document.getElementById("telefone");
const senhaInput = document.getElementById("senha");
const tipoInput = document.getElementById("tipo");
const cepInput = document.getElementById("cep");
const logradouroInput = document.getElementById("logradouro");
const bairroInput = document.getElementById("bairro");
const cidadeInput = document.getElementById("cidade");
const estadoInput = document.getElementById("estado");

const botaoAdicionar = document.getElementById("btnAdicionar");
const campoBusca = document.getElementById("buscar");
const tabela = document.getElementById("tabelaUsuarios");
const sensorVazio = document.getElementById("sensorVazio");

function formatarCep(valor) {
    const numeros = (valor || "").replace(/\D/g, "").slice(0, 8);
    if (numeros.length <= 5) return numeros;
    return `${numeros.slice(0, 5)}-${numeros.slice(5)}`;
}

function limparCamposEndereco() {
    if (cepInput) cepInput.value = "";
    if (logradouroInput) logradouroInput.value = "";
    if (bairroInput) bairroInput.value = "";
    if (cidadeInput) cidadeInput.value = "";
    if (estadoInput) estadoInput.value = "";
}

async function consultarCepUsuario() {
    const cep = formatarCep(cepInput ? cepInput.value : "");

    if (!cep || !/^\d{5}-\d{3}$/.test(cep)) {
        mostrarMensagem("Informe um CEP válido para preencher os dados do endereço.");
        return;
    }

    try {
        const resposta = await fetch(`https://viacep.com.br/ws/${cep.replace(/\D/g, "")}/json/`);

        if (!resposta.ok) {
            throw new Error("Falha de comunicação com a API de CEP.");
        }

        const dados = await resposta.json();

        if (dados.erro) {
            mostrarMensagem("CEP não encontrado. Verifique o número informado.");
            return;
        }

        if (cepInput) cepInput.value = formatarCep(dados.cep || cep);
        if (logradouroInput) logradouroInput.value = dados.logradouro || "";
        if (bairroInput) bairroInput.value = dados.bairro || "";
        if (cidadeInput) cidadeInput.value = dados.localidade || "";
        if (estadoInput) estadoInput.value = dados.uf || "";
    } catch (erro) {
        console.error("Erro ao consultar CEP do usuário:", erro);
        mostrarMensagem("Não foi possível consultar o CEP neste momento. Verifique a conexão com a internet e tente novamente.");
    }
}

function configurarConsultaCepUsuario() {
    if (!cepInput) {
        return;
    }

    cepInput.addEventListener("input", (event) => {
        event.target.value = formatarCep(event.target.value);
    });

    cepInput.addEventListener("blur", () => {
        if (cepInput.value.trim()) {
            consultarCepUsuario();
        }
    });
}

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

function mostrarMensagem(mensagem) {
    alert(mensagem);
}

function limparFormulario() {
    nomeInput.value = "";
    emailInput.value = "";
    telefoneInput.value = "";
    senhaInput.value = "";
    tipoInput.value = "";
    limparCamposEndereco();

    usuarioEditando = null;

    botaoAdicionar.textContent = "+ ADICIONAR";
    botaoAdicionar.disabled = false;

    const titulo = document.querySelector(".usuario__title");

    if (titulo) {
        titulo.textContent = "Cadastro de Usuários";
    }

    const cancelar = document.getElementById("btnCancelar");

    if (cancelar) {
        cancelar.style.display = "none";
    }
}

async function requisicaoPHP(url, opcoes = {}) {
    const resposta = await fetch(url, opcoes);
    const texto = await resposta.text();

    let dados;

    try {
        dados = JSON.parse(texto);
    } catch (erro) {
        console.error("Resposta recebida do PHP:", texto);
        throw new Error("O PHP não retornou JSON válido.");
    }

    if (!resposta.ok || dados.status !== "success") {
        throw new Error(
            dados.message || "Não foi possível concluir a operação."
        );
    }

    return dados;
}

async function carregarUsuarios() {
    try {
        const dados = await requisicaoPHP(
            "crud_usuarios/listar_usuario.php"
        );

        usuarios = Array.isArray(dados.usuarios)
            ? dados.usuarios
            : [];

        filtrarUsuarios();

    } catch (erro) {
        console.error("Erro ao carregar usuários:", erro);
        mostrarMensagem("Erro ao carregar usuários: " + erro.message);
    }
}

function mostrarUsuarios(lista) {
    tabela.innerHTML = "";

    if (lista.length === 0) {
        if (sensorVazio) {
            sensorVazio.style.display = "block";
        }
        return;
    }

    if (sensorVazio) {
        sensorVazio.style.display = "none";
    }

    lista.forEach(function(usuario) {
        const linha = document.createElement("tr");

        const colunaId = document.createElement("td");
        colunaId.textContent = String(usuario.id).padStart(2, "0");

        const colunaNome = document.createElement("td");
        const linkNome = document.createElement("a");
        linkNome.href = `usuario_detalhes.php?id=${usuario.id}`;
        linkNome.textContent = usuario.nome;
        linkNome.className = "usuario-link";
        colunaNome.appendChild(linkNome);

        const colunaEmail = document.createElement("td");
        colunaEmail.textContent = usuario.email;

        const colunaTelefone = document.createElement("td");
        colunaTelefone.textContent = usuario.telefone;

        const colunaAcoes = document.createElement("td");

        const areaAcoes = document.createElement("div");
        areaAcoes.className =
            "d-flex justify-content-between align-items-center";

        const status = document.createElement("span");
        status.className = "status-ativo";
        status.textContent = "● " + (usuario.status || "Ativo");

        const botoes = document.createElement("div");

        const botaoEditar = document.createElement("button");
        botaoEditar.type = "button";
        botaoEditar.className = "btn-acao";
        botaoEditar.innerHTML =
            '<i class="bi bi-pencil-square icone-color"></i>';

        botaoEditar.addEventListener("click", function() {
            editarUsuario(usuario.id);
        });

        const botaoExcluir = document.createElement("button");
        botaoExcluir.type = "button";
        botaoExcluir.className = "btn-acao";
        botaoExcluir.innerHTML =
            '<i class="bi bi-trash3 icone-color"></i>';

        botaoExcluir.addEventListener("click", function() {
            removerUsuario(usuario.id);
        });

        botoes.appendChild(botaoEditar);
        botoes.appendChild(botaoExcluir);

        areaAcoes.appendChild(status);
        areaAcoes.appendChild(botoes);

        colunaAcoes.appendChild(areaAcoes);

        linha.appendChild(colunaId);
        linha.appendChild(colunaNome);
        linha.appendChild(colunaEmail);
        linha.appendChild(colunaTelefone);
        linha.appendChild(colunaAcoes);

        tabela.appendChild(linha);
    });
}

function filtrarUsuarios() {
    const texto = campoBusca.value.trim().toLowerCase();

    const filtrados = usuarios.filter(function(usuario) {
        return (
            String(usuario.id).includes(texto) ||
            String(usuario.nome).toLowerCase().includes(texto) ||
            String(usuario.email).toLowerCase().includes(texto) ||
            String(usuario.telefone).toLowerCase().includes(texto)
        );
    });

    mostrarUsuarios(filtrados);
}

async function salvarUsuario() {
    if (!exigirPermissaoAdmin()) {
        return;
    }

    const nome = nomeInput.value.trim();
    const email = emailInput.value.trim();
    const telefone = telefoneInput.value.trim();
    const senha = senhaInput.value;
    const tipo = tipoInput.value;
    const cep = formatarCep(cepInput ? cepInput.value : "");
    const logradouro = logradouroInput ? logradouroInput.value.trim() : "";
    const bairro = bairroInput ? bairroInput.value.trim() : "";
    const cidade = cidadeInput ? cidadeInput.value.trim() : "";
    const estado = estadoInput ? estadoInput.value.trim() : "";

    if (!nome || !email || !telefone || !tipo) {
        mostrarMensagem("Preencha nome, e-mail, telefone e tipo.");
        return;
    }

    if (usuarioEditando === null && !senha) {
        mostrarMensagem("Digite a senha do usuário.");
        senhaInput.focus();
        return;
    }

    if (senha && senha.length < 8) {
        mostrarMensagem("A senha deve ter no mínimo 8 caracteres.");
        senhaInput.focus();
        return;
    }

    if (cep && !/^\d{5}-\d{3}$/.test(cep)) {
        mostrarMensagem("CEP inválido. Informe um CEP no formato 00000-000.");
        return;
    }

    const dadosFormulario = new FormData();

    dadosFormulario.append("nome", nome);
    dadosFormulario.append("email", email);
    dadosFormulario.append("telefone", telefone);
    dadosFormulario.append("tipo", tipo);
    dadosFormulario.append("cep", cep);
    dadosFormulario.append("logradouro", logradouro);
    dadosFormulario.append("bairro", bairro);
    dadosFormulario.append("cidade", cidade);
    dadosFormulario.append("estado", estado);

    let url;

    if (usuarioEditando !== null) {
        url = "crud_usuarios/editar_usuario.php";

        dadosFormulario.append("id", usuarioEditando);

    } else {
        url = "crud_usuarios/cadastrar_usuario.php";
        dadosFormulario.append("senha", senha);
    }

    botaoAdicionar.disabled = true;

    try {
        const dados = await requisicaoPHP(url, {
            method: "POST",
            body: dadosFormulario
        });

        mostrarMensagem(
            dados.message ||
            (usuarioEditando !== null
                ? "Usuário atualizado com sucesso!"
                : "Usuário cadastrado com sucesso!")
        );

        limparFormulario();
        await carregarUsuarios();

    } catch (erro) {
        console.error("Erro ao salvar usuário:", erro);
        mostrarMensagem("Erro ao salvar usuário: " + erro.message);
    } finally {
        botaoAdicionar.disabled = false;
    }
}

function editarUsuario(id) {
    if (!exigirPermissaoAdmin()) {
        return;
    }

    const usuario = usuarios.find((item) => String(item.id) === String(id));

    if (!usuario) {
        mostrarMensagem("Usuário não encontrado.");
        return;
    }

    usuarioEditando = usuario.id;

    nomeInput.value = usuario.nome;
    emailInput.value = usuario.email;
    telefoneInput.value = usuario.telefone;
    tipoInput.value = usuario.tipo;
    cepInput.value = usuario.cep || "";
    logradouroInput.value = usuario.logradouro || "";
    bairroInput.value = usuario.bairro || "";
    cidadeInput.value = usuario.cidade || "";
    estadoInput.value = usuario.estado || "";

    senhaInput.value = "";

    botaoAdicionar.textContent = "SALVAR ALTERAÇÕES";

    const titulo = document.querySelector(".usuario__title");
    if (titulo) {
        titulo.textContent = "EDITAR USUÁRIO";
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



async function removerUsuario(id) {
    if (!exigirPermissaoAdmin()) {
        return;
    }

    const usuario = usuarios.find(function(item) {
        return String(item.id) === String(id);
    });

    if (!usuario) {
        mostrarMensagem("Usuário não encontrado.");
        return;
    }

    const confirmar = confirm(
        "Deseja realmente remover o usuário " + usuario.nome + "?"
    );

    if (!confirmar) {
        return;
    }

    const dadosFormulario = new FormData();
    dadosFormulario.append("id", id);

    try {
        const dados = await requisicaoPHP(
            "crud_usuarios/excluir_usuario.php",
            {
                method: "POST",
                body: dadosFormulario
            }
        );

        mostrarMensagem(dados.message || "Usuário excluído com sucesso!");

        if (String(usuarioEditando) === String(id)) {
            limparFormulario();
        }

        await carregarUsuarios();

    } catch (erro) {
        console.error("Erro ao excluir usuário:", erro);
        mostrarMensagem("Erro ao excluir usuário: " + erro.message);
    }
}

botaoAdicionar.addEventListener("click", salvarUsuario);
campoBusca.addEventListener("input", filtrarUsuarios);
configurarConsultaCepUsuario();

carregarUsuarios();