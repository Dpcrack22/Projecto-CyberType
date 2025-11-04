const loginButton = document.getElementById("loginButton");

document.addEventListener("keydown", (event) => {
    const activeElement = document.activeElement;
    const isTyping = (
        activeElement.tagName.toLowerCase() === "input" ||
        activeElement.tagName.toLowerCase() === "textarea" ||
        activeElement.isContentEditable
    );

    if (isTyping) return;
    
    if (event.key === "i") {
        loginButton.click();
    }
});