let rotas = [];
let rotaEditando = null;

const nomeInput = document.getElementById("nome");
const inicioInput = document.getElementById("localizacao-inicial");
const fimInput = document.getElementById("localizacao-final");

const botaoAdicionar = document.getElementById("btnAdicionar");
const campoBusca = document.getElementById("buscar");
const tabela = document.getElementById("tabelaRotas");
const sensorVazio = document.getElementById("sensorVazio");

const camposEndereco = {
    inicial: {
        cep: document.getElementById("cep-inicial"),
        logradouro: document.getElementById("logradouro-inicial"),
        bairro: document.getElementById("bairro-inicial"),
        cidade: document.getElementById("cidade-inicial"),
        estado: document.getElementById("estado-inicial"),
        localizacao: document.getElementById("localizacao-inicial")
    },
    final: {
        cep: document.getElementById("cep-final"),
        logradouro: document.getElementById("logradouro-final"),
        bairro: document.getElementById("bairro-final"),
        cidade: document.getElementById("cidade-final"),
        estado: document.getElementById("estado-final"),
        localizacao: document.getElementById("localizacao-final")
    }
};

const modalEndereco = document.getElementById("modalEndereco");
const modalEnderecoTitulo = document.getElementById("modalEnderecoTitulo");
const modalCep = document.getElementById("modalCep");
const modalLogradouro = document.getElementById("modalLogradouro");
const modalBairro = document.getElementById("modalBairro");
const modalCidade = document.getElementById("modalCidade");
const modalEstado = document.getElementById("modalEstado");
const btnFecharModal = document.getElementById("btnFecharModal");

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

function formatarCep(valor) {
    const numeros = (valor || "").replace(/\D/g, "").slice(0, 8);

    if (numeros.length <= 5) {
        return numeros;
    }

    return `${numeros.slice(0, 5)}-${numeros.slice(5)}`;
}

function limparFormulario() {
    nomeInput.value = "";
    inicioInput.value = "";
    fimInput.value = "";

    Object.values(camposEndereco).forEach((campos) => {
        if (campos.cep) campos.cep.value = "";
        if (campos.logradouro) campos.logradouro.value = "";
        if (campos.bairro) campos.bairro.value = "";
        if (campos.cidade) campos.cidade.value = "";
        if (campos.estado) campos.estado.value = "";
        if (campos.localizacao) campos.localizacao.value = "";
    });

    rotaEditando = null;
    botaoAdicionar.textContent = "+ ADICIONAR";

    const titulo = document.querySelector(".usuario__title");
    if (titulo) {
        titulo.textContent = "Cadastro de Rotas";
    }

    const cancelar = document.getElementById("btnCancelar");
    if (cancelar) {
        cancelar.style.display = "none";
    }
}

function obterDadosEndereco(tipo) {
    const campos = camposEndereco[tipo];

    return {
        cep: (campos.cep ? campos.cep.value.trim() : ""),
        logradouro: (campos.logradouro ? campos.logradouro.value.trim() : ""),
        bairro: (campos.bairro ? campos.bairro.value.trim() : ""),
        cidade: (campos.cidade ? campos.cidade.value.trim() : ""),
        estado: (campos.estado ? campos.estado.value.trim() : "")
    };
}

function montarDescricaoEndereco(tipo, dados = null) {
    const dadosEndereco = dados || obterDadosEndereco(tipo);
    const partes = [];

    if (dadosEndereco.cep) {
        partes.push(`CEP ${dadosEndereco.cep}`);
    }

    if (dadosEndereco.logradouro) {
        partes.push(dadosEndereco.logradouro);
    }

    if (dadosEndereco.bairro) {
        partes.push(dadosEndereco.bairro);
    }

    if (dadosEndereco.cidade || dadosEndereco.estado) {
        const cidadeEstado = [dadosEndereco.cidade, dadosEndereco.estado]
            .filter(Boolean)
            .join("/");

        if (cidadeEstado) {
            partes.push(cidadeEstado);
        }
    }

    const descricao = partes.join(" - ");

    if (camposEndereco[tipo].localizacao) {
        camposEndereco[tipo].localizacao.value = descricao;
    }

    return descricao;
}

