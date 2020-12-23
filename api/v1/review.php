<?php
    /*
        GET to get reviews and rating
        parameters:
        -) book_id  (required)
        -) size     (default = 5)
        -) offset   (default = 0)

        POST to upload or edit a review and a rating
        Login needed
        paramters:
        -) book_id  (required)
        -) title    (required)
        -) content  (required)
        -) rating   (required in range [1, 5])
    */

    session_start();
    require_once __DIR__ . '/../../lib/Utils.php';
    require_once __DIR__ . '/../../lib/Models.php';

    //Checks POST parameters and return them sanitized
    function checkArgs(){
        //Check if use is logged
        if(!isLogged()){
            http_response_code(404);
            exit();
        }

        //Check if parameters are available
        if(!isset($_POST['book_id']) || !is_string($_POST['book_id']) ||
            !isset($_POST['title']) || !is_string($_POST['title']) ||
            !isset($_POST['content']) || !is_string($_POST['content']) ||
            !isset($_POST['rating']) || !is_string($_POST['rating'])){
            exitWithJson(['error' => 'Bad format'], 400);
        }

        $book_id = trim($_POST['book_id']);
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $rating = trim($_POST['rating']);

        //Check parameter formats
        if(!isNumber($book_id) || !isNumber($rating) || strlen($title) == 0 || strlen($content) == 0){
            exitWithJson(['error' => 'Bad format'], 400);
        }

        //Check if book exists and is public
        $books = Book::filter_by([
            'book_id' => $book_id
        ]);
        if(count($books) == 0 || $books[0]['private']){
            exitWithJson(['error' => 'No book found'], 404);
        }

        $rating = intval($rating);

        //Check if rating is in range [0, 5]
        if($rating <  0 || $rating > 5){
            exitWithJson(['error' => 'Bad rating'], 400);
        }

        //return sanitized
        return [
            'book_id' => $book_id,
            'title' => $title,
            'content' => $content,
            'rating' => $rating,
            'book' => Book::toObject($books[0])
        ];
    }

    //Post request handler
    function reviewPost(){
        //Check and get parameters
        $params = checkArgs();

        //Get old or new Review object
        $reviews = Review::filter_by([
            'book_id' => $params['book_id'],
            'user_id' => $_SESSION['user_id']
        ]);
        if(count($reviews) > 0){
            $review = Review::toObject($reviews[0]);
            $params['book']->rating_sum -= $review->rating;
            $params['book']->rating_count--;
        }
        else         $review = new Review();

        //Add or edit data
        $review->title = $params['title'];
        $review->content = $params['content'];
        $review->user_id = $_SESSION['user_id'];
        $review->book_id = $params['book_id'];
        $review->rating = $params['rating'];

        //Update review values
        $params['book']->rating_sum += $params['rating'];
        $params['book']->rating_count++;

        //Save data
        $review->save();
        $params['book']->save();

        exitWithJson(['error' => NULL], ($review->review_id) ? 202 : 201);
    }

    function reviewGet(){
        $size = 5;
        $offset = 0;

        //Check if book_id is provded
        if(!isset($_GET['book_id']) || !is_string($_GET['book_id'])){
            exitWithJson(['error' => 'No book_id specified']);
        }

        //Check if book_id is numeric
        $book_id = trim($_GET['book_id']);
        if(!isNumber($book_id)){
            exitWithJson(['error' => 'Bad book_id provided']);
        }

        //Check if size is provided
        if(isset($_GET['size']) && isNumber($_GET['size'])){
            $size = min(intval($_GET['size']), 10);
        }

        //Check if offset is provided
        if(isset($_GET['offset']) && isNumber($_GET['offset'])){
            $offset = intval($_GET['offset']);
        }

        //Check if book exists and is public
        $books = Book::filter_by([
            'book_id' => $book_id
        ]);
        if(count($books) == 0 || $books[0]['private']){
            exitWithJson(['error' => 'No book found'], 404);
        }

        //Get Reviews
        $result = Review::filter_by([
            'book_id' => $book_id,
            'limit' => $size,
            'offset' => $offset
        ]);

        //Format data
        $ans = [];
        foreach($result as $k => $v){
            $tmp = [];
            $tmp['title'] = $v['title'];
            $tmp['content'] = $v['content'];
            $tmp['rating'] = $v['rating'];
            $tmp['username'] = User::filter_by([
                'user_id' => $v['user_id']
            ])[0]['username'];
            $ans[] = $tmp;
        }

        //Return formatted data as json
        exitWithJson($ans, 200);
    }

    //Choose right handler
    if(isGet()){
        reviewGet();
    }
    else if(isPost()){
        reviewPost();
    }

    http_response_code(404);
?>