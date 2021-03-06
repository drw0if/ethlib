<?php

    /*
     * This file is used to store common methods and constant value across
     * all the application
     */

    define("MAX_FILE_SIZE", 50*1024*1024); // 50MB

    $allowedMimeTypes = [
        'application/pdf' => '.pdf',           //pdf
        'application/epub+zip' => '.epub',     //epub
        'application/epub' => '.epub',         //epub
    ];

    $contentTypes = array_flip($allowedMimeTypes);

    //This should be created in an offline folder
    define('STORAGE', __DIR__ . '/../upload/');

    //Exit if the page is requested directly instead of import
    function exitIfRequested($callingFile){
        if (strcasecmp(str_replace('\\', '/', $callingFile), $_SERVER['SCRIPT_FILENAME']) == 0) {
            http_response_code(404);
            exit();
        }
    }
    exitIfRequested(__FILE__);

    function throwDatabaseError(){
        http_response_code(500);
        exit('Database Error, please contact the administrator');
    }

    //Check if user is logged, REMEMBER TO START THE SESSION
    function isLogged(){
        return (isset($_SESSION['user_id']) && ($_SESSION['user_id'] !== null));
    }

    //Check if user is admin, REMEMBER TO START THE SESSION
    function isAdmin(){
        return (isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 1));
    }

    function checkPassword($password){
        if(strlen($password) < 8 || !preg_match("/[a-z]/", $password) ||
            !preg_match("/[A-Z]/", $password) || !preg_match("/\d/", $password) ||
            !preg_match("/\W|_/", $password)){
            return false;
        }
        return true;
    }

    function isPost(){
        return strcmp($_SERVER['REQUEST_METHOD'], 'POST') === 0;
    }

    function isGet(){
        return strcmp($_SERVER['REQUEST_METHOD'], 'GET') === 0;
    }

    function isDelete(){
        return strcmp($_SERVER['REQUEST_METHOD'], 'DELETE') === 0;
    }

    //Use this function to print user provided content to avoid XSS
    function escapeString($string){
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    //returns a json and set a status code - default is 200
    function exitWithJson($obj, $code = 200){
        http_response_code($code);
        echo json_encode($obj);
        exit();
    }

    //Check if a string is an integer number
    function isNumber($str){
        return is_string($str) && preg_match("/^-?\d{1,}$/", $str);
    }

    //Common procedure to serve file via HTTP
    function serveFile($book){
        //Open the file and suppress warnings
        $fp = @fopen(STORAGE . $book->local_name, 'r');

        //Check if fopen worked
        if($fp === FALSE){
            http_response_code(404);
            exit();
        }

        //Set content type to serve the file
        header('Content-Type: ' . $GLOBALS['contentTypes'][$book->file_type]);
        header('Content-Disposition: attachment; filename="' . $book->name . $book->file_type . '"');

        //Send the file content
        fpassthru($fp);
        fclose($fp);
    }

?>