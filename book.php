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
        'isbn_13' => 'ISBN',
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
        <div>
        </div>
        <div>
        </div>
    </div>

<?php require_once __DIR__ . "/template/footer.php" ?>