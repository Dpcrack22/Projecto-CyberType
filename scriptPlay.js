const fraseOriginal = ["El ràpid esquirol salta sobre el gos mandrós." , "Tipus de lletra monoespaiada són ideals per a programar." , "La pràctica fa al mestre en qualsevol disciplina."];
const fraseDiv = document.getElementById("frase");
const inputOcult = document.getElementById("inputOcult");
const contadorDiv = document.getElementById("contador");
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
            spans[i].classList.add("correcta");
            spans[i].classList.remove("incorrecta");
        } else {
            spans[i].classList.add("incorrecta");
            spans[i].classList.remove("correcta");
        }
    }

    posicionActual = valor.length;
    updateCurrentLetter();


    // Necesito hacer que cuando acabe la partida, se envie el formulario al gameover.php
    // Pero se debe tener en cuenta que para ir a gameover.php debemos poner este codigo
    /* Cuando acabe el juego hay que poner esto en algun lado antes de redirigir a gameover.php

        $_SESSION['game_finished'] = true;
        header("Location: gameover.php");

    */
    if (valor === fraseAleatoria) {
        const tiempoFin = performance.now();
        const tiempoTotal = ((tiempoFin - tiempoInicio) / 1000).toFixed(2); // Tiempo en segundos con dos decimales

        // Rellenar los inputs ocultos del formulario
        document.getElementById("fraseInput").value = fraseAleatoria;
        document.getElementById("tiempoInput").value = tiempoTotal;

        // Enviar el formulario
        document.getElementById("formRanking").submit();

        fetch('gameover.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ game_finished: true })
        });
    }
};

inputOcult.addEventListener("input", verificarEscritura);
