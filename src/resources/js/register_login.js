//toggle Register and Signup Form
document.addEventListener("DOMContentLoaded", function () {
  let signupForm = document.querySelector("#signupForm");
  let registerForm = document.querySelector("#registerForm");
  let checkBoxSign = document.querySelector("#checkBoxSign");

  let labelSignup = document.querySelector("#label-signup");
  let labelRegister = document.querySelector("#label-register");

  // Initialize signupForm as visible and registerForm as hidden
  if (signupForm){
     signupForm.style.display = "flex";
  }
 if (registerForm){
 registerForm.style.display = "none";
 }
 
if(checkBoxSign){
  checkBoxSign.addEventListener("change", function () {
    if (this.checked) {
      signupForm.style.display = "none";
      registerForm.style.display = "flex";
      labelSignup.style.color = "var(--gray)";
      labelRegister.style.color = "var(--blue)";
    } else {
      signupForm.style.display = "flex";
      registerForm.style.display = "none";
      labelSignup.style.color = "var(--blue)";
      labelRegister.style.color = "var(--gray)";
    }
  });}
});

// Shows alert and superposition layer
let closeBtn = document.querySelector(".close");
let alertModal = document.querySelector(".alert");
let overlay = document.querySelector(".back-overlay");
let audio = new Audio('public/audio/click.mp3');
//close modal and play sound
if(closeBtn){
closeBtn.addEventListener("click", () => {
  alertModal.style.display = "none";
  overlay.style.display = "none";
  audio.play();
});}
