// Variables globales
const inputOcult = document.getElementById("frase");
const contadorDiv = document.getElementById("contador");
const audioRight = new Audio("Right.mp3");
const audioMiss = new Audio("Miss.wav");
const audioGameover = new Audio("gameover.wav");
const bonusDiv = document.getElementById("bonusMessage");
const tiempoDiv = document.getElementById("tiempoTranscurrido");

// Variables de juego modificables
let puntuation = 0;
let consectutiveRightHits = 0;
let consectutiveWrongHits = 0;
let bonus = 0;
let multiplicador = 1;

// Contador de inicio
let contador = 3;

// Frases y estado del juego
let indiceFraseActual = 0;
let posicionActual = 0;
let fraseAleatoria = "";

// Input oculto para capturar composition/input (acentos) correctamente
let hiddenInput = null;
let isComposing = false;

let tiempoInicio = 0;
let tiempoTranscurrido = 0;
let intervalTiempo;
let imagenJuego = "";
let frasesJuego = [];
let imagenesJuego = [];
let fraseJuego = "";

// Prueba Chasquido
let totalLetrasEscritas = 0;
let totalErrores = 0;
let thanosSnapTriggered = false;

// Barra de progreso
let totalFrases = 0;
let progressLabel = null;
let progressFill = null;


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
    startTime = performance.now();
    // Crear y enfocar un input oculto para recibir la composición de acentos
    createHiddenInput();
    hiddenInput.value = "";
    hiddenInput.focus();
    updateProgress(); // actualizar barra (cada vez que mostramos una frase)

}

function createHiddenInput() {
    if (hiddenInput) return;
    hiddenInput = document.createElement('input');
    hiddenInput.type = 'text';
    hiddenInput.id = 'hiddenInput';
    hiddenInput.autocomplete = 'off';
    hiddenInput.autocorrect = 'off';
    hiddenInput.autocapitalize = 'off';
    hiddenInput.spellcheck = false;
    hiddenInput.style.position = 'absolute';
    hiddenInput.style.left = '-9999px';
    hiddenInput.style.width = '1px';
    hiddenInput.style.height = '1px';
    hiddenInput.style.opacity = '0';
    document.body.appendChild(hiddenInput);

    // Composición (dead-keys / IME)
    hiddenInput.addEventListener('compositionstart', () => {
        isComposing = true;
    });

    hiddenInput.addEventListener('compositionend', (e) => {
        isComposing = false;
        const composed = (e.data !== undefined) ? e.data : hiddenInput.value;
        if (composed) {
            for (let ch of composed) {
                if (ch.length === 1) verificarEscritura(ch);
            }
            hiddenInput.value = '';
        }
    });

    // Input normal (no composición)
    hiddenInput.addEventListener('input', (e) => {
        if (isComposing) return; // compositionend ya lo maneja
        if (e.data) {
            for (let ch of e.data) {
                if (ch.length === 1) verificarEscritura(ch);
            }
            hiddenInput.value = '';
        }
    });

    // Keydown en el input: manejar Backspace y teclas simples, bloquear control keys
    hiddenInput.addEventListener('keydown', (e) => {
        // No bloquear la composición
        if (isComposing) return;
        
        // Bloquear teclas de control (Escape, Tab, Delete, Enter) como si fueran errores
        // para evitar que se borre accidentalmente
        if (["Backspace","Escape", "Tab", "Delete", "Enter"].includes(e.key)) {
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
            e.preventDefault();
            return;
        }
        
        if (e.key === 'Dead' || e.key.length !== 1) return;
        // Para teclas de un solo carácter (no dead-keys), procesar
        verificarEscritura(e.key);
        updateCurrentLetter();
        e.preventDefault();
    });

    // Al hacer click en el contenedor, asegurar foco en el input oculto
    const fraseContainerEl = document.getElementById('fraseContainer');
    if (fraseContainerEl) {
        fraseContainerEl.addEventListener('click', () => {
            hiddenInput.focus();
        });
    }
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
        document.getElementById("tiempoTranscurrido").style.display = "block";
        tiempoInicio = performance.now();
        intervalTiempo = setInterval(() => {
            tiempoTranscurrido = Math.floor((performance.now() - tiempoInicio) / 1000).toFixed(2);
            tiempoDiv.textContent = `Tiempo: ${tiempoTranscurrido} s`;
        }, 100);

        mostrarFrase();
    }
}, 1000);

function manejarEntrada(e) {
    if (e.inputType === "insertCompositionText" || e.inputType === "insertText") {
        const letra = e.data;
        if (letra && letra.length === 1) {
            verificarEscritura(letra);
        }
    }
}

    

function manejarTecla(e) {
    // Evitar procesar teclas que no generan caracteres o que forman composición
    // (p. ej. dead keys usadas para acentos). No hacemos preventDefault para
    // permitir la composición nativa del navegador/teclado.
    if (e.key === 'Shift' || e.key === 'Control' || e.key === 'Alt' || e.key === 'Meta') return;

    if (e.key === "Backspace") {
        posicionActual = Math.max(0, posicionActual - 1);
        updateCurrentLetter();
        return;
    }

    // 'Dead' es el valor común para teclas muertas (acentos). Ignorar; la
    // letra compuesta llegará vía evento `input`/`compositionend` si procede.
    if (e.key === 'Dead') return;

    // Sólo procesar teclas de un solo carácter
    if (e.key.length === 1) {
        verificarEscritura(e.key);
        updateCurrentLetter();
    }
}

