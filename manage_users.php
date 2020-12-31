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
    function manage_usersPost(){
        //if book or csrf_token is missing no post achieved
        if(!isset($_POST['user_id']) || !isNumber($_POST['user_id']) ||
            !isset($_POST['csrf_token']) || !is_string($_POST['csrf_token'])){
                return;
        }

        $user_id = trim($_POST['user_id']);
        $csrf_token = trim($_POST['csrf_token']);

        //check for csrf_token trust
        if($_SESSION['csrf_token'] !== $csrf_token){
            return;
        }

        $users = User::filter_by(['user_id' => $user_id]);
        if(count($users) === 0){
            return;
        }

        $user = User::toObject($users[0]);

        //Delete user from database
        $user->delete();
    }

    if(isPost()){
        manage_usersPost();
    }

    //Collect all non - admin users
    $users = User::filter_by(['user_type' => '0']);
    //Create random csrf_token
    $_SESSION['csrf_token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
?>
<?php require_once __DIR__ . '/template/header.php'; ?>
<div class="col">
    <h1 class="center">Lista utenti</h1>
    <?php foreach($users as $u){ ?>
        <div class="book-row">
            <div class="book-text">
                <p><?php echo escapeString($u['username']); ?></p>
                <p><?php echo $u['email']; ?></p>
            </div>
            <form class="book-button background-red m-10" method="POST" action="#">
                <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <p onclick="this.parentNode.submit();">Rimuovi</p>
            </form>
        </div>
    <?php } ?>
</div>
<?php require_once __DIR__ . '/template/footer.php'; ?>