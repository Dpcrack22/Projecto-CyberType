const cerrarSesionButton = document.getElementById("cerrarSesionButton");
const listarFrasesButton = document.getElementById("listarFrasesButton");
const agregarFraseButton = document.getElementById("agregarFraseButton");

document.addEventListener("keydown", (event) => {
    switch (event.key.toLowerCase()) {
        case "c":
            window.location.href="/admin/logout.php";
            break;
        case "l":
            window.location.href="/admin/list_sentences.php";
            break;
        case "a":
            window.location.href="/admin/create_sentence.php";
            break;
        case "s":
            window.location.href="/admin/add_image.php";
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