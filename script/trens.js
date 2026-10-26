let trens = [];
let usuarios = [];
let tremEditando = null;

const nomeInput = document.getElementById("nome");
const empresaInput = document.getElementById("empresa");
const vagoesInput = document.getElementById("vagoes");
const tipoInput = document.getElementById("tipo");
const responsavelInput = document.getElementById("responsavel_id");

const botaoAdicionar = document.getElementById("btnAdicionar");
const campoBusca = document.getElementById("buscar");
const tabela = document.getElementById("tabelaTrens");
const trenVazio = document.getElementById("sensorVazio");

function escapar(texto) {
    const div = document.createElement("div");
    div.textContent = texto ?? "";
    return div.innerHTML;
}

function tratarResposta(response) {
    if (!response.ok) {
        throw new Error("Erro HTTP: " + response.status);
    }

    return response.json();
}

function carregarUsuarios() {
    fetch("./crud_trens/listar_usuarios.php")
        .then(tratarResposta)
        .then(function(data) {
            if (
                data.status !== "success" ||
                !Array.isArray(data.usuarios)
            ) {
                throw new Error(
                    data.message || "Erro ao carregar usuários."
                );
            }

            usuarios = data.usuarios;

            responsavelInput.innerHTML =
                '<option value="">Selecione o responsável</option>';

            usuarios.forEach(function(usuario) {
                const opcao = document.createElement("option");

                opcao.value = usuario.id;
                opcao.textContent = usuario.nome;

                responsavelInput.appendChild(opcao);
            });

            if (usuarios.length === 0) {
                responsavelInput.innerHTML =
                    '<option value="">Nenhum usuário cadastrado</option>';
            }
        })
        .catch(function(error) {
            console.error("Erro ao carregar usuários:", error);

            responsavelInput.innerHTML =
                '<option value="">Erro ao carregar usuários</option>';

            alert(
                "Não foi possível carregar os usuários. " +
                "Verifique o arquivo listar_usuarios.php."
            );
        });
}

function mostrarTrens(lista) {
    tabela.innerHTML = "";

    if (lista.length === 0) {
        trenVazio.style.display = "block";
        return;
    }

    trenVazio.style.display = "none";

    lista.forEach(function(trem) {
        const linha = document.createElement("tr");

        linha.innerHTML = `
            <td>${String(trem.id).padStart(2, "0")}</td>
            <td>${escapar(trem.nome)}</td>
            <td>${escapar(trem.empresa)}</td>
            <td>${escapar(trem.numero_vagoes)}</td>
            <td>${trem.tipo === "Eletrico" ? "Elétrico" : escapar(trem.tipo)}</td>
            <td>${escapar(trem.responsavel_nome || "Não informado")}</td>

            <td>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="status-ativo">
                        ● ${escapar(trem.status)}
                    </span>

                    <div>
                        <button
                            type="button"
                            class="btn-acao"
                            onclick="editarTrem(${Number(trem.id)})"
                            aria-label="Editar trem">
                            <i class="bi bi-pencil-square icone-color"></i>
                        </button>

                        <button
                            type="button"
                            class="btn-acao"
                            onclick="excluirTrem(${Number(trem.id)})"
                            aria-label="Excluir trem">
                            <i class="bi bi-trash3 icone-color"></i>
                        </button>
                    </div>
                </div>
            </td>
        `;

        tabela.appendChild(linha);
    });
}

function carregarTrens() {
    fetch("./crud_trens/listar_trem.php")
        .then(tratarResposta)
        .then(function(data) {
            if (
                data.status === "success" &&
                Array.isArray(data.trens)
            ) {
                trens = data.trens;
                aplicarBusca();
            } else {
                alert(data.message || "Erro ao carregar trens.");
            }
        })
        .catch(function(error) {
            console.error("Erro ao carregar trens:", error);
            alert("Erro ao carregar trens. Verifique o servidor.");
        });
}

function aplicarBusca() {
    const texto = campoBusca.value.toLowerCase().trim();

    const resultados = trens.filter(function(trem) {
        return (
            String(trem.nome).toLowerCase().includes(texto) ||
            String(trem.id).includes(texto) ||
            String(trem.empresa).toLowerCase().includes(texto) ||
            String(trem.responsavel_nome || "")
                .toLowerCase()
                .includes(texto)
        );
    });

    mostrarTrens(resultados);
}

campoBusca.addEventListener("input", aplicarBusca);

