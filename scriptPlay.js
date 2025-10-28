const fraseOriginal = ["El ràpid esquirol salta sobre el gos mandrós." , "Tipus de lletra monoespaiada són ideals per a programar." , "La pràctica fa al mestre en qualsevol disciplina."];
const fraseDiv = document.getElementById("frase");
const inputOcult = document.getElementById("inputOcult");
const contadorDiv = document.getElementById("contador");
const audioRight = new Audio("Right.mp3");
const audioMiss = new Audio("Miss.wav");
const audioGameover = new Audio("gameover.wav");
let contador = 3;
let posicionActual = 0;
let fraseAleatoria = "";

function mostrarFrase() {
    fraseDiv.innerHTML = "";
    posicionActual = 0;
    fraseAleatoria = fraseOriginal[Math.floor(Math.random() * fraseOriginal.length)];
    tiempoInicio = performance.now(); // Mide el tiempo que ha tardado en escribir la frase

    for (let letra of fraseAleatoria) {
        const span = document.createElement("span");
        span.textContent = letra;
        fraseDiv.appendChild(span);
    }

    updateCurrentLetter();
    inputOcult.value = "";
    inputOcult.focus();
}

function updateCurrentLetter() {
    const spans = fraseDiv.querySelectorAll("span");
    spans.forEach(span => span.classList.remove("currentLetter"));
    if (posicionActual < spans.length) {
        spans[posicionActual].classList.add("currentLetter");
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

    for (let i = 0; i < spans.length; i++) {
        const letraEsperada = fraseAleatoria[i] || "";
        const letraEscrita = valor[i] || "";

        if (letraEscrita === "") {
            spans[i].classList.remove("correcta", "incorrecta");
        } else if (letraEscrita === letraEsperada) {
            audioRight.pause();
            audioMiss.pause();
            audioRight.currentTime = 0;
            audioRight.play();
            spans[i].classList.add("correcta");
            spans[i].classList.remove("incorrecta");
        } else {
            audioMiss.pause();
            audioRight.pause();
            audioMiss.currentTime = 0;
            audioMiss.play();
            spans[i].classList.add("incorrecta");
            spans[i].classList.remove("correcta");
        }
    }

    posicionActual = valor.length;
    updateCurrentLetter();

    if (valor === fraseAleatoria) {
        const tiempoFin = performance.now();
        const tiempoTotal = ((tiempoFin - tiempoInicio) / 1000).toFixed(2); // Tiempo en segundos con dos decimales

        endGame();
    }
};

inputOcult.addEventListener("input", verificarEscritura);

function endGame() {
    // Llamada al servidor para establecer la variable de sesión
    fetch('finish_game.php')
        .then(response => response.text())
        .then(data => {
            if (data === "OK") {
                // Redirigir una vez se haya establecido la sesión
                audioGameover.play();
                window.location.href = "gameover.php";
            } else {
                console.error("Error al finalizar el juego en el servidor.");
            }
        })
        .catch(error => console.error("Error al comunicarse con el servidor:", error));
}
