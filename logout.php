<?php

    session_start();
    require __DIR__ . '/lib/Utils.php';

    if(!isLogged()){
        http_response_code(404);
        exit();
    }

    $_SESSION = array();
    session_destroy();

    header('Location: index.php');

?>