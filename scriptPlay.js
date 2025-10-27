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
        mostrarFrase();
    }
}, 1000);

function verificarEscritura() {
    const valor = inputOcult.value;
    const spans = fraseDiv.querySelectorAll("span");
    for (let i =0; i < spans.length; i++) {
        const letra = valor[i];
        const span = spans[i];

        if (letra == null) {
            span.classList.remove("correcto");
            span.classList.remove("incorrecto");
        } else if (letra === span.textContent) {
            span.classList.add("correcto");
            span.classList.remove("incorrecto");
        } else {
            span.classList.add("incorrecto");
            span.classList.remove("correcto");
        }
    }

    if (valor.length === fraseAleatoria.length) {
        window.location.href = "ranking.php";
    }
};