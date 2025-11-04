const jugarDeNuevoButton = document.getElementById("jugarDeNuevoButton");
const almacenarRankingButton = document.getElementById("almacenarRankingButton");

document.addEventListener("keydown", (event) => {
    if (event.key.toLowerCase() === 'j' || event.key.toLowerCase() === 'a') {
        (event.key.toLowerCase() === 'j') ? window.location.href="index.php" : almacenarRankingButton.click();
    }
});

jugarDeNuevoButton.addEventListener("click", () => {
    window.location.href= "index.php";
})