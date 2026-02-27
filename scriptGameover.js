const jugarDeNuevoButton = document.getElementById("jugarDeNuevoButton");
const almacenarRankingButton = document.getElementById("almacenarRankingButton");

// Helper: obtener elemento visible (si hay <a> dentro usarlo)
const getVisibleElement = (btn) => btn ? (btn.querySelector('a') || btn) : null;
const visibleJugar = getVisibleElement(jugarDeNuevoButton);
const visibleAlmacenar = getVisibleElement(almacenarRankingButton);

const getFirstLetter = (el) => {
    if (!el) return '';
    const text = (el.innerText || el.textContent || '').trim();
    return text.charAt(0).toLowerCase();
};

const underlineFirstLetter = (el) => {
    if (!el) return;
    const text = (el.innerText || el.textContent || '').trim();
    if (!text) return;
    const first = text.charAt(0);
    const rest = text.slice(1);
    el.innerHTML = `<u>${first}</u>${rest}`;
};

underlineFirstLetter(visibleJugar);
underlineFirstLetter(visibleAlmacenar);

document.addEventListener("keydown", (event) => {
    const activeElement = document.activeElement;
    const isTyping = (
        activeElement && (activeElement.tagName.toLowerCase() === "input" || activeElement.tagName.toLowerCase() === "textarea" || activeElement.isContentEditable)
    );
    if (isTyping) return;

    const key = event.key.toLowerCase();
    const jugarKey = getFirstLetter(visibleJugar);
    const almacenarKey = getFirstLetter(visibleAlmacenar);

    if (jugarKey && key === jugarKey) {
        event.preventDefault();
        window.location.href = "index.php";
    }
    if (almacenarKey && key === almacenarKey) {
        event.preventDefault();
        almacenarRankingButton && almacenarRankingButton.click();
    }
});

jugarDeNuevoButton && jugarDeNuevoButton.addEventListener("click", () => {
    window.location.href= "index.php";
});