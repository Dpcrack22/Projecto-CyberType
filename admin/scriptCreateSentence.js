const createSentence = document.getElementById("createSentence");

document.addEventListener("keydown", (event) => {
    const activeElement = document.activeElement;
    const isTyping = (
        activeElement.tagName.toLowerCase() === "input" ||
        activeElement.tagName.toLowerCase() === "textarea" ||
        activeElement.isContentEditable
    );

    if (isTyping) return;
    
    if (event.key === "a") {
        createSentence.click();
    } else if (event.key === "v") {
        window.location.href="/admin/index.php";
    }
});