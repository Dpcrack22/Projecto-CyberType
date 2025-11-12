const startGameButton = document.getElementById('startGameButton');
const divArea = document.getElementById("nameArea");
const infoText = document.getElementById("infoText");

const getVisibleElement = (btn) => btn ? (btn.querySelector('a') || btn) : null;
const getFirstLetter = (element) => {
    if (!element) return '';
    const text = (element.innerText || element.textContent || '').trim();
    return text.charAt(0).toLowerCase();
};
const underlineFirstLetter = (element) => {
    if (!element) return;
    const text = (element.innerText || element.textContent || '').trim();
    if (!text) return;
    const first = text.charAt(0);
    const rest = text.slice(1);
    element.innerHTML = `<u>${first}</u>${rest}`;
};

const visibleStart = getVisibleElement(startGameButton);
underlineFirstLetter(visibleStart);

document.addEventListener("keydown", (event) => {
    const activeElement = document.activeElement;
    const isTyping = (
        activeElement && (activeElement.tagName && (activeElement.tagName.toLowerCase() === "input" || activeElement.tagName.toLowerCase() === "textarea") || activeElement.isContentEditable)
    );
    if (isTyping) return;

    const key = event.key.toLowerCase();
    const startKey = getFirstLetter(visibleStart);
    if (startKey && key === startKey) {
        event.preventDefault();
        startGameButton && startGameButton.click();
    }
});


startGameButton && startGameButton.addEventListener('click', () => {
    const inputNameValue = document.getElementById("inputName").value;
    const selectDifficultyValue = document.getElementById("selectDifficulty").value;
    const permadeathCheckbox = document.getElementById("checkbox");
    const permadeathValue = permadeathCheckbox.checked ? 1 : 0;

    if (inputNameValue.trim() === "") {
        infoText.style.color = "red";
        infoText.textContent = "Por favor, ingresa tu nombre para continuar.";
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

        // Crear input hidden para si hay permadeath o no
        const permaInput = document.createElement("input");
        permaInput.type = "hidden";
        permaInput.name = "permadeathCheckbox";
        permaInput.value = permadeathValue;
        form.appendChild(permaInput);

        // Añadir el formulario al body y enviarlo
        document.body.appendChild(form);
        form.submit();
    }
});