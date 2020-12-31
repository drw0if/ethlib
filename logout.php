<?php

    session_start();
    require __DIR__ . '/lib/Utils.php';

    //If the user is not logged raise 404
    if(!isLogged()){
        http_response_code(404);
        exit();
    }

    //Delete session data and PHPSESSID cookie
    $_SESSION = array();
    session_destroy();

    header('Location: index.php');
?>