function atualizarCamposEndereco(tipo, dados) {
    const campos = camposEndereco[tipo];

    if (campos.cep) {
        campos.cep.value = formatarCep(dados.cep || "");
    }

    if (campos.logradouro) {
        campos.logradouro.value = dados.logradouro || "";
    }

    if (campos.bairro) {
        campos.bairro.value = dados.bairro || "";
    }

    if (campos.cidade) {
        campos.cidade.value = dados.localidade || "";
    }

    if (campos.estado) {
        campos.estado.value = dados.uf || "";
    }

    montarDescricaoEndereco(tipo, {
        cep: dados.cep || "",
        logradouro: dados.logradouro || "",
        bairro: dados.bairro || "",
        cidade: dados.localidade || "",
        estado: dados.uf || ""
    });
}

function abrirModalEndereco(tipo, dados) {
    const rotulo = tipo === "inicial" ? "Detalhes do endereço inicial" : "Detalhes do endereço final";
    modalEnderecoTitulo.textContent = rotulo;
    modalCep.textContent = dados.cep || "Não informado";
    modalLogradouro.textContent = dados.logradouro || "Não informado";
    modalBairro.textContent = dados.bairro || "Não informado";
    modalCidade.textContent = dados.localidade || "Não informado";
    modalEstado.textContent = dados.uf || "Não informado";
    modalEndereco.classList.add("is-visible");
    modalEndereco.setAttribute("aria-hidden", "false");
}

function extrairCep(texto) {
    if (!texto || typeof texto !== "string") {
        return null;
    }

    const match = texto.match(/\d{5}-\d{3}/);
    return match ? match[0] : null;
}

async function abrirModalComCepInformado(cep, rotulo = "Detalhes do endereço") {
    const valorCep = formatarCep(cep || "");

    if (!valorCep || !/^\d{5}-\d{3}$/.test(valorCep)) {
        mostrarMensagem("Nenhum CEP válido foi encontrado para abrir os detalhes do endereço.");
        return;
    }

    try {
        const resposta = await fetch(`https://viacep.com.br/ws/${valorCep.replace(/\D/g, "")}/json/`);

        if (!resposta.ok) {
            throw new Error("Falha de comunicação com a API de CEP.");
        }

        const dados = await resposta.json();

        if (dados.erro) {
            mostrarMensagem("CEP não encontrado.");
            return;
        }

        modalEnderecoTitulo.textContent = rotulo;
        modalCep.textContent = dados.cep || "Não informado";
        modalLogradouro.textContent = dados.logradouro || "Não informado";
        modalBairro.textContent = dados.bairro || "Não informado";
        modalCidade.textContent = dados.localidade || "Não informado";
        modalEstado.textContent = dados.uf || "Não informado";
        modalEndereco.classList.add("is-visible");
        modalEndereco.setAttribute("aria-hidden", "false");
    } catch (erro) {
        console.error("Erro ao abrir modal do CEP da tabela:", erro);
        mostrarMensagem("Não foi possível consultar os detalhes do CEP selecionado.");
    }
}

function fecharModalEndereco() {
    modalEndereco.classList.remove("is-visible");
    modalEndereco.setAttribute("aria-hidden", "true");
}

async function consultarCep(tipo) {
    const cepInput = camposEndereco[tipo].cep;
    const cep = formatarCep(cepInput.value);

    if (!cep || !/^\d{5}-\d{3}$/.test(cep)) {
        mostrarMensagem("Informe um CEP válido para consultar o endereço.");
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

        atualizarCamposEndereco(tipo, dados);
        abrirModalEndereco(tipo, dados);
    } catch (erro) {
        console.error("Erro ao consultar CEP:", erro);
        mostrarMensagem("Não foi possível consultar o CEP neste momento. Verifique a conexão com a internet e tente novamente.");
    }
}

