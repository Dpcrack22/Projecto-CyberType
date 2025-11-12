const listarFrasesButton = document.getElementById("listarFrasesButton");
const agregarFraseButton = document.getElementById("agregarFraseButton");
const logsButton = document.getElementById("verLogsButton");

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

const visibleList = getVisibleElement(listarFrasesButton);
const visibleAdd = getVisibleElement(agregarFraseButton);
const visibleLogs = getVisibleElement(logsButton);

underlineFirstLetter(visibleList);
underlineFirstLetter(visibleAdd);
underlineFirstLetter(visibleLogs);
underlineFirstLetter(visibleImg);

document.addEventListener("keydown", (event) => {
    const activeElement = document.activeElement;
    const isTyping = (
        activeElement && (activeElement.tagName && (activeElement.tagName.toLowerCase() === "input" || activeElement.tagName.toLowerCase() === "textarea") || activeElement.isContentEditable)
    );
    if (isTyping) return;

    const key = event.key.toLowerCase();
    const map = new Map();
    if (getFirstLetter(visibleList)) map.set(getFirstLetter(visibleList), () => window.location.href="/admin/list_sentences.php");
    if (getFirstLetter(visibleAdd)) map.set(getFirstLetter(visibleAdd), () => window.location.href="/admin/create_sentence.php");
    if (getFirstLetter(visibleLogs)) map.set(getFirstLetter(visibleLogs), () => window.location.href="/admin/logs.php");
    if (getFirstLetter(visibleImg)) map.set(getFirstLetter(visibleImg), () => window.location.href="/admin/add_image.php");

    if (map.has(key)) {
        event.preventDefault();
        map.get(key)();
    }
});

listarFrasesButton && listarFrasesButton.addEventListener("click", () => {
    window.location.href="/admin/list_sentences.php";
});

agregarFraseButton && agregarFraseButton.addEventListener("click", () => {
    window.location.href="/admin/create_sentence.php";
});

logsButton && logsButton.addEventListener("click", () => {
    window.location.href="/admin/logs.php";
});

