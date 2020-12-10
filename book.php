<?php
    session_start();
    require_once __DIR__ . "/lib/Utils.php";
?>
<?php require_once __DIR__ . "/template/header.php" ?>

    <div class="row">
        <div class="col">
            <div id="book-cover" class="book-title-box reset-cursor"></div>
        </div>

        <div id="book-info" class="col flex-center">
            <div class="row space-between">
                <div class="col p-10 description-name">Nome:</div>
                <div id="bookName" class="col p-10 description-value"></div>
            </div>
            <div class="row space-between">
                <div class="col p-10 description-name">Condiviso da:</div>
                <div id="ownerName" class="col p-10 description-value"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div id="rating-row"></div>

        <div class="book-button background-red">
            <a id="download-button" href="">
                DOWNLOAD
            </a>
        </div>
    </div>

    <div class="row m-10">
        <h2>Review this book</h2>
    </div>
<?php if(isLogged()) { ?>
    <div class="row form-background p-20">
        <div class="col">
            <label for="title" class="d-block">
                Titolo:
                <input type="text" id="title" name="title" class="form-input" placeholder="Titolo">
            </label>
            <div class="rating reversed">
            <?php
                for($i = 5; $i > 0; $i--){
            ?>
                <input type="radio" class="star-radio" id="rate<?php echo $i; ?>" name="rate" value="<?php echo $i; ?>"/>
                <label for="rate<?php echo $i; ?>" class="star"></label>
            <?php
                }
            ?>
            </div>
            <textarea name="content" id="content" cols="50" rows="10" placeholder="Contenuto"></textarea>
        </div>
        <div class="col">
            <div class="col">
                <div class="error-banner"></div>
                <input type="button" id="submit" class="form-input form-button background-red p-20" value="INVIA">
            </div>
        </div>
    </div>
<?php } ?>


    <div class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <header>
                    <p></p>
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
    <script src="js/book.js"></script>

<?php require_once __DIR__ . "/template/footer.php" ?>