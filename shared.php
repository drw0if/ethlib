<?php
    require_once __DIR__ . '/lib/Utils.php';
    require_once __DIR__ . '/lib/Models.php';

    //Check if filename has been specified
    if(!isset($_GET['file']) || !is_string($_GET['file'])){
        http_response_code(404);
        exit();
    }

    $local_name = trim($_GET['file']);

    $books = Book::filter_by([
        'local_name' => $local_name
    ]);

    //Check if file exists in the database
    if(count($books) == 0){
        http_response_code(404);
        exit();
    }

    $book = Book::toObject($books[0]);

    serveFile($book);
?>