<?php
    session_start();
    require_once __DIR__ . '/lib/Utils.php';

    if(!isLogged()){
        header('Location: signin.php');
        exit();
    }
?>

<?php require_once __DIR__ . '/template/header.php'; ?>

<div class="col" id="bookList"></div>

<div id="toast">
    <p></p>
</div>

<script src="js/modal.js"></script>
<script src="js/bookcase.js"></script>
<?php require_once __DIR__ . '/template/footer.php'; ?>