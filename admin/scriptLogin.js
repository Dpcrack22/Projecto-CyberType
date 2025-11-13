const loginButton = document.getElementById("loginButton");

const getVisibleElement = (btn) => btn ? (btn.querySelector('a') || btn) : null;
const getFirstLetter = (el) => el ? ((el.innerText || el.textContent || '').trim().charAt(0) || '').toLowerCase() : '';
const underlineFirstLetter = (el) => {
    if (!el) return;
    const text = (el.innerText || el.textContent || '').trim();
    if (!text) return;
    const first = text.charAt(0);
    const rest = text.slice(1);
    el.innerHTML = `<u>${first}</u>${rest}`;
};

const visibleLogin = getVisibleElement(loginButton);
underlineFirstLetter(visibleLogin);

document.addEventListener("keydown", (event) => {
    const activeElement = document.activeElement;
    const isTyping = (
        activeElement && (activeElement.tagName && (activeElement.tagName.toLowerCase() === "input" || activeElement.tagName.toLowerCase() === "textarea") || activeElement.isContentEditable)
    );
    if (isTyping) return;

    const key = event.key.toLowerCase();
    const loginKey = getFirstLetter(visibleLogin);

    if (loginKey && key === loginKey) {
        event.preventDefault();
        loginButton && loginButton.click();
    }
});