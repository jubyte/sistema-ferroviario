const cidadeInput = document.getElementById("cidade");
const btnBuscar = document.getElementById("btnBuscar");
const resultadoTempo = document.getElementById("resultadoTempo");

btnBuscar.addEventListener("click", buscarPrevisao);

async function buscarPrevisao() {
    const cidade = cidadeInput.value.trim();

    if (cidade === "") {
        resultadoTempo.innerHTML = "<p>Digite uma cidade.</p>";
        return;
    }

    resultadoTempo.innerHTML = "<p>Buscando previsão...</p>";

    try {
        const respostaCidade = await fetch(
            `https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(cidade)}&count=1&language=pt&format=json`
        );

        const dadosCidade = await respostaCidade.json();

        if (!dadosCidade.results || dadosCidade.results.length === 0) {
            resultadoTempo.innerHTML = "<p>Cidade não encontrada.</p>";
            return;
        }

        const local = dadosCidade.results[0];

        const latitude = local.latitude;
        const longitude = local.longitude;

        const respostaTempo = await fetch(
            `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current=temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code,wind_speed_10m&timezone=auto`
        );

        const dadosTempo = await respostaTempo.json();

        resultadoTempo.innerHTML = `
            <h3>${local.name}</h3>

            <p>Temperatura: ${dadosTempo.current.temperature_2m} °C</p>

            <p>Umidade: ${dadosTempo.current.relative_humidity_2m}%</p>

            <p>Chuva: ${dadosTempo.current.precipitation} mm</p>

            <p>Vento: ${dadosTempo.current.wind_speed_10m} km/h</p>
        `;

    } catch (erro) {
        console.error(erro);

        resultadoTempo.innerHTML = `
            <p>Erro ao buscar a previsão do tempo.</p>
        `;
    }
}