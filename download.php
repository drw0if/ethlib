<?php

    session_start();

    require_once __DIR__ . '/lib/Utils.php';
    require_once __DIR__ . '/lib/Models.php';

    if(!isset($_GET['book_id']) || !isNumber($_GET['book_id'])){
        http_response_code(404);
        exit();
    }

    $book_id = trim($_GET['book_id']);

    $books = Book::filter_by([
        'book_id' => $book_id
    ]);

    if(count($books) == 0){
        http_response_code(404);
        exit();
    }

    $book = Book::toObject($books[0]);

    if($book->private && (!isLogged() || $book->user_id != $_SESSION['user_id'])){
        http_response_code(404);
        exit();
    }

    $fp = @fopen(STORAGE . $book->local_name, 'r');

    if($fp === FALSE){
        http_response_code(404);
        exit();
    }

    header('Content-Type: ' . $contentTypes[$book->file_type]);

    fpassthru($fp);
    fclose($fp);
?>