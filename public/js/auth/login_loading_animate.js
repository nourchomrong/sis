document.addEventListener("DOMContentLoaded", () => {
    setTimeout(() => {
        const loader = document.getElementById("loader-overlay");
        const main = document.getElementById("main-content");
        loader.style.transition = "opacity 0.5s ease";
        loader.style.opacity = 0;
        setTimeout(() => {
            loader.style.display = "none";
            main.classList.remove("hidden");
            main.querySelectorAll("h1, p, button").forEach((el) => {
                el.classList.add(
                    "transition-opacity",
                    "duration-500",
                    "opacity-100",
                );
            });
        }, 500);
    }, 700);
});
