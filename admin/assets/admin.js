(function () {

    const html = document.documentElement;
    const themeButtons = document.querySelectorAll(".admin-theme-button");

    function getTheme() {
        return localStorage.getItem("spicaAdminTheme") || "light";
    }

    function applyTheme(theme) {

        if (theme === "dark") {
            html.setAttribute("data-theme", "dark");
        } else {
            html.setAttribute("data-theme", "light");
        }

        themeButtons.forEach(function (button) {

            if (theme === "dark") {
                button.innerHTML = "☀️ Light Mode";
            } else {
                button.innerHTML = "🌙 Dark Mode";
            }

        });
    }

    applyTheme(getTheme());

    themeButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            const currentTheme = getTheme();

            const newTheme =
                currentTheme === "dark"
                    ? "light"
                    : "dark";

            localStorage.setItem(
                "spicaAdminTheme",
                newTheme
            );

            applyTheme(newTheme);

        });

    });

})();