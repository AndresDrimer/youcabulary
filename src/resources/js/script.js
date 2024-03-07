//scroll to clikced word
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



//make display to invite user to write on first usage disappear on add btn click.
let addBtn = document.getElementById('add-word');
let starterText = document.querySelector('.starter-text');
if(addBtn){
addBtn.addEventListener('click', function(){
    starterText.classList.add('fade-out');
}, {once:true});
}
var fadeOutElements = document.querySelectorAll('.fade-out');

fadeOutElements.forEach(function(element) {
  element.addEventListener('animationend', function() {
    this.style.display = 'none';
  });
});

//if new world added, scroll to thta term when reloading
//cuando recarga
document.addEventListener('DOMContentLoaded', function() {
    // Función para obtener el ID de la última palabra agregada de la URL
    function getLastWordIdFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('lastWordId');
    }

    // Función para verificar si un elemento con un ID específico existe
    function elementExists(id) {
        return document.getElementById(id) !== null;
    }

    // Función para desplazarse hasta el elemento si existe
    function scrollToLastWord() {
        const lastWordId = getLastWordIdFromUrl();
        if (lastWordId && elementExists('term-' + lastWordId)) {
            const lastWordElement = document.getElementById('term-' + lastWordId);
            lastWordElement.scrollIntoView({
                behavior: "smooth",
                block: "center",
                inline: "center"
            });
        }
    }

    // Llama a la función para desplazarte hasta la última palabra agregada
    scrollToLastWord();
});

