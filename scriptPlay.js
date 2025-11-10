// Variables globales
const inputOcult = document.getElementById("frase");
const contadorDiv = document.getElementById("contador");
const audioRight = new Audio("Right.mp3");
const audioMiss = new Audio("Miss.wav");
const audioGameover = new Audio("gameover.wav");
const bonusDiv = document.getElementById("bonusMessage");
const dificultadFrase = document.getElementById("frase").textContent = fraseJuego;
let puntuation = 0;
let consectutiveRightHits = 0;
let consectutiveWrongHits = 0;
let bonus = 0;
let contador = 3;
let posicionActual = 0;
let fraseAleatoria = "";


// Prueba Chasquido
let totalLetrasEscritas = 0;
let totalErrores = 0;
let thanosSnapTriggered = false;

function mostrarFrase() {
    fraseAleatoria = dificultadFrase;
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
    if (e.key.length !== 1 && e.key !== "Backspace" && e.key !== "Escape" && e.key !== "Tab" && e.key !== "Delete" && e.key !== "Enter") return;

    e.preventDefault();

    if (["Backspace", "Escape", "Tab", "Delete", "Enter"].includes(e.key)) {
        const spans = inputOcult.querySelectorAll("span");

        audioMiss.pause();
        audioMiss.currentTime = 0;
        audioMiss.play().catch(() => {});

        if (posicionActual < spans.length) {
            spans[posicionActual].classList.add("incorrecta");
        }

        easterEgg(false);

        posicionActual++;

        updateCurrentLetter();

        if (posicionActual === fraseAleatoria.length) {
            endGame(puntuation);
        }

        return;
    } 
        
    verificarEscritura(e.key);


    updateCurrentLetter();
}

function verificarEscritura(tecla) {
    console.log("👉 Tecla pulsada:", tecla);
    const spans = inputOcult.querySelectorAll("span");
    const letraEsperada = fraseAleatoria[posicionActual];

    if (!letraEsperada) return;

    if (normalizar(tecla) === normalizar(letraEsperada)) {
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
        if (Math.random() < 0.1 ) { // 1% de probabilidad
            thanosSnapTriggered = true;
            activateThanosSnap();
            setTimeout(() => {
                endGame(puntuation);
            }, 4000);
            return;
        }
        endGame(puntuation);
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

function normalizar(texto) {
    return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}