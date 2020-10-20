<?php

    /* Evito che la pagina possa essere richiesta direttamente */
    if (strcasecmp(str_replace('\\', '/', __FILE__), $_SERVER['SCRIPT_FILENAME']) == 0) {
        http_response_code(404);
        exit();
    }

    require_once "DB.php";

    class DBModel{
        
    }

?>