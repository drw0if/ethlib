<?php
    session_start();
    require_once __DIR__ . "/lib/Utils.php";

    if(!isLogged()){
        header("Location: signin.php");
    }
?>

<?php require_once __DIR__ . "/template/header.php"; ?>

    <div class="row">
        <label for="file-input" id="file-label" class="book-title-box">
        </label>

        <input type="file" name="file" id="file-input" accept="application/pdf,application/epub+zip">

        <div class="centered-container form-background padded-container">
            <label for="private" class="d-block center">
                <input type="checkbox" id="private" name="private">
                Privato
            </label>

            <label for="name" class="d-block">
                Nome*
                <input type="text" id="name" name="name" class="form-input" placeholder="1984">
            </label>
            <label for="isbn" class="d-block">
                ISBN
                <input type="text" id="isbn" name="isbn" class="form-input" placeholder="isbn">
            </label>

            <div class="error-banner center"></div>
            <input type="button" id="submit" class="form-input form-button background-red" value="UPLOAD">
        </div>
    </div>

    <div class="row">
        <div id="progress-bar">
            <p id="progress-bar-label"></p>
            <div class="center"></div>
        </div>
    </div>

    <div class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <header>
                    <p>Esito caricamento</p>
                    <button class="modal-close">
                        X
                    </button>
                </header>
                <div class="modal-main">
                    <p class="modal-text">
                        Caricamento completato con successo!
                    </p>
                </div>
                <footer>
                    <button class="modal-button background-red text-white">
                        OK
                    </button>
                </footer>
            </div>
        </div>
    </div>

    <script>
        // Layout elements
        const errorBanner = document.getElementsByClassName('error-banner')[0];
        const fileLabel = document.getElementById('file-label');
        const innerBar = document.getElementById('progress-bar').children[1];
        const barLabel = document.getElementById('progress-bar-label');
        const modal = document.getElementsByClassName('modal')[0];
        const modalText = document.getElementsByClassName('modal-text')[0];

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

        document.getElementsByClassName('modal-close')[0].onclick = 
            document.getElementsByClassName('modal-button')[0].onclick = function(){
                fileInput.value = '';
                modal.classList.toggle('show');
                reset();
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
                innerBar.style.background = "red";
                showError("Errore nel caricamento del file");
            };

            post.onload = function(e) {
                if(post.status === 400 || post.status === 404 || post.status === 500){
                    post.onerror();
                    return;
                }

                bar(100);
                innerBar.style.background = "#2ecc71";
                modalText.innerText = 'Caricamento completato!';
                modal.classList.add('show');
            };

            post.open("POST", "api/v1/upload.php");
            post.send(data);
        }
    </script>
<?php require_once __DIR__ . "/template/footer.php"; ?>