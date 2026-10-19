// Theme Toggle

    const themeToggle = document.getElementById("themeToggle");
    const html = document.documentElement;

    themeToggle.addEventListener("click", function () {

        const currentTheme = html.getAttribute("data-theme");

        if (currentTheme === "dark") {
            html.setAttribute("data-theme", "light");
            themeToggle.innerHTML = '<span class="theme-icon">☾</span><span>Night</span>';
        } else {
            html.setAttribute("data-theme", "dark");
            themeToggle.innerHTML = '<span class="theme-icon">☀</span><span>Day</span>';
        }

    });