document.querySelectorAll('.each-term').forEach((term, index) => {
    term.addEventListener('click', () => {
        const definition = document.getElementById('def-' + index);
        definition.scrollIntoView({behavior: "smooth"});
    });
});

function showMPhilosophyModal() {
    var modal = document.getElementById("philosophy-modal");
    modal.style.display = "block";
}

function closePhiloModal() {
    var modal = document.getElementById("philosophy-modal");
    modal.style.display = "none";
}