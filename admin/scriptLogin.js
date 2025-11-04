const loginButton = document.getElementById("loginButton");

document.addEventListener("keydown", (event) => {
    if (event.key === "i") {
        loginButton.click();
    }
});