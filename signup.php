<?php

    function signupPost(){
        if(!isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["username"])){
            return "Ci sono dei valori mancanti!";
        }

        $email = trim($_POST["email"]);
        if(strlen($email) === 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            return "L'email inserita non è valida!";
        }

        $username = trim($_POST["username"]);
        if(strlen($username) === 0){
            return "L'username inserito non è valido!";
        }

        $password = trim($_POST["password"]);
        if(strlen($password) < 8 || !preg_match("/[a-z]/", $password) ||
            !preg_match("/[A-Z]/", $password) || !preg_match("/\d/", $password) ||
            !preg_match("/\W|_/", $password)){

            return "La password non rispetta i criteri minimi di sicurezza!";
        }

        require_once __DIR__ . "/lib/Models.php";

        $newUser = new User();
        $newUser->email = $email;
        $newUser->username = $username;
        $newUser->setPassword($password);

        try{
            $newUser->save();
            return null;
        }
        catch(DuplicateKeyException $e){
            return "Username o email già esistente!";
        }
    }

    session_start();
    if(isset($_SESSION["login_token"])){
        header("Location: index.php");
        die();
    }

    if($_SERVER["REQUEST_METHOD"] === 'POST'){
        $error = signupPost();
        if($error === null)
            header("Location: index.php");
    }

?>

<?php require_once "template/header.php"; ?>

    <div class="splitted-container">
        <div class="form-container">
            <h2 class="center">
                Sign up
            </h2>
            <form id="form" method="POST">
                <input class="form-input" type="text" name="username" placeholder="Username" required>
                <input class="form-input" type="email" name="email" placeholder="Email" required>
                <input class="form-input" type="password" name="password" placeholder="Password" required>
                <input class="form-input" type="password" name="passwordConfirm" placeholder="Conferma password" required>
                <div class="error-banner center"></div>
                <input id="submitButton" class="form-input form-button" type="submit" name="submit" value="SIGN UP">
            </form>
        </div>
        <div class="redirect-container">
            <h2 class="center">
                Bentornato!
            </h2>
            <p class="center">
                Fai già parte della nostra community?
            </p>
            <a href="signin.php" class="form-input form-button">
                SIGN IN
            </a>
        </div>
    </div>

    <script>
        const submitButton = document.getElementById("submitButton");
        const form = document.getElementById("form");
        const errorBanner = document.getElementsByClassName("error-banner")[0];

        const showError = function(msg) {
            errorBanner.innerText = msg;
        }

        submitButton.onclick = function(evt){
            for(let i = 0; i < 4; i++){
                let input = form.children[i];
                let check = input.validity;
                if(check.valueMissing){
                    showError(input.placeholder + " mancante");
                    return false;
                }
                if(check.typeMismatch){
                    showError(input.placeholder + " non valida");
                    return false;
                }
            }

            let password = form.children[2].value;

            if(password.match(/[a-z]/) == null){
                showError("Nella password serve almeno una lettera minuscola");
                return false;
            }
            if(password.match(/[A-Z]/) == null){
                showError("Nella password serve almeno una lettera maiuscola");
                return false;
            }
            if(password.match(/[0-9]/) == null){
                showError("Nella password serve almeno una cifra");
                return false;
            }
            if(password.match(/\W|_/) == null){
                showError("Nella password serve almeno un carattere speciale");
                return false;
            }

            if(password != form.children[3].value){
                showError("La conferma non coincide con la password");
                return false;
            }
        }
    </script>

<?php require_once "template/footer.php"; ?>