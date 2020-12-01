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
?>

<?php require_once __DIR__ . "/template/header.php" ?>

    <div class="row">
        <div class="col">
            <div class="book-title-box">
                <?php echo $book->name; ?>
            </div>
        </div>
        <div class="col">
            <div class="row">
                <div class="col">Nome:</div>
                <div class="col"><?php echo $book->name; ?></div>
            </div>
            <div class="row">
                <div class="col">Condiviso da:</div>
                <div class="col"><?php echo $owner->username; ?></div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . "/template/footer.php" ?>