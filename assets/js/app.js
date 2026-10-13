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

const revealElements = document.querySelectorAll(
    ".reveal, .reveal-left, .reveal-right"
);

const revealObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("active");
                revealObserver.unobserve(entry.target);
            }
        });
    },
    {
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    }
);

revealElements.forEach((element) => {
    revealObserver.observe(element);
});