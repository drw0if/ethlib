<?php

    define("MAX_FILE_SIZE", 50*1024*1024); // 50MB
    $allowedMimeTypes = [
        'application/pdf' => '.pdf',           //pdf
        'application/epub+zip' => '.epub'      //epub
    ];

    $contentTypes = array_flip($allowedMimeTypes);

    define('STORAGE', __DIR__ . '\..\upload\\');


    function exitIfRequested($callingFile){
        if (strcasecmp(str_replace('\\', '/', $callingFile), $_SERVER['SCRIPT_FILENAME']) == 0) {
            http_response_code(404);
            exit();
        }
    }
    exitIfRequested(__FILE__);

    function throwDatabaseError(){
        http_response_code(500);
        die('Database Error, please contact the administrator');
    }

    function isLogged(){
        return isset($_SESSION['user_id']) && ($_SESSION['user_id'] !== null);
    }

    /* TODO: implement real check */
    function isAdmin(){
        return false;
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

    function escapeString($string){
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    function exitWithJson($obj, $code = 200){
        http_response_code($code);
        echo json_encode($obj);
        exit();
    }

    function isNumber($str){
        return is_string($str) && preg_match("/^-?\d{1,}$/", $str);
    }

?>