<?php

    function registerPost(){
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
            !preg_match("/[\W_]/", $password)){

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

    if($_SERVER["REQUEST_METHOD"] === 'POST'){
        $error = registerPost();
        if($error === null)
            header("Location: index.php");
    }

?>

<?php require_once "template/header.php"; ?>

<form action="" method="POST">
    <input type="email" name="email">
    <input type="text" name="username">
    <input type="text" name="password">
    <input type="submit" name="submit">
</form>


<?php require_once "template/footer.php"; ?>