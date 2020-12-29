// Layout elements
const errorBanner = document.getElementsByClassName('error-banner')[0];
const fileLabel = document.getElementById('file-label');
const innerBar = document.getElementById('progress-bar').children[1];
const barLabel = document.getElementById('progress-bar-label');
const modal = new Modal(
    document.getElementsByClassName('modal')[0],
    function (){
        fileInput.value = '';
        reset();
    }
);

// Form controls
const fileInput = document.getElementById('file-input')
const submitButton = document.getElementById('submit');
const privateCheck = document.getElementById('private');
const nameInput = document.getElementById('name');
const isbnInput = document.getElementById('isbn');

const bar = function(value) {
    value = Math.max(0, value);
    value = Math.min(100, value);
    innerBar.style.width = value + "%";
    barLabel.innerText = value + "%";
}

const showError = function(msg){
    errorBanner.innerText = msg;
}

const reset = function(){
    fileLabel.innerText = 'Clicca per scegliere il contenuto';
    nameInput.value = '';
    privateCheck.checked = false;
    showError('');
    bar(0);
}

fileInput.onchange = function(e){
    reset();
    innerBar.style.backgroundColor = 'green';
    fileLabel.innerText = e.target.files[0].name.split('.')[0];
}

reset();

submitButton.onclick = function() {
    const files = fileInput.files;
    if(files.length == 0){
        showError('Nessun file selezionato!');
        return;
    }

    var name = nameInput.value.trim();
    if(name.length == 0){
        showError('Nome mancante!');
        return;
    }

    var private = privateCheck.checked;
    var isbn = isbnInput.value;

    if(isbn.length > 0 && isbn.match('^(\d{10}|\d{13})$') == null){
        showError("L' ISBN non corrisponde al formato");
        return;
    }

    var data = new FormData();
    data.append('file', files[0]);
    data.append('name', name);
    data.append('private', private);
    data.append('isbn', isbn);

    var post = new XMLHttpRequest();

    post.onprogress = function(e) {
        bar((e.loaded / e.total) * 100);
    };

    post.onerror = post.onabort = function() {
        bar(100);
        innerBar.style.background = 'red';
        showError('Errore nel caricamento del file');
    };

    post.onload = function(e) {
        if(post.status != 201){
            post.onerror();
            return;
        }

        bar(100);
        innerBar.style.background = '#2ecc71';
        modal.setContent('Caricamento completato!');
        modal.show();
    };

    post.open('POST', 'api/v1/upload.php');
    post.send(data);
}