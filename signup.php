<?php

    session_start();
    require_once __DIR__ . '/lib/Utils.php';
    require_once __DIR__ . "/lib/Models.php";

    function signupPost(){
        $ans = [
            'user_id' => null,
            'error' => null,
        ];

        if(!isset($_POST['email']) || !is_string($_POST['email']) ||
            !isset($_POST['password']) || !is_string($_POST['password']) ||
            !isset($_POST['username']) || !is_string($_POST['username'])){
            $ans['error'] = 'Ci sono dei valori mancanti!';
            return $ans;
        }

        $email = trim($_POST['email']);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $ans['error'] = "L'email inserita non è valida!";
            return $ans;
        }

        $username = trim($_POST['username']);
        if(strlen($username) === 0){
            $ans['error'] = "L'username inserito non è valido!";
            return $ans;
        }

        $password = trim($_POST['password']);
        if(strlen($password) < 8 || !preg_match("/[a-z]/", $password) ||
            !preg_match("/[A-Z]/", $password) || !preg_match("/\d/", $password) ||
            !preg_match("/\W|_/", $password)){

            $ans['error'] = 'La password non rispetta i criteri minimi di sicurezza!';
            return $ans;
        }

        $newUser = new User();
        $newUser->email = $email;
        $newUser->username = $username;
        $newUser->setPassword($password);

        try{
            $newUser->save();

            $users = User::filter_by([
                'username' => $newUser->username
            ]);

            $ans['user_id'] = $users[0]['user_id'];
            return $ans;
        }
        catch(DuplicateKeyException $e){
            $ans['error'] = 'Username o email già esistente!';
            return $ans;
        }
    }

    if(isLogged()){
        header('Location: index.php');
        die();
    }

    $ans = null;

    if(isPost()){
        $ans = signupPost();
        if($ans['error'] === null){
            $_SESSION['user_id'] = $ans['user_id'];
            header('Location: index.php');
        }
    }

?>

<?php require_once __DIR__ . '/template/header.php'; ?>

    <div class="splitted-container">
        <div class="left-container form-background">
            <header>
                <h2 class="center">
                    Sign up
                </h2>
            </header>
            <form id="form" method="POST">
                <input class="form-input" type="text" name="username" placeholder="Username" required>
                <input class="form-input" type="email" name="email" placeholder="Email" required>
                <input class="form-input" type="password" name="password" placeholder="Password" required>
                <input class="form-input" type="password" name="passwordConfirm" placeholder="Conferma password" required>
                <div class="error-banner center">
                    <?php if($ans != null) echo $ans['error']; ?>
                </div>
                <input id="submitButton" class="form-input form-button background-red" type="submit" name="submit" value="SIGN UP">
            </form>
        </div>
        <div class="right-container centered-container">
            <h2 class="center">
                Bentornato!
            </h2>
            <p class="center">
                Fai già parte della nostra community?
            </p>
            <a href="signin.php" class="form-input form-button background-red">
                SIGN IN
            </a>
        </div>
    </div>

    <script src="js/signup.js"></script>

<?php require_once __DIR__ . '/template/footer.php'; ?>