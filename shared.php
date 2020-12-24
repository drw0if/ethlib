<?php

    require_once __DIR__ . '/lib/Utils.php';
    require_once __DIR__ . '/lib/Models.php';
    if(!isset($_GET['file']) || !is_string($_GET['file'])){
        http_response_code(404);
        exit();
    }

    $local_name = trim($_GET['file']);

    $books = Book::filter_by([
        'local_name' => $local_name
    ]);

    if(count($books) == 0){
        http_response_code(404);
        exit();
    }

    $fp = @fopen(STORAGE . $local_name, 'r');

    if($fp === FALSE){
        http_response_code(404);
        exit();
    }

    header('Content-Type: ' . $books[0]['file_type']);

    fpassthru($fp);
    fclose($fp);
?>