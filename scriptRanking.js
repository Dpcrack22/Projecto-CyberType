const RankingButton = document.getElementById("RankingButton");

const visibleRanking = RankingButton ? (RankingButton.querySelector('a') || RankingButton) : null;
const getFirstLetter = (el) => el ? ((el.innerText || el.textContent || '').trim().charAt(0) || '').toLowerCase() : '';
const underlineFirstLetter = (el) => {
    if (!el) return;
    const text = (el.innerText || el.textContent || '').trim();
    if (!text) return;
    const first = text.charAt(0);
    const rest = text.slice(1);
    el.innerHTML = `<u>${first}</u>${rest}`;
};

underlineFirstLetter(visibleRanking);

document.addEventListener("keydown", (event) => {
    const activeElement = document.activeElement;
    const isTyping = (
        activeElement && (activeElement.tagName.toLowerCase() === "input" || activeElement.tagName.toLowerCase() === "textarea" || activeElement.isContentEditable)
    );
    if (isTyping) return;

    const key = event.key.toLowerCase();
    const rankKey = getFirstLetter(visibleRanking);
    if (rankKey && key === rankKey) {
        event.preventDefault();
        window.location.href = "index.php";
    }
});

RankingButton && RankingButton.addEventListener("click", () => {
    window.location.href= "index.php";
});