botaoAdicionar.addEventListener("click", function() {
    const nome = nomeInput.value.trim();
    const empresa = empresaInput.value.trim();
    const vagoes = vagoesInput.value.trim();
    const tipo = tipoInput.value;
    const responsavelId = responsavelInput.value;

    if (nome === "") {
        alert("Digite o nome do trem.");
        nomeInput.focus();
        return;
    }

    if (empresa === "") {
        alert("Digite a empresa.");
        empresaInput.focus();
        return;
    }

    if (
        vagoes === "" ||
        !Number.isInteger(Number(vagoes)) ||
        Number(vagoes) < 1
    ) {
        alert("Digite um número inteiro de vagões maior que zero.");
        vagoesInput.focus();
        return;
    }

    if (!["Eletrico", "Diesel"].includes(tipo)) {
        alert("Selecione um tipo de trem válido.");
        tipoInput.focus();
        return;
    }

    if (!responsavelId) {
        alert("Selecione o responsável pelo trem.");
        responsavelInput.focus();
        return;
    }

    const dados = new FormData();

    dados.append("nome", nome);
    dados.append("empresa", empresa);
    dados.append("numero_vagoes", vagoes);
    dados.append("tipo", tipo);
    dados.append("responsavel_id", responsavelId);

    // Edição
    if (tremEditando !== null) {
        dados.append("id", tremEditando);

        fetch("./crud_trens/editar_trem.php", {
            method: "POST",
            body: dados
        })
            .then(tratarResposta)
            .then(function(data) {
                if (data.status === "success") {
                    alert(data.message || "Trem atualizado com sucesso!");

                    limparFormulario();
                    carregarTrens();
                } else {
                    alert(
                        data.message ||
                        "Não foi possível editar o trem."
                    );
                }
            })
            .catch(function(error) {
                console.error("Erro ao editar trem:", error);
                alert("Erro ao editar trem. Verifique o servidor.");
            });

        return;
    }

    // Cadastro
    fetch("./crud_trens/cadastrar_trem.php", {
        method: "POST",
        body: dados
    })
        .then(tratarResposta)
        .then(function(data) {
            alert(data.message);

            if (data.status === "success") {
                limparFormulario();
                carregarTrens();
            }
        })
        .catch(function(error) {
            console.error("Erro ao cadastrar trem:", error);
            alert("Erro ao cadastrar trem. Verifique o servidor.");
        });
});

function editarTrem(id) {
    const trem = trens.find(function(item) {
        return Number(item.id) === Number(id);
    });

    if (!trem) {
        alert("Trem não encontrado.");
        return;
    }

    tremEditando = trem.id;

    nomeInput.value = trem.nome;
    empresaInput.value = trem.empresa;
    vagoesInput.value = trem.numero_vagoes;
    tipoInput.value = trem.tipo;
    responsavelInput.value = trem.responsavel_id ?? "";

    botaoAdicionar.textContent = "Salvar alterações";

    const titulo = document.querySelector(".usuario__title");

    if (titulo) {
        titulo.textContent = "Editar trem";
    }

    let cancelar = document.getElementById("btnCancelar");

    if (!cancelar) {
        cancelar = document.createElement("button");

        cancelar.id = "btnCancelar";
        cancelar.type = "button";
        cancelar.textContent = "Cancelar edição";
        cancelar.className = "btn btn-secondary mt-2";

        botaoAdicionar.insertAdjacentElement(
            "afterend",
            cancelar
        );

        cancelar.addEventListener("click", limparFormulario);
    }

    cancelar.style.display = "inline-block";

    nomeInput.focus();
}

function limparFormulario() {
    tremEditando = null;

    nomeInput.value = "";
    empresaInput.value = "";
    vagoesInput.value = "";
    tipoInput.value = "";
    responsavelInput.value = "";

    botaoAdicionar.textContent = "Adicionar";

    const titulo = document.querySelector(".usuario__title");

    if (titulo) {
        titulo.textContent = "Adicionar trem";
    }

    const cancelar = document.getElementById("btnCancelar");

    if (cancelar) {
        cancelar.style.display = "none";
    }
}

function excluirTrem(id) {
    const trem = trens.find(function(trem) {
        return Number(trem.id) === Number(id);
    });

    if (!trem) {
        alert("Trem não encontrado.");
        return;
    }

    const confirmar = confirm(
        'Deseja realmente remover o trem "' + trem.nome + '"?'
    );

    if (!confirmar) {
        return;
    }

    const dados = new FormData();

    dados.append("id", id);

    fetch("./crud_trens/excluir_trem.php", {
        method: "POST",
        body: dados
    })
        .then(tratarResposta)
        .then(function(data) {
            alert(data.message);

            if (data.status === "success") {
                carregarTrens();
            }
        })
        .catch(function(error) {
            console.error("Erro ao excluir trem:", error);
            alert("Erro ao excluir trem. Verifique o servidor.");
        });
}

carregarUsuarios();
carregarTrens();