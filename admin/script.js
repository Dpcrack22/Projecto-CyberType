const cerrarSesionButton = document.getElementById("cerrarSesionButton");
const listarFrasesButton = document.getElementById("listarFrasesButton");
const agregarFraseButton = document.getElementById("agregarFraseButton");

document.addEventListener("keydown", (event) => {
    switch (event.key.toLowerCase()) {
        case "c":
            window.location.href="logout.php";
            break;
        case "l":
            window.location.href="listar_frases.php";
            break;
        case "a":
            window.location.href="agregar_frase.php";
            break;
        default:
            break;
    }
});

cerrarSesionButton.addEventListener("click", () => {
    window.location.href="logout.php";
});

listarFrasesButton.addEventListener("click", () => {
    window.location.href="listar_frases.php";
});

agregarFraseButton.addEventListener("click", () => {
    window.location.href="agregar_frase.php";
});