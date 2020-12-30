<?php
    session_start();
    require_once __DIR__ . '/../../lib/Utils.php';
    require_once __DIR__ . '/../../lib/Models.php';

    if(!isLogged()){
        header('Location: signin.php');
        exit();
    }

    $books = Book::filter_by([
        'user_id' => $_SESSION['user_id']
    ]);

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