<?php
    session_start();
    require_once __DIR__ . '/lib/Utils.php';

    //Admin only
    if(!isAdmin()){
        http_response_code(404);
        exit();
    }

    require_once __DIR__ . '/lib/Models.php';

    //POST requests handler
    function manage_booksPost(){
        //if book or csrf_token is missing no post achieved
        if(!isset($_POST['book_id']) || !isNumber($_POST['book_id']) ||
            !isset($_POST['csrf_token']) || !is_string($_POST['csrf_token'])){
                return;
        }

        $book_id = trim($_POST['book_id']);
        $csrf_token = trim($_POST['csrf_token']);

        //Check for csrf_token trust
        if($_SESSION['csrf_token'] !== $csrf_token){
            return;
        }

        $books = Book::filter_by(['book_id' => $book_id]);
        if(count($books) === 0){
            return;
        }

        $book = Book::toObject($books[0]);

        //Delete file and suppress warnings
        @unlink(STORAGE . $book->local_name);
        //Delete file from database
        $book->delete();
    }

    if(isPost()){
        manage_booksPost();
    }

    //Collect all books
    $books = Book::filter_by();
    //Create random csrf_token
    $_SESSION['csrf_token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
?>
<?php require_once __DIR__ . '/template/header.php'; ?>
<div class="col">
    <h1 class="center">Libri</h1>
    <?php foreach($books as $b){ ?>
        <div class="book-row">
            <div class="book-text">
                <p><?php echo escapeString($b['name']); ?></p>
                <p><?php echo $b['isbn']; ?></p>
            </div>
            <form class="book-button background-red m-10" method="POST" action="#">
                <input type="hidden" name="book_id" value="<?php echo $b['book_id']; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <p onclick="this.parentNode.submit();">Rimuovi</p>
            </form>
            <div class="book-button background-red">
                <a href="book.php?book_id=<?php echo $b['book_id']; ?>">></a>
            </div>
        </div>
    <?php } ?>
</div>
<?php require_once __DIR__ . '/template/footer.php'; ?>