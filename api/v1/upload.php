<?php
    /*
        POST to upload a new book
        Login needed
        paramters:
        -) file     (required)
        -) name     (required)
    */

    session_start();
    require_once __DIR__ . '/../../lib/Utils.php';

    //Only POST request allowed
    if(!isPost()){
        http_response_code(404);
        exit();
    }

    //Only logged user
    if(!isLogged()){
        http_response_code(404);
        exit();
    }

    //Check for correct request
    if(!isset($_FILES['file']) || empty($_FILES['file']) ||
        !isset($_POST['name']) || !is_string($_POST['name']) ||
        empty(trim($_POST['name']))){
        http_response_code(400);
        exit();
    }

    //Check for file existance
    if($_FILES['file']['size'] <= 0 ||
        $_FILES['file']['size'] >= MAX_FILE_SIZE ||
        $_FILES['file']['error'] !== 0){

        http_response_code(400);
        exit();
    }

    //Check Content-Type header
    if(!isset($allowedMimeTypes[$_FILES['file']['type']])){
        exitWithJson(['error' => 'File not allowed, only pdf and epub accepted'], 400);
    }

    //Get file mime type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
    finfo_close($finfo);

    //Check file mime type
    if(!isset($allowedMimeTypes[$mime])){
        exitWithJson(['error' => 'File not allowed, only pdf and epub accepted'], 400);
    }

    $extension = $allowedMimeTypes[$mime];
    $realName = $_FILES['file']['name'];
    $localName = md5($realName . date('Y-m-d H:i:s')) . $extension;
    $uploadfile = STORAGE . $localName;

    require_once __DIR__ . '/../../lib/Models.php';

    $b = new Book();

    //Check for isbn correctness
    if(isset($_POST['isbn']) && is_string($_POST['isbn'])){
        $isbn = trim($_POST['isbn']);

        if(!preg_match("/^(\d{10}|\d{13})$/", $isbn)){
            exitWithJson(['error' => 'Bad isbn'], 400);
        }

        $b->isbn = $isbn;
    }

    //Set new values
    $b->local_name = $localName;
    $b->file_type = $extension;
    $b->name = trim($_POST['name']);

    if(isset($_POST['private']) && $_POST['private'] === 'true'){
        $b->private = true;
    }else{
        $b->private = false;
    }

    $b->user_id = $_SESSION['user_id'];

    //Moving file from tmp directory to upload folder and update database
    if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)){
        $b->save();
        exitWithJson(['error' => NULL], 201);
    }

    exitWithJson(['error' => 'Server error moving the file'], 500);
?>