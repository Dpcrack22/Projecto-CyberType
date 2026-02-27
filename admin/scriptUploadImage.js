const uploadBtn = document.getElementById('uploadImageButton');
const backBtn = document.getElementById('btn-volverIndex');

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

const visibleBack = getVisibleElement(backBtn);
underlineFirstLetter(visibleBack);

document.addEventListener('keydown', (event) => {
    const activeElement = document.activeElement;
    const isTyping = (
        activeElement && (activeElement.tagName && (activeElement.tagName.toLowerCase() === 'input' || activeElement.tagName.toLowerCase() === 'textarea') || activeElement.isContentEditable)
    );
    if (isTyping) return;

    const key = event.key.toLowerCase();
    const backKey = getFirstLetter(visibleBack);

    if (backKey && key === backKey) {
        event.preventDefault();
        const anchor = backBtn && backBtn.querySelector('a');
        if (anchor && anchor.getAttribute('href')) {
            window.location.href = anchor.getAttribute('href');
        } else {
            window.location.href = '/admin/index.php';
        }
    }
    // Do NOT create a keyboard shortcut for the upload button (user requested upload without hotkey)
});