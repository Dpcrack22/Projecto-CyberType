// Variables globales
const inputOcult = document.getElementById("frase");
const contadorDiv = document.getElementById("contador");
const audioRight = new Audio("Right.mp3");
const audioMiss = new Audio("Miss.wav");
const audioGameover = new Audio("gameover.wav");
const bonusDiv = document.getElementById("bonusMessage");

// Variables para Easter Egg/Bonus
let consectutiveRightHits = 0;
let consectutiveWrongHits = 0;
let bonus = 0;

// Contador de inicio
let contador = 3;

// Frases y estado del juego
let indiceFraseActual = 0;
let posicionActual = 0;
let fraseAleatoria = "";
let imagenJuego = "";
let frasesJuego = [];
let imagenesJuego = [];
let fraseJuego = "";
let puntuation = 0;

// Prueba Chasquido
let totalLetrasEscritas = 0;
let totalErrores = 0;
let thanosSnapTriggered = false;

function mostrarFrase() {
    const imageContainer = document.getElementById("imageContainer");
    const fraseImg = document.getElementById("fraseImg");

    fraseAleatoria = frasesJuego[indiceFraseActual] || "";
    imagenJuego = imagenesJuego[indiceFraseActual] || "";

    if (typeof imagenJuego !== 'undefined' && imagenJuego.trim() !== "") {
        fraseImg.src = "IMG/" + imagenJuego;
    } else {
        imageContainer.style.display = "none";
    }

    inputOcult.innerHTML = "";
    posicionActual = 0;

    for (let letra of fraseAleatoria) {
        const span = document.createElement("span");
        span.textContent = letra;
        inputOcult.appendChild(span);
    }

    updateCurrentLetter();
    inputOcult.value = "";
    inputOcult.focus();
}

function updateCurrentLetter() {
    const spans = inputOcult.querySelectorAll("span");  
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
        document.getElementById("imageContainer").style.display = "block";
        document.getElementById("fraseContainer").style.display = "block";
        document.getElementById("titulo-play").style.display = "block";
        document.getElementById("titulo-prepara").style.display = "none";

        mostrarFrase();
    }
}, 1000);

document.addEventListener("keydown", manejarTecla);
document.addEventListener("input", manejarEntrada);

function manejarEntrada(e) {
    if (e.inputType === "insertCompositionText" || e.inputType === "insertText") {
        const letra = e.data;
        if (letra && letra.length === 1) {
            verificarEscritura(letra);
        }
    }
}

function manejarTecla(e) {
    if (e.key.length !== 1 && e.key !== "Backspace") return;

    e.preventDefault();

    if (e.key === "Backspace") {
        posicionActual = Math.max(0, posicionActual - 1);
    } else {
        verificarEscritura(e.key);
    }

    updateCurrentLetter();
}

function cargarSiguienteFrase() {
    indiceFraseActual++;
    if (indiceFraseActual >= frasesJuego.length) {
        endGame(puntuation);
        return;
    } else {
        contador = 3;
        contadorDiv.style.display = "block";
        contadorDiv.textContent = contador;
        document.getElementById("imageContainer").style.display = "none";
        document.getElementById("fraseContainer").style.display = "none";
        document.getElementById("titulo-play").style.display = "none";

        const intervalo = setInterval(() => {
            contador--;
            if (contador > 0) {
                contadorDiv.textContent = contador;
            } else if (contador === 0) {
                contadorDiv.textContent = "YA!";
            } else {
                clearInterval(intervalo);
                document.getElementById("contador").style.display = "none";
                document.getElementById("imageContainer").style.display = "block";
                document.getElementById("fraseContainer").style.display = "block";
                document.getElementById("titulo-play").style.display = "block";

                mostrarFrase();
            }
        }, 1000);
    }
}

function verificarEscritura(tecla) {
    console.log("👉 Tecla pulsada:", tecla);
    const spans = inputOcult.querySelectorAll("span");
    const letraEsperada = fraseAleatoria[posicionActual];

    if (!letraEsperada) return;

    if (tecla === letraEsperada) {
        audioRight.pause();
        audioRight.currentTime = 0;
        audioRight.play().catch(() => {});
        spans[posicionActual].classList.add("correcta");
        spans[posicionActual].classList.remove("incorrecta");
        puntuation += 10;
        easterEgg(true);
    } else {
        audioMiss.pause();
        audioMiss.currentTime = 0;
        audioMiss.play().catch(() => {});
        spans[posicionActual].classList.add("incorrecta");
        spans[posicionActual].classList.remove("correcta");
        puntuation -= 5;
        easterEgg(false);
    }

    posicionActual++;
    updateCurrentLetter();

    if (posicionActual === fraseAleatoria.length) {
        cargarSiguienteFrase();
    }
};

function activateThanosSnap() {
    console.log("💥 Modo Thanos activado: la mitad de las letras desaparecerán...");

    const spans = Array.from(inputOcult.querySelectorAll("span"));
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

function endGame(score) {
    fetch('finish_game.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: "score=" + encodeURIComponent(score)
        + "&bonus=" + encodeURIComponent(bonus)
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