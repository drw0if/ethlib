<?php
    session_start();

    require_once __DIR__ . '/lib/Utils.php';
    require_once __DIR__ . '/lib/Models.php';

    //Check if book_id has been submitted
    if(!isset($_GET['book_id']) || !isNumber($_GET['book_id'])){
        http_response_code(404);
        exit();
    }

    $book_id = trim($_GET['book_id']);

    $books = Book::filter_by([
        'book_id' => $book_id
    ]);

    //If no book found raise 404
    if(count($books) == 0){
        http_response_code(404);
        exit();
    }

    $book = Book::toObject($books[0]);

    //If the book is private and the user is not the owner raise 404
    if($book->private && (!isLogged() || $book->user_id != $_SESSION['user_id'])){
        http_response_code(404);
        exit();
    }

    serveFile($book);
?>