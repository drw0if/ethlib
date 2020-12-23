<?php
    /*
        GET to get list of public books
        parameters:
        -) query    (optional) (default = '%')
        -) offset   (optional) (default = 0)
    */

    require_once '../../lib/Utils.php';
    require_once '../../lib/Models.php';

    $query = "%";
    $offset = 0;

    if(isset($_GET['query']) && is_string($_GET['query']) && !empty(trim($_GET['query']))){
        $query = trim($_GET['query']);
    }

    if(isset($_GET['offset']) && isNumber($_GET['offset'])){
        $offset = intval($_GET['offset']);
    }

    $ans = Book::search($query, $offset);
    exitWithJson($ans, 200);
?>