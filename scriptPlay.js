const fraseOriginal = ["El ràpid esquirol salta sobre el gos mandrós." , "Tipus de lletra monoespaiada són ideals per a programar." , "La pràctica fa al mestre en qualsevol disciplina."];
const fraseDiv = document.getElementById("frase");
const inputOcult = document.getElementById("inputOcult");
const contadorDiv = document.getElementById("contador");
const audioRight = new Audio("Right.mp3");
const audioMiss = new Audio("Miss.wav");
const audioGameover = new Audio("gameover.wav");
const bonusDiv = document.getElementById("bonusMessage");
const dificultadFrase = "<?php echo htmlspecialchars($difficulty); ?>";
let puntuation = 0;
let consectutiveRightHits = 0;
let consectutiveWrongHits = 0;
let bonus = 0;
let contador = 3;
let posicionActual = 0;
let fraseAleatoria = "";
let puntuacion = 0;


// Prueba Chasquido
let totalLetrasEscritas = 0;
let totalErrores = 0;
let thanosSnapTriggered = false;

async function getPhrase(difficulty) {
    const file = "sentences.txt";
    const response = await fetch(file);
    const text = await response.text();
    switch (difficulty) {
        case "easy":
            return text.substring(text.indexOf("easy:") + 6, text.indexOf("]")).trim();
        case "medium":
            return text.substring(text.indexOf("medium:") + 8, text.indexOf("]", text.indexOf("medium:"))).trim();
        case "hard":
            return text.substring(text.indexOf("hard:") + 6, text.lastIndexOf("]")).trim();
        default:
            return "";
    }
}

function mostrarFrase() {
    const frase = getPhrase(dificultadFrase);
    const frasesArray = frase.split("\n").map(f => f.trim()).filter(f => f !== "");
    fraseAleatoria = frasesArray[Math.floor(Math.random() * frasesArray.length)];
    fraseDiv.innerHTML = "";
    posicionActual = 0;
    
    fraseAleatoria = Math.floor(Math.random() * getPhrase(dificultadFrase));
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
        document.getElementById("titulo-play").style.display = "block";
        document.getElementById("titulo-prepara").style.display = "none";

        mostrarFrase();
    }
}, 1000);

function verificarEscritura() {
    const valor = inputOcult.value;
    const spans = fraseDiv.querySelectorAll("span");

    totalLetrasEscritas = valor.length;
    totalErrores = 0;

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
            puntuacion = puntuacion + 10;
        } else {
            audioMiss.pause();
            audioRight.pause();
            audioMiss.currentTime = 0;
            audioMiss.play();
            spans[i].classList.add("incorrecta");
            spans[i].classList.remove("correcta");
            puntuacion = puntuacion - 5;
            totalErrores++;
        }
    }

    if (valor.length > 0) {
        const ultimaLetraIndex = valor.length - 1;
        const ultimaLetraEsperada = fraseAleatoria[ultimaLetraIndex];
        const ultimaLetraEscrita = valor[ultimaLetraIndex];

        easterEgg(ultimaLetraEscrita === ultimaLetraEsperada);
    }

    posicionActual = valor.length;
    updateCurrentLetter();

    if (!thanosSnapTriggered && totalLetrasEscritas > 0) {
        const errorRate = totalErrores / totalLetrasEscritas;
        if (errorRate >= 0.5) {
            thanosSnapTriggered = true;
            activateThanosSnap();
            puntuacion = -5000;
            setTimeout(() => {
                endGame(puntuacion, ((performance.now() - tiempoInicio) / 1000).toFixed(2));
            }, 4000);
            return;
        }
    }

    if (valor.length === fraseAleatoria.length) {
        const tiempoFin = performance.now();
        const tiempoTotal = ((tiempoFin - tiempoInicio) / 1000).toFixed(2); // Tiempo en segundos con dos decimales

        endGame(puntuacion, tiempoTotal);
    }
};

function activateThanosSnap() {
    console.log("💥 Modo Thanos activado: la mitad de las letras desaparecerán...");

    const spans = Array.from(fraseDiv.querySelectorAll("span"));
    const half = Math.floor(spans.length / 2);
    const shuffled = spans.sort(() => 0.5 - Math.random());
    const toRemove = shuffled.slice(0, half);

    new Audio('snap.mp3').play();

    alert("💀 Thanos ha chasqueado los dedos... la mitad se desintegra y tu partida se acabó.");

    // Efecto visual
    toRemove.forEach((span, i) => {
        setTimeout(() => {
            span.classList.add("disappear");
            setTimeout(() => span.remove(), 1000);
        }, i * 100);
    });
}
inputOcult.addEventListener("input", verificarEscritura);

function endGame(score, time) {
    fetch('finish_game.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: "score=" + encodeURIComponent(score)
        + "&time=" + encodeURIComponent(time)
    })
    .then(response => response.text())
    .then(data => {
        if (data === "OK") {
            // Redirigir una vez se haya establecido la sesión
            window.location.href = "gameover.php";
        } else {
            console.error("Error al finalizar el juego en el servidor.");
        }
    })
    .catch(error => console.error("Error al comunicarse con el servidor:", error));
}


function mostrarBonus() {
    bonusDiv.textContent = "BONUS!";
    bonusDiv.style.display = "block";

    setTimeout(() => {
        bonusDiv.style.display = "none";
    }, 1500);
}


function easterEgg(bool) {
    if (bool) {
        consectutiveWrongHits = 0;
        consectutiveRightHits++;
        puntuation += 50;
        if (consectutiveRightHits === 5) {
            consectutiveRightHits = 0;
            puntuation += 200;
            bonus++;
            mostrarBonus();
        }
    } else {
        consectutiveRightHits = 0;
        consectutiveWrongHits++;
        puntuation -= 20;
        if (consectutiveWrongHits === 5) {
            consectutiveWrongHits = 0;
            puntuation -= 200;
            bonus--;
        }
    }
    console.log(puntuation);
}