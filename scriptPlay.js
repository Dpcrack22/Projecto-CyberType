const fraseOriginal = "El ràpid guineu marró salta sobre el gos mandrós.";
const fraseDiv = document.getElementById("frase");
const inputOcult = document.getElementById("inputOcult");
const contadorDiv = document.getElementById("contador");
let contador = 3;

function mostrarFrase() {
    fraseDiv.innerHTML = "";
    for (let letra of fraseOriginal) {
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