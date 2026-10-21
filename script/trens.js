let trens = [];
let usuarios = [];

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
                .toLowerCase().includes(texto)
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

    fetch("./crud_trens/cadastrar_trem.php", {
        method: "POST",
        body: dados
    })
        .then(tratarResposta)
        .then(function(data) {
            alert(data.message);

            if (data.status === "success") {
                nomeInput.value = "";
                empresaInput.value = "";
                vagoesInput.value = "";
                tipoInput.value = "";
                responsavelInput.value = "";

                carregarTrens();
            }
        })
        .catch(function(error) {
            console.error("Erro ao cadastrar trem:", error);
            alert("Erro ao cadastrar trem. Verifique o servidor.");
        });
});

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

function editarTrem(id) {
    const trem = trens.find(function(trem) {
        return Number(trem.id) === Number(id);
    });

    if (!trem) {
        alert("Trem não encontrado.");
        return;
    }

    const novoNome = prompt("Digite o novo nome do trem:", trem.nome);
    if (novoNome === null) return;
    if (novoNome.trim() === "") {
        alert("O nome não pode ficar vazio.");
        return;
    }

    const novaEmpresa = prompt("Digite a nova empresa:", trem.empresa);
    if (novaEmpresa === null) return;
    if (novaEmpresa.trim() === "") {
        alert("A empresa não pode ficar vazia.");
        return;
    }

    const novosVagoes = prompt("Digite o novo número de vagões:", trem.numero_vagoes);
    if (novosVagoes === null) return;
    if (
        novosVagoes.trim() === "" ||
        !Number.isInteger(Number(novosVagoes)) ||
        Number(novosVagoes) < 1
    ) {
        alert("Digite um número inteiro de vagões maior que zero.");
        return;
    }

    const novoTipo = prompt("Digite o novo tipo (Eletrico ou Diesel):", trem.tipo);
    if (novoTipo === null) return;
    const tipoNormalizado = novoTipo.trim();
    if (!["Eletrico", "Diesel"].includes(tipoNormalizado)) {
        alert("O tipo deve ser Eletrico ou Diesel.");
        return;
    }

    if (usuarios.length === 0) {
        alert("Não há usuários carregados para selecionar.");
        return;
    }

    const listaUsuarios = usuarios.map(function(usuario) {
        return usuario.id + " - " + usuario.nome;
    }).join("\n");

    const responsavelAtual = trem.responsavel_id ? String(trem.responsavel_id) : "";

    const novoResponsavel = prompt(
        "Digite o ID do novo responsável:\n\n" + listaUsuarios,
        responsavelAtual
    );
    if (novoResponsavel === null) return;

    const usuarioSelecionado = usuarios.find(function(usuario) {
        return String(usuario.id) === novoResponsavel.trim();
    });

    if (!usuarioSelecionado) {
        alert("Selecione um ID de usuário válido.");
        return;
    }

    const dados = new FormData();
    dados.append("id", id);
    dados.append("nome", novoNome.trim());
    dados.append("empresa", novaEmpresa.trim());
    dados.append("numero_vagoes", novosVagoes.trim());
    dados.append("tipo", tipoNormalizado);
    dados.append("responsavel_id", usuarioSelecionado.id);

    fetch("./crud_trens/editar_trem.php", {
        method: "POST",
        body: dados
    })
        .then(tratarResposta)
        .then(function(data) {
            if (data.status === "success") {
                alert(data.message || "Trem atualizado com sucesso!");
                carregarTrens();
            } else {
                alert(data.message || "Não foi possível editar o trem.");
            }
        })
        .catch(function(error) {
            console.error("Erro ao editar trem:", error);
            alert("Erro ao editar trem. Verifique o servidor.");
        });
}

carregarUsuarios();
carregarTrens();