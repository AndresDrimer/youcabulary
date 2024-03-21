//scroll to clikced word
document.querySelectorAll('.each-term').forEach((term, index) => {
    term.addEventListener('click', () => {
        const definition = document.getElementById('def-' + index);
        definition.scrollIntoView({behavior: "smooth", block:'center'});
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


// Shows alert and superposition layer
let closeBtn = document.querySelector(".close");
let closeSelfModal = document.querySelector("#close-self-modal");
let alertModal = document.querySelector(".alert");
let overlay = document.querySelector(".back-overlay");
let audio = new Audio('public/audio/cork.wav');
//close modal and play sound
if(closeBtn){
closeBtn.addEventListener("click", () => {
  alertModal.style.display = "none";
  overlay.style.display = "none";
  audio.play();
});}
if(closeSelfModal){
  closeSelfModal.addEventListener("click", () => {
    alertModal.style.display = "none";
    overlay.style.display = "none";
    audio.play();
  });}


  //modal to scroll back to top functionally
  window.onscroll = function() {showModal()};

  function showModal() {
    if (document.body.scrollTop > 150 || document.documentElement.scrollTop > 150) {
       document.getElementById("scrollModal").style.display = "block";
       document.getElementById("scrollModal").style.visibility = "visible"; 
       
    } else {
       document.getElementById("scrollModal").style.display = "none";
       document.getElementById("scrollModal").style.visibility = "hidden"; 
    }
   }
// Obtiene el elemento <span> que cierra el modal
var span = document.getElementsByClassName("closeScrollModal")[0];

// Cuando el usuario hace clic en <span> (x), cierra el modal
span.onclick = function() {
 document.getElementById("scrollModal").style.display = "none";
 window.scrollTo(0, 0); // Vuelve al inicio de la página
}

// Cuando el usuario hace clic en cualquier lugar fuera del modal, lo cierra
window.onclick = function(event) {
 if (event.target == document.getElementById("scrollModal")) {
    document.getElementById("scrollModal").style.display = "none";
    window.scrollTo(0, 0); // Vuelve al inicio de la página
 }
}
