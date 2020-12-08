<?php

    session_start();

    //If no book requested die
    if(!isset($_GET['book_id']) || trim(empty($_GET['book_id']))){
        http_response_code(404);
        die();
    }

    require_once __DIR__ . "/lib/Models.php";
    require_once __DIR__ . "/lib/Utils.php";

    $book_id = trim($_GET['book_id']);

    $ans = Book::filter_by([
        'book_id' => $book_id
    ]);

    //If no book found die
    if(count($ans) == 0){
        http_response_code(404);
        die();
    }

    $book = Book::toObject($ans[0]);
    $ans = User::filter_by([
        'user_id' => $book->user_id
    ]);
    $owner = User::toObject($ans[0]);

    //if book is private but you are not logged or you are not the owner die
    if($book->private){
        if(!isLogged() || ($book->user_id != $_SESSION['user_id'])){
            http_response_code(404);
            die();
        }
    }

    $data = $book->openData();

    $dataToPrint = [
        'title' => 'Titolo originale',
        'publishers' => 'Editore',
    ];

?>

<?php require_once __DIR__ . "/template/header.php" ?>

    <div class="row">
        <div class="col">
            <div class="book-title-box reset-cursor">
                <?php
                    if($data != NULL)
                        echo '<img src="http://covers.openlibrary.org/b/isbn/' . $book->isbn . '-M.jpg" alt="No image available">';
                    else echo $book->name;
                ?>
            </div>
        </div>
        <div class="col flex-center">
            <div class="row space-between">
                <div class="col p-10 description-name">Nome:</div>
                <div class="col p-10 description-value"><?php echo $book->name; ?></div>
            </div>
            <div class="row space-between">
                <div class="col p-10 description-name">Condiviso da:</div>
                <div class="col p-10 description-value"><?php echo $owner->username; ?></div>
            </div>
            <?php if($book->isbn != NULL){ ?>
                <div class="row space-between">
                    <div class="col p-10 description-name">ISBN:</div>
                    <div class="col p-10 description-value"><?php echo $book->isbn; ?></div>
                </div>
            <?php } ?>
            <?php
                if($data != NULL){
                    foreach($dataToPrint as $k => $v){
            ?>
                        <div class="row space-between">
                            <div class="col p-10 description-name"><?php echo $v . ':'; ?></div>
                            <div class="col p-10 description-value">
                                <?php
                                    if(is_array($data->{$k}))   echo $data->{$k}[0];
                                    else                        echo $data->{$k};
                                ?>
                            </div>
                        </div>
            <?php
                    }
                }
            ?>
        </div>
    </div>
    <div class="row">
        <div>
        <?php
            $activeStars = round(intval($book->mark_sum)/max(intval($book->mark_count), 1));

            for($i = 0; $i < $activeStars; $i++){
        ?>
            <div class="star active"></div>
        <?php
            }
            for(; $i < 5; $i++){
        ?>
            <div class="star"></div>
        <?php
            }
        ?>
        </div>

        <div class="book-button background-red">
            <a href="download?id=1">
                DOWNLOAD
            </a>
        </div>
    </div>
    <?php if(isLogged()){ ?>
        <div class="row m-10">
            <h2>Review this book</h2>
        </div>
        <div class="row form-background p-20">
            <div class="col">
                <label for="title" class="d-block">
                    Titolo:
                    <input type="text" id="title" name="title" class="form-input" placeholder="Titolo">
                </label>
                <div class="rating reversed">
                    <input type="radio" class="star-radio" id="rate5" name="rate" value="5"/>
                    <label for="rate5" class="star"></label>

                    <input type="radio" class="star-radio" id="rate4" name="rate" value="4"/>
                    <label for="rate4" class="star"></label>

                    <input type="radio" class="star-radio" id="rate3" name="rate" value="3"/>
                    <label for="rate3" class="star"></label>

                    <input type="radio" class="star-radio" id="rate2" name="rate" value="2"/>
                    <label for="rate2" class="star"></label>

                    <input type="radio" class="star-radio" id="rate1" name="rate" value="1"/>
                    <label for="rate1" class="star"></label>
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
        <script src="js/book.js"></script>
    <?php } ?>
<?php require_once __DIR__ . "/template/footer.php" ?>