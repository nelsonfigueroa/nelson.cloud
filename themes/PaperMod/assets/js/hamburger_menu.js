// Source: https://github.com/adityatelange/hugo-PaperMod/discussions/943
let b = document.querySelector("#menu-trigger")
let m = document.querySelector(".menu")
b.addEventListener("click", function () {
    m.classList.toggle("hidden")
});
// hide menu when clicked outside
document.body.addEventListener('click', function (event) {
    if (!b.contains(event.target)) {
        m.classList.add("hidden")
    }
});