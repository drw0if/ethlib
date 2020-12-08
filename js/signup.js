const submitButton = document.getElementById("submitButton");
const form = document.getElementById("form");
const errorBanner = document.getElementsByClassName("error-banner")[0];

const showError = function(msg) {
    errorBanner.innerText = msg;
}

submitButton.onclick = function(evt){
    for(let i = 0; i < 4; i++){
        let input = form.children[i];
        let check = input.validity;
        if(check.valueMissing){
            showError(input.placeholder + " mancante");
            return false;
        }
        if(check.typeMismatch){
            showError(input.placeholder + " non valida");
            return false;
        }
    }

    let password = form.children[2].value;

    if(password.match(/[a-z]/) == null){
        showError("Nella password serve almeno una lettera minuscola");
        return false;
    }
    if(password.match(/[A-Z]/) == null){
        showError("Nella password serve almeno una lettera maiuscola");
        return false;
    }
    if(password.match(/[0-9]/) == null){
        showError("Nella password serve almeno una cifra");
        return false;
    }
    if(password.match(/\W|_/) == null){
        showError("Nella password serve almeno un carattere speciale");
        return false;
    }

    if(password != form.children[3].value){
        showError("La conferma non coincide con la password");
        return false;
    }
}