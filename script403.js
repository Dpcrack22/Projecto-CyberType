const Button403 = document.getElementById("Button403");

document.addEventListener("keydown", (event) => {
    if (event.key.toLowerCase() === 'v') {
        window.location.href = "index.php";
    }
});

Button403.addEventListener("click", () => {
    window.location.href= "index.php";
})