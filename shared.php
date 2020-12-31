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

    //Open the file and suppress warnings
    $fp = @fopen(STORAGE . $local_name, 'r');

    //Check if fopen worked
    if($fp === FALSE){
        http_response_code(404);
        exit();
    }

    //Set content type to serve the file
    header('Content-Type: ' . $books[0]['file_type']);

    //Send the file content
    fpassthru($fp);
    fclose($fp);
?>