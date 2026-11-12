(() => {

    
const cidadeInput = document.getElementById("cidade");
const btnBuscar = document.getElementById("btnBuscar");
const resultadoTempo = document.getElementById("resultadoTempo");

if (!cidadeInput || !btnBuscar || !resultadoTempo) {
    console.error("Elementos da previsão do tempo não encontrados.");
} else {

    btnBuscar.addEventListener("click", buscarPrevisao);

    cidadeInput.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            buscarPrevisao();
        }
    });

    async function buscarPrevisao() {

        const cidade = cidadeInput.value.trim();

        if (cidade === "") {
            resultadoTempo.innerHTML = "<p>Digite uma cidade.</p>";
            return;
        }

        resultadoTempo.innerHTML = "<p>Buscando previsão...</p>";

        btnBuscar.disabled = true;

        try {

            const respostaCidade = await fetch(
                `https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(cidade)}&count=1&language=pt&format=json`
            );

            if (!respostaCidade.ok) {
                throw new Error("Erro ao consultar a localização.");
            }

            const dadosCidade = await respostaCidade.json();

            if (!dadosCidade.results || dadosCidade.results.length === 0) {
                resultadoTempo.innerHTML = "<p>Cidade não encontrada.</p>";
                return;
            }

            const local = dadosCidade.results[0];

            const respostaTempo = await fetch(
                `https://api.open-meteo.com/v1/forecast?latitude=${local.latitude}&longitude=${local.longitude}&current=temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code,wind_speed_10m&timezone=auto`
            );

            if (!respostaTempo.ok) {
                throw new Error("Erro ao consultar a previsão.");
            }

            const dadosTempo = await respostaTempo.json();

            if (!dadosTempo.current) {
                throw new Error("Dados meteorológicos indisponíveis.");
            }

            const clima = dadosTempo.current;

            resultadoTempo.innerHTML = `
                <h3>${local.name}</h3>

                <p>${local.admin1 || ""} - ${local.country || ""}</p>

                <p>Temperatura: ${clima.temperature_2m} °C</p>

                <p>Sensação térmica: ${clima.apparent_temperature} °C</p>

                <p>Umidade: ${clima.relative_humidity_2m}%</p>

                <p>Chuva: ${clima.precipitation} mm</p>

                <p>Vento: ${clima.wind_speed_10m} km/h</p>
            `;

        } catch (erro) {

            console.error("Erro na previsão:", erro);

            resultadoTempo.innerHTML = `
                <p>Não foi possível buscar a previsão do tempo.</p>
                <p>${erro.message}</p>
            `;

        } finally {

            btnBuscar.disabled = false;

        }
    }
}

})();