const Button404 = document.getElementById("Button404");
const visible404 = Button404 ? (Button404.querySelector('a') || Button404) : null;
const getFirstLetter = (el) => el ? ((el.innerText || el.textContent || '').trim().charAt(0) || '').toLowerCase() : '';
const underlineFirstLetter = (el) => {
    if (!el) return;
    const text = (el.innerText || el.textContent || '').trim();
    if (!text) return;
    const first = text.charAt(0);
    const rest = text.slice(1);
    el.innerHTML = `<u>${first}</u>${rest}`;
};

underlineFirstLetter(visible404);

document.addEventListener("keydown", (event) => {
    const activeElement = document.activeElement;
    const isTyping = (
        activeElement && (activeElement.tagName.toLowerCase() === "input" || activeElement.tagName.toLowerCase() === "textarea" || activeElement.isContentEditable)
    );
    if (isTyping) return;

    const key = event.key.toLowerCase();
    const backKey = getFirstLetter(visible404);
    if (backKey && key === backKey) {
        event.preventDefault();
        window.location.href = "index.php";
    }
});

Button404 && Button404.addEventListener("click", () => {
    window.location.href= "index.php";
});