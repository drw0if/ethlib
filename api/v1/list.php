<?php
    /*
        GET to get list of public books
        parameters:
        -) query    (optional) (default = '%')
        -) offset   (optional) (default = 0)
    */

    require_once '../../lib/Utils.php';
    require_once '../../lib/Models.php';

    //Default query parameters
    $query = '%';
    $offset = 0;

    //Check for query
    if(isset($_GET['query']) && is_string($_GET['query']) && !empty(trim($_GET['query']))){
        $query = trim($_GET['query']);
    }

    //Check for offset
    if(isset($_GET['offset']) && isNumber($_GET['offset'])){
        $offset = intval($_GET['offset']);
    }

    $ans = Book::search($query, $offset);
    exitWithJson($ans, 200);
?>