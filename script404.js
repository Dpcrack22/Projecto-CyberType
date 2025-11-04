const Button404 = document.getElementById("Button404");

document.addEventListener("keydown", (event) => {
    if (event.key.toLowerCase() === 'v') {
        window.location.href = "index.php";
    }
});

Button404.addEventListener("click", () => {
    window.location.href= "index.php";
})