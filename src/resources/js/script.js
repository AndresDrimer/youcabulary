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


/*THIS BREAKS ERROR MSG SYSTEM, I PREFERE TO GO WITH IT AND WITHOUT SPINNER BY NOW. SHOULD BE RE-CHECKED 
//////loading spinner

document.querySelector('#add-word').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(event.target);
    document.getElementById('loading').style.display = 'block';
    
    fetch(event.target.action, {
        method: 'POST',
        body: formData
    })
    }).catch(function(error) {
        console.error('There has been a problem with your fetch operation:', error);
        // Ocultar el spinner de carga
        document.getElementById('loading').style.display = 'none';
    });

*/



