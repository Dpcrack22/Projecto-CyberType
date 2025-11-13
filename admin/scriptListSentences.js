const ButtonListSentences = document.getElementById("ButtonListSentences");
const ButtonLogs = document.getElementById("ButtonLogs");

const getVisibleElement = (btn) => btn ? (btn.querySelector('a') || btn) : null;
const visibleList = getVisibleElement(ButtonListSentences);
const visibleLogs = getVisibleElement(ButtonLogs);

const getFirstLetter = (el) => el ? ((el.innerText || el.textContent || '').trim().charAt(0) || '').toLowerCase() : '';
const underlineFirstLetter = (el) => {
    if (!el) return;
    const text = (el.innerText || el.textContent || '').trim();
    if (!text) return;
    const first = text.charAt(0);
    const rest = text.slice(1);
    el.innerHTML = `<u>${first}</u>${rest}`;
};

underlineFirstLetter(visibleList);
underlineFirstLetter(visibleLogs);

document.addEventListener("keydown", (event) => {
    const activeElement = document.activeElement;
    const isTyping = (
        activeElement && (activeElement.tagName && (activeElement.tagName.toLowerCase() === "input" || activeElement.tagName.toLowerCase() === "textarea") || activeElement.isContentEditable)
    );
    if (isTyping) return;

    const key = event.key.toLowerCase();

    // Build mapping by reading actual anchor hrefs when available. This avoids mapping a back button to the current page.
    const map = new Map();
    if (visibleList) {
        const anchor = ButtonListSentences.querySelector('a');
        const href = anchor ? anchor.getAttribute('href') : '/admin/index.php';
        const targetPath = new URL(href, window.location.origin).pathname;
        if (targetPath !== window.location.pathname) {
            map.set(getFirstLetter(visibleList), () => window.location.href = href);
        }
    }
    if (visibleLogs) {
        const anchor = ButtonLogs.querySelector('a');
        const href = anchor ? anchor.getAttribute('href') : '/admin/logs.php';
        const targetPath = new URL(href, window.location.origin).pathname;
        if (targetPath !== window.location.pathname) {
            map.set(getFirstLetter(visibleLogs), () => window.location.href = href);
        }
    }

    if (map.has(key)) {
        event.preventDefault();
        map.get(key)();
    }
});