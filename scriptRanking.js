const RankingButton = document.getElementById("RankingButton");

document.addEventListener("keydown", (event) => {
    if (event.key.toLowerCase() === 'v') {
        window.location.href = "index.php";
    }
});

RankingButton.addEventListener("click", () => {
    window.location.href= "index.php";
})