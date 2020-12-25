<?php
    /*
        GET to get book details
        parameters:
        -) book_id  (required)

        DELETE to delete a book
        -) book_id  (required)
    */

    session_start();

    require_once __DIR__ . '/../../lib/Utils.php';
    require_once __DIR__ . '/../../lib/Models.php';

    function bookGet($book){

        //Recover owner data
        $ans = User::filter_by([
            'user_id' => $book->user_id
        ]);
        $owner = User::toObject($ans[0]);

        //if book is private but you are not logged or you are not the owner die
        if($book->private){
            if(!isLogged() || ($book->user_id != $_SESSION['user_id'])){
                http_response_code(404);
                exit();
            }
        }

        $ans = [];
        $ans['book_id'] = $book->book_id;
        $ans['isbn'] = $book->isbn;
        $ans['name'] = $book->name;
        $ans['rating'] = round(intval($book->rating_sum)/max(intval($book->rating_count), 1));
        $ans['ownerName'] = $owner->username;

        exitWithJson($ans, 200);
    }

    function bookDelete($book){
        if(!isLogged()){
            http_response_code(404);
            exit();
        }

        //Recover owner data
        $ans = User::filter_by([
            'user_id' => $book->user_id
        ]);

        if($ans[0]['user_id'] != $_SESSION['user_id']){
            http_response_code(404);
            exit();
        }

        @unlink(STORAGE . $book->local_name);
        $book->delete();

        http_response_code(200);
        exit();
    }

    //If no book requested die
    if(!isset($_GET['book_id']) || !isNumber($_GET['book_id'])){
        http_response_code(404);
        exit();
    }

    $book_id = $_GET['book_id'];

    $ans = Book::filter_by([
        'book_id' => $book_id
    ]);

    //If no book found die
    if(count($ans) == 0){
        http_response_code(404);
        exit();
    }

    $book = Book::toObject($ans[0]);

    if(isGet()){
        bookGet($book);
    }
    else if(isDelete()){
        bookDelete($book);
    }

    http_response_code(404);
?>