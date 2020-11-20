<?php
    session_start();
    require_once __DIR__ . "/lib/Utils.php";

    if(!isLogged()){
        header("Location: signin.php");
    }
?>

<?php require_once __DIR__ . "/template/header.php"; ?>

<?php require_once __DIR__ . "/template/footer.php"; ?>