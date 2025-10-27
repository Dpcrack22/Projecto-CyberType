const startGameButton = document.getElementById('startGameButton');
const divArea = document.getElementById("nameArea");
const infoText = document.getElementById("infoText");

startGameButton.addEventListener('click', async () => {
    const inputNameValue = document.getElementById("inputName").value;
    const selectDifficultyValue = document.getElementById("selectDifficulty").value;
    const phraseArray = await getPhrase(selectDifficultyValue);
    let phrase = phraseArray.split(',')[Math.floor(Math.random() * phraseArray.split(',').length)];
    console.log(phrase);
    // haz que haya un paron de 3 segundos antes de iniciar el juego
    await new Promise(resolve => setTimeout(resolve, 20000));

    if (inputNameValue.trim() === "") {
        infoText.style.color = "red";
        infoText.textContent = "Please enter your name to start the game.";
        document.getElementById("inputName").focus();
    } else {
        infoText.textContent = "";
        // Crear un formulario dinamicamente para almacenar los datos del jugador
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "play.php";

        // Crear input hidden para el nombre
        const nameInput = document.createElement("input");
        nameInput.type = "hidden";
        nameInput.name = "playerName";
        nameInput.value = inputNameValue;
        form.appendChild(nameInput);

        // Crear input hidden para la dificultad
        const difficultyInput = document.createElement("input");
        difficultyInput.type = "hidden";
        difficultyInput.name = "difficulty";
        difficultyInput.value = selectDifficultyValue;
        form.appendChild(difficultyInput);

        // Añadir el formulario al body y enviarlo
        document.body.appendChild(form);
        form.submit();
    }
});


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

function lastLetter(index) {
    if (index === phrase.length - 1) {
        window.location.href = "gameover.php";
    }
}