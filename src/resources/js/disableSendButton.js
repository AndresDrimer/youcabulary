//disable send button till passwords match
let btn = document.getElementById('submit-send-new-pass-btn');
let pass = document.getElementById('new-pass');
let pass_copy = document.getElementById('new-pass-copy');

function checkPasswordsMatch() {
    if (pass.value === pass_copy.value && pass.value !== "" && pass_copy.value !== "") {
        btn.disabled = false;
        pass_copy.classList.remove('error');
        pass_copy.classList.add('success-border');
        btn.classList.add('paint-btn-success');
    } else {
        btn.disabled = true;
        pass_copy.classList.add('error');
        pass_copy.classList.remove('success-border');
        btn.classList.remove('paint-btn-success');
    }
}

pass.oninput = checkPasswordsMatch;
pass_copy.oninput = checkPasswordsMatch;