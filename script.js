const startGameButton = document.getElementById('startGameButton');
const divArea = document.getElementById("nameArea");
const infoText = document.getElementById("infoText");

startGameButton.addEventListener('click', () => {
    const inputNameValue = document.getElementById("inputName").value;
    const selectDifficultyValue = document.getElementById("selectDifficulty").value;

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