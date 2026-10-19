(() => {
    const root = document.documentElement;
    const themeToggle = document.getElementById("themeToggle");
    const themeIcon = document.querySelector(".theme-icon");
    const themeLabel = document.querySelector(".theme-label");
    const menuBtn = document.getElementById("menuBtn");
    const mobileMenu = document.getElementById("mobileMenu");
    const header = document.getElementById("siteHeader");

    const savedTheme = localStorage.getItem("carewell-theme");
    const preferredDark = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches;

    function applyTheme(theme) {
        root.setAttribute("data-theme", theme);
        localStorage.setItem("carewell-theme", theme);

        if (theme === "dark") {
            themeIcon.textContent = "☀";
            themeLabel.textContent = "Day";
        } else {
            themeIcon.textContent = "☾";
            themeLabel.textContent = "Night";
        }
    }

    applyTheme(savedTheme || (preferredDark ? "dark" : "light"));

    themeToggle?.addEventListener("click", () => {
        applyTheme(root.getAttribute("data-theme") === "dark" ? "light" : "dark");
    });

    menuBtn?.addEventListener("click", () => {
        mobileMenu.classList.toggle("open");
        menuBtn.textContent = mobileMenu.classList.contains("open") ? "✕" : "☰";
    });

    mobileMenu?.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", () => {
            mobileMenu.classList.remove("open");
            if (menuBtn) menuBtn.textContent = "☰";
        });
    });

    const navLinks = document.querySelectorAll(".desktop-nav a[href^='#']");
    const sections = [...document.querySelectorAll("main section[id]")];

    function updateActiveNav() {
        const y = window.scrollY + 150;
        let current = "home";
        sections.forEach(section => {
            if (y >= section.offsetTop) current = section.id;
        });
        navLinks.forEach(link => {
            link.classList.toggle("active", link.getAttribute("href") === "#" + current);
        });
    }

    window.addEventListener("scroll", updateActiveNav, { passive: true });
    updateActiveNav();

    window.addEventListener("scroll", () => {
        if (!header) return;
        header.style.boxShadow = window.scrollY > 20
            ? "0 8px 35px rgba(20,70,100,.08)"
            : "none";
    }, { passive: true });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll(".reveal").forEach(el => observer.observe(el));

    document.querySelectorAll("[data-year]").forEach(el => {
        el.textContent = new Date().getFullYear();
    });
})();
