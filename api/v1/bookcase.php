<?php
    /*
        GET to get list of logged user's books
        parameters:
            None
    */

    session_start();

    require_once __DIR__ . '/../../lib/Utils.php';
    require_once __DIR__ . '/../../lib/Models.php';

    //If you are not logged raise 404
    if(!isLogged()){
        http_response_code(404);
        exit();
    }

    //Query books
    $books = Book::filter_by([
        'user_id' => $_SESSION['user_id']
    ]);

    //Build array with public data only
    $ans = [];
    foreach(array_reverse($books) as $b){
        $tmp = [
            'book_id' => $b['book_id'],
            'isbn' => $b['isbn'],
            'local_name' => $b['local_name'],
            'name' => $b['name'],
            'private' => $b['private']
        ];
        $ans[] = $tmp;
    }

    exitWithJson($ans, 200);
?>