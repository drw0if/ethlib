<?php
    session_start();
    require __DIR__ . "/lib/Models.php";

    $result = [];

    if(isset($_GET['query']) && !empty($_GET['query'])){

    }
    else{
        $result = Book::lastTenPublic();
    }
?>

<?php require_once "template/header.php"; ?>

<div class="row">
    <h2>Libri Pubblici</h2>
</div>

<?php
    if(count($result) == 0){
?>
        <div class="row">
            <h2>Nessun risultato</h2>
        </div>
<?php
    }else{
        foreach($result as $v){
?>
            <div class="book-row">
                <div class="book-text">
                    <p>
                        <?php echo $v->name; ?>
                    </p>
                </div>
                <div class="book-text">
                    <p>
                        <?php echo ($v->isbn != NULL) ? $v->isbn : '' ; ?>
                    </p>
                </div>
                <div class="book-button background-red">
                    <a href="">></a>
                </div>
            </div>
<?php
        }
    }
?>

<?php require_once "template/footer.php"; ?>