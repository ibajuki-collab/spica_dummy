const menu = document.querySelector(".menu");
const links = document.querySelector(".nav-links");

if (menu && links) {
    menu.addEventListener("click", () => {
        links.classList.toggle("open");
    });
}

const darkModeToggle = document.getElementById("darkModeToggle");


if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");

    if (darkModeToggle) {
        darkModeToggle.textContent = "☀️";
    }
}


if (darkModeToggle) {

    darkModeToggle.addEventListener("click", () => {

        document.body.classList.toggle("dark-mode");


        if (document.body.classList.contains("dark-mode")) {

            localStorage.setItem("darkMode", "enabled");

            darkModeToggle.textContent = "☀️";

        } else {

            localStorage.setItem("darkMode", "disabled");

            darkModeToggle.textContent = "🌙";

        }

    });

}