function cargarSiguienteFrase() {
    indiceFraseActual++;
    if (indiceFraseActual >= frasesJuego.length) {
        const tiempoFinal = performance.now();
        const tiempoTotal = ((tiempoFinal - tiempoInicio) / 1000).toFixed(2); // Tiempo completado con decimales
        if (Math.random() < 0.1 ) { // 1% de probabilidad
            thanosSnapTriggered = true;
            activateThanosSnap();
            setTimeout(() => {
                endGame(puntuation, tiempoTotal);
            }, 4000);
            return;
        }
        clearInterval(intervalTiempo);
        enviarLogTeclas(fraseAleatoria, fraseAleatoria, tiempoTranscurrido);
        endGame(puntuation, tiempoTotal);
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
        console.log(tecla);
        // Registrar la tecla pulsada con información sobre acento y la tecla esperada
        enviarLogKeypress(tecla, letraEsperada, true);
    } else {
        audioMiss.pause();
        audioMiss.currentTime = 0;
        audioMiss.play().catch(() => {});
        spans[posicionActual].classList.add("incorrecta");
        spans[posicionActual].classList.remove("correcta");
        puntuation -= 5;
        easterEgg(false);
        // Registrar la tecla pulsada (incorrecta)
        enviarLogKeypress(tecla, letraEsperada, false);
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

function enviarLogTeclas(fraseEscrita, fraseObjetivo, tiempo) {
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "./admin/log_keys.php";

    const input1 = document.createElement("input");
    input1.type = "hidden";
    input1.name = "typedText";
    input1.value = fraseEscrita;

    const input2 = document.createElement("input");
    input2.type = "hidden";
    input2.name = "targetSentence";
    input2.value = fraseObjetivo;

    const input3 = document.createElement("input");
    input3.type = "hidden";
    input3.name = "elapsedTime";
    input3.value = tiempo;

    form.appendChild(input1);
    form.appendChild(input2);
    form.appendChild(input3);
    document.body.appendChild(form);

    // Enviar sin cambiar de página
    form.target = "invisibleFrame";
    let iframe = document.getElementById("invisibleFrame");
    if (!iframe) {
        iframe = document.createElement("iframe");
        iframe.name = "invisibleFrame";
        iframe.style.display = "none";
        document.body.appendChild(iframe);
    }

    form.submit();
}

function endGame(score, tiempo) {
    fetch('finish_game.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: "score=" + encodeURIComponent(score * multiplicador)
        + "&bonus=" + encodeURIComponent(bonus)
        + "&tiempo=" + encodeURIComponent(tiempo)
        + "&multiplicador=" + encodeURIComponent(multiplicador)
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
    bonusDiv.textContent = "Bonus x" + multiplicador + "!";
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
            multiplicador++;
            mostrarBonus();
        }
    } else {
        consectutiveRightHits = 0;
        consectutiveWrongHits++;
        puntuation -= 20;
        if (consectutiveWrongHits >= 2 && multiplicador > 1) {
            multiplicador--;
            mostrarBonus();
        }
        if (consectutiveWrongHits === 5) {
            puntuation -= 200;
            bonus--;
        }
    }
    console.log(puntuation);
}

function normalizar(texto) {
    if (typeof texto !== 'string') return texto;
    return texto.normalize("NFC");
}

// Detecta si un carácter (o string) contiene marcas diacríticas (acentos)
function tieneAcento(texto) {
    if (typeof texto !== 'string' || texto.length === 0) return false;
    // Normalizar a NFD para separar base + marcas, y buscar marcas Unicode
    return /[\u0300-\u036f]/.test(texto.normalize('NFD'));
}

// Enviar registro de tecla al servidor incluyendo si tiene acento y la tecla esperada
function enviarLogKeypress(tecla, expected, correct) {
    const payload = {
        key: tecla, // lo que ha escrito el usuario
        normalizedKey: normalizar(tecla),
        hasAccent: tieneAcento(tecla),
        expected: expected,
        normalizedExpected: normalizar(expected),
        correct: !!correct,
        timestamp: new Date().toISOString()
    };

    fetch("admin/log_keypress.php", {
        method: "POST",
        headers: { "Content-Type": "application/json; charset=UTF-8" },
        body: JSON.stringify(payload)
    }).catch(() => {});
}

// Funciones barra de progreso

function initProgressBar() {
    progressLabel = document.getElementById('progressLabel');
    progressFill = document.getElementById('progressFill');
    updateProgress();
}

function updateProgress() {
    if (!progressLabel || !progressFill) return;
    const current = Math.min(indiceFraseActual + 1, totalFrases);
    progressLabel.textContent = `Frase ${current} / ${totalFrases}`;
    const pct = totalFrases > 0 ? Math.round((current / totalFrases) * 100) : 0;
    progressFill.style.width = `${pct}%`;
}