const Button403 = document.getElementById("Button403");
const visible403 = Button403 ? (Button403.querySelector('a') || Button403) : null;
const getFirstLetter = (el) => el ? ((el.innerText || el.textContent || '').trim().charAt(0) || '').toLowerCase() : '';
const underlineFirstLetter = (el) => {
    if (!el) return;
    const text = (el.innerText || el.textContent || '').trim();
    if (!text) return;
    const first = text.charAt(0);
    const rest = text.slice(1);
    el.innerHTML = `<u>${first}</u>${rest}`;
};

underlineFirstLetter(visible403);

document.addEventListener("keydown", (event) => {
    const activeElement = document.activeElement;
    const isTyping = (
        activeElement && (activeElement.tagName.toLowerCase() === "input" || activeElement.tagName.toLowerCase() === "textarea" || activeElement.isContentEditable)
    );
    if (isTyping) return;

    const key = event.key.toLowerCase();
    const backKey = getFirstLetter(visible403);
    if (backKey && key === backKey) {
        event.preventDefault();
        window.location.href = "index.php";
    }
});

Button403 && Button403.addEventListener("click", () => {
    window.location.href= "index.php";
});