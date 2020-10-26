<?php

    function exitIfRequested($callingFile){
        if (strcasecmp(str_replace('\\', '/', $callingFile), $_SERVER['SCRIPT_FILENAME']) == 0) {
            http_response_code(404);
            exit();
        }
    }

    /* Ritorna gli attributi pubblici di un oggetto */
    function getProperties($obj){
        return get_object_vars($obj);
    }

    function throwDatabaseError(){
        http_response_code(500);
        die('Database Error, please contact the administrator');
    }


?>