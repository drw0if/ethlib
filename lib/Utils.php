<?php

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
        return isset($_SESSION["user_id"]) && ($_SESSION["user_id"] !== null);
    }

    /* TODO: implement real check */
    function isAdmin(){
        return false;
    }

    function isPost(){
        return strcmp($_SERVER["REQUEST_METHOD"], 'POST') === 0;
    }

    function escapeString($string){
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

?>