function configurarConsultasCep() {
    Object.keys(camposEndereco).forEach((tipo) => {
        const entradaCep = camposEndereco[tipo].cep;

        if (entradaCep) {
            entradaCep.addEventListener("input", (event) => {
                event.target.value = formatarCep(event.target.value);
            });

            entradaCep.addEventListener("keydown", (event) => {
                if (event.key === "Enter") {
                    event.preventDefault();
                    consultarCep(tipo);
                }
            });
        }
    });

    if (btnFecharModal) {
        btnFecharModal.addEventListener("click", fecharModalEndereco);
    }

    if (modalEndereco) {
        modalEndereco.addEventListener("click", (event) => {
            if (event.target === modalEndereco) {
                fecharModalEndereco();
            }
        });
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
        throw new Error(
            "O PHP não retornou JSON válido. Confira o console (F12)."
        );
    }

    if (!resposta.ok || dados.status !== "success") {
        console.error("Erro retornado pelo PHP:", dados);

        throw new Error(
            dados.detalhe ||
            dados.message ||
            "Não foi possível concluir a operação."
        );
    }

    return dados;
}

async function carregarRotas() {
    try {
        const dados = await requisicaoPHP("crud_rotas/listar.php");

        rotas = Array.isArray(dados.rotas) ? dados.rotas : [];

        mostrarRotas(rotas);
    } catch (erro) {
        console.error("Erro ao carregar rotas:", erro);
        mostrarMensagem("Erro ao carregar as rotas: " + erro.message);
    }
}

function mostrarRotas(lista) {
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

    lista.forEach((rota) => {
        const linha = document.createElement("tr");

        const colunaId = document.createElement("td");
        colunaId.textContent = rota.id;

        const colunaNome = document.createElement("td");
        colunaNome.textContent = rota.nome;

        const colunaInicio = document.createElement("td");
        const cepInicio = extrairCep(rota.localizacao_inicial || "");
        if (cepInicio) {
            const spanInicio = document.createElement("span");
            spanInicio.textContent = rota.localizacao_inicial;
            spanInicio.style.cursor = "pointer";
            spanInicio.style.textDecoration = "underline";
            spanInicio.addEventListener("click", () => abrirModalComCepInformado(cepInicio, "Detalhes do endereço inicial"));
            colunaInicio.appendChild(spanInicio);
        } else {
            colunaInicio.textContent = rota.localizacao_inicial;
        }

        const colunaFim = document.createElement("td");
        const cepFim = extrairCep(rota.localizacao_final || "");
        if (cepFim) {
            const spanFim = document.createElement("span");
            spanFim.textContent = rota.localizacao_final;
            spanFim.style.cursor = "pointer";
            spanFim.style.textDecoration = "underline";
            spanFim.addEventListener("click", () => abrirModalComCepInformado(cepFim, "Detalhes do endereço final"));
            colunaFim.appendChild(spanFim);
        } else {
            colunaFim.textContent = rota.localizacao_final;
        }

        const colunaAcoes = document.createElement("td");

        const botaoEditar = document.createElement("button");
        botaoEditar.type = "button";
        botaoEditar.innerHTML = '<i class="bi bi-pencil-square"></i>';
        botaoEditar.className = "botao-rotas";
        botaoEditar.addEventListener("click", () => {
            editarRota(rota.id);
        });

        const botaoExcluir = document.createElement("button");
        botaoExcluir.type = "button";
        botaoExcluir.innerHTML = '<i class="bi bi-trash3"></i>';
        botaoExcluir.className = "botao-rotas";
        botaoExcluir.addEventListener("click", () => {
            excluirRota(rota.id);
        });

        colunaAcoes.appendChild(botaoEditar);
        colunaAcoes.appendChild(botaoExcluir);

        linha.appendChild(colunaId);
        linha.appendChild(colunaNome);
        linha.appendChild(colunaInicio);
        linha.appendChild(colunaFim);
        linha.appendChild(colunaAcoes);

        tabela.appendChild(linha);
    });
}

