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

    //GET requests handler
    function bookGet($book){

        //Recover owner data
        $ans = User::filter_by([
            'user_id' => $book->user_id
        ]);
        $owner = User::toObject($ans[0]);

        //if book is private and user is not admin
        if($book->private && !isAdmin()){
            //if user is not logged or is not the book owner
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

    //DELETE requests handler
    function bookDelete($book){
        //You must be logged to delete books
        if(!isLogged()){
            http_response_code(404);
            exit();
        }

        //Recover owner data
        $ans = User::filter_by([
            'user_id' => $book->user_id
        ]);

        //Check if book is owned by the current user
        if($ans[0]['user_id'] != $_SESSION['user_id']){
            http_response_code(404);
            exit();
        }

        //Delete the book file
        @unlink(STORAGE . $book->local_name);
        //Delete the book from the database
        $book->delete();

        http_response_code(200);
        exit();
    }

    //If no book requested exit
    if(!isset($_GET['book_id']) || !isNumber($_GET['book_id'])){
        http_response_code(404);
        exit();
    }

    $book_id = $_GET['book_id'];

    $ans = Book::filter_by([
        'book_id' => $book_id
    ]);

    //If no book found exit
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

    //Other type of request raise 404
    http_response_code(404);
?>