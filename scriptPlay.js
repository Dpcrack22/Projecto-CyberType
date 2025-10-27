const fraseOriginal = ["El ràpid esquirol salta sobre el gos mandrós." , "Tipus de lletra monoespaiada són ideals per a programar." , "La pràctica fa al mestre en qualsevol disciplina."];
const fraseDiv = document.getElementById("frase");
const inputOcult = document.getElementById("inputOcult");
const contadorDiv = document.getElementById("contador");
let contador = 3;

function mostrarFrase() {
    fraseDiv.innerHTML = "";
    fraseAleatoria = fraseOriginal[Math.floor(Math.random() * fraseOriginal.length)];
    for (let letra of fraseAleatoria) {
        const span = document.createElement("span");
        span.textContent = letra;
        fraseDiv.appendChild(span);
    }
}

const intervalo = setInterval(() => {
    contador--;
    if (contador > 0) {
        contadorDiv.textContent = contador;
    } else if (contador === 0) {
        contadorDiv.textContent = "YA!";
    } else {
        clearInterval(intervalo);
        document.getElementById("contador").style.display = "none";
        document.getElementById("fraseContainer").style.display = "block";
        document.getElementById("titulo-play").style.display = "block";
        document.getElementById("titulo-prepara").style.display = "none";

        mostrarFrase();
    }
}, 1000);