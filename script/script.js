const mensagem = document.querySelector(".alert");

if (mensagem) {
    setTimeout(function () {
        mensagem.style.display = "none";
    }, 4000);
}

const formulariosSalvar = document.querySelectorAll(".form-salvar");

for (let i = 0; i < formulariosSalvar.length; i++) {
    formulariosSalvar[i].addEventListener("submit", function () {
        const botao = this.querySelector("button[type='submit']");
        botao.disabled = true;
        botao.textContent = this.dataset.carregando;
    });
}

const formulariosExcluir = document.querySelectorAll(".form-excluir");

for (let i = 0; i < formulariosExcluir.length; i++) {
    formulariosExcluir[i].addEventListener("submit", function (evento) {
        if (!confirm(this.dataset.mensagem)) {
            evento.preventDefault();
        }
    });
}

const camposCep = document.querySelectorAll(".cep-input");

for (let i = 0; i < camposCep.length; i++) {
    camposCep[i].addEventListener("input", function () {
        let numeros = this.value.replace(/\D/g, "");

        if (numeros.length > 5) {
            this.value = numeros.substring(0, 5) + "-" + numeros.substring(5, 8);
        } else {
            this.value = numeros;
        }
    });
}
