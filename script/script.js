let cadastro = false;

const toggle = document.getElementById("toggle");
const titulo = document.getElementById("titulo");
const formulario = document.getElementById("form-login");
const mensagem = document.getElementById("mensagem");
const botao = formulario.querySelector("button");

toggle.addEventListener("click", (e) => {
    e.preventDefault();

    cadastro = !cadastro;

    titulo.innerText = cadastro ? "CADASTRO" : "LOGIN";
    botao.innerText = cadastro ? "CADASTRAR" : "ENTRAR";

    toggle.innerText = cadastro
        ? "Já tem uma conta? Faça login!"
        : "Não tem uma conta? Cadastre-se!";
        
    mensagem.innerHTML = "";
});

formulario.addEventListener("submit", (e) => {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const senha = document.getElementById("senha").value.trim();

    mensagem.innerHTML = "";

    if (email === "admin" && senha === "admin") {
        window.location.href = "menu.php";
        return;
    }

    if (!email.includes("@") || !email.includes(".")) {
        mensagem.innerHTML =
            "<div class='texto-perigo'>Email inválido!</div>";
        return;
    }

    if (senha.length < 4) {
        mensagem.innerHTML =
            "<div class='texto-perigo'>A senha deve ter pelo menos 4 caracteres!</div>";
        return;
    }

    if (cadastro) {
        if (localStorage.getItem(email)) {
            mensagem.innerHTML =
                "<div class='texto-aviso'>Esse email já está cadastrado!</div>";
            return;
        }

        localStorage.setItem(email, senha);

        mensagem.innerHTML =
            "<div class='texto-sucesso'>Cadastro realizado com sucesso!</div>";

        formulario.reset();
        return;
    }

    const senhaSalva = localStorage.getItem(email);

    if (senhaSalva === null) {
        mensagem.innerHTML =
            "<div class='texto-perigo'>Usuário não encontrado!</div>";
    } else if (senhaSalva === senha) {
        window.location.href = "menu.php";
    } else {
        mensagem.innerHTML =
            "<div class='texto-perigo'>Email ou senha incorreta!</div>";
    }

    formulario.reset();
});