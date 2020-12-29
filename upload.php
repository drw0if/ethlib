<?php
    session_start();
    require_once __DIR__ . '/lib/Utils.php';

    if(!isLogged()){
        header('Location: signin.php');
    }
?>

<?php require_once __DIR__ . '/template/header.php'; ?>

    <div class="row">
        <label for="file-input" id="file-label" class="book-title-box">
        </label>

        <input type="file" name="file" id="file-input" accept="application/pdf,application/epub+zip">

        <div class="centered-container form-background p-20">
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
                    <p class="modal-text"></p>
                </div>
                <footer>
                    <button class="modal-button background-red text-white">
                        OK
                    </button>
                </footer>
            </div>
        </div>
    </div>

    <script src="js/modal.js"></script>
    <script src="js/upload.js"></script>
<?php require_once __DIR__ . '/template/footer.php'; ?>