function filtrarRotas() {
    const busca = campoBusca.value.trim().toLowerCase();

    const filtradas = rotas.filter((rota) => {
        return (
            String(rota.id).includes(busca) ||
            String(rota.nome).toLowerCase().includes(busca) ||
            String(rota.localizacao_inicial).toLowerCase().includes(busca) ||
            String(rota.localizacao_final).toLowerCase().includes(busca)
        );
    });

    mostrarRotas(filtradas);
}

async function salvarRota() {
    if (!exigirPermissaoAdmin()) {
        return;
    }

    const nome = nomeInput.value.trim();
    const inicio = montarDescricaoEndereco("inicial").trim();
    const fim = montarDescricaoEndereco("final").trim();

    if (!nome || !inicio || !fim) {
        mostrarMensagem("Preencha todos os campos e consulte os CEPs para completar os endereços.");
        return;
    }

    const dadosFormulario = new FormData();
    dadosFormulario.append("nome", nome);
    dadosFormulario.append("localizacao_inicial", inicio);
    dadosFormulario.append("localizacao_final", fim);

    let url = "crud_rotas/adicionar.php";

    if (rotaEditando !== null) {
        url = "crud_rotas/editar.php";
        dadosFormulario.append("id", rotaEditando);
    }

    botaoAdicionar.disabled = true;

    try {
        const dados = await requisicaoPHP(url, {
            method: "POST",
            body: dadosFormulario
        });

        mostrarMensagem(dados.message || "Rota salva com sucesso!");

        limparFormulario();
        await carregarRotas();

    } catch (erro) {
        console.error("Erro ao salvar a rota:", erro);
        mostrarMensagem("Erro ao salvar a rota: " + erro.message);
    } finally {
        botaoAdicionar.disabled = false;
    }
}

function editarRota(id) {
    if (!exigirPermissaoAdmin()) {
        return;
    }

    const rota = rotas.find((item) => String(item.id) === String(id));

    if (!rota) {
        mostrarMensagem("Rota não encontrada.");
        return;
    }

    rotaEditando = rota.id;

    nomeInput.value = rota.nome;
    inicioInput.value = rota.localizacao_inicial;
    fimInput.value = rota.localizacao_final;

    botaoAdicionar.textContent = "SALVAR ALTERAÇÕES";

    const titulo = document.querySelector(".usuario__title");
    if (titulo) {
        titulo.textContent = "EDITAR ROTA";
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

async function excluirRota(id) {
    if (!exigirPermissaoAdmin()) {
        return;
    }

    const confirmar = confirm("Tem certeza de que deseja excluir esta rota?");

    if (!confirmar) {
        return;
    }

    const dadosFormulario = new FormData();
    dadosFormulario.append("id", id);

    try {
        const dados = await requisicaoPHP("crud_rotas/excluir.php", {
            method: "POST",
            body: dadosFormulario
        });

        mostrarMensagem(dados.message || "Rota excluída com sucesso!");

        if (String(rotaEditando) === String(id)) {
            limparFormulario();
        }

        await carregarRotas();

    } catch (erro) {
        console.error("Erro ao excluir rota:", erro);
        mostrarMensagem("Erro ao excluir a rota: " + erro.message);
    }
}

botaoAdicionar.addEventListener("click", salvarRota);
campoBusca.addEventListener("input", filtrarRotas);
configurarConsultasCep();

document.addEventListener("DOMContentLoaded", carregarRotas);

if (document.readyState !== "loading") {
    carregarRotas();
}