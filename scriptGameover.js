const jugarDeNuevoButton = document.getElementById("jugarDeNuevoButton");
const almacenarRankingButton = document.getElementById("almacenarRankingButton");

document.addEventListener("keydown", (event) => {
    if (event.key.toLowerCase() === 'j') {
        window.location.href = "index.php";
    }
    if (event.key.toLowerCase() === 'a') {
        document.getElementById("almacenarRankingButton").click();
    }
});

jugarDeNuevoButton.addEventListener("click", () => {
    window.location.href= "index.php";
})