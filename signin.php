<?php

    session_start();
    require_once __DIR__ . "/lib/Utils.php";

    function signinPost(){
        $ans = [
            "user_id" => null,
            "error" => null
        ];

        if(!isset($_POST["username"]) || !isset($_POST["password"])){
            $ans["error"] = "Ci sono dei valori mancanti!";
            return $ans;
        }

        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        require_once __DIR__ . "/lib/Models.php";

        try{
            $user = User::login($username, $password);
            if($user === null){
                $ans["error"] = "Nome utente o password sbagliati";
                return $ans;
            }

            $ans["user_id"] = $user->user_id;
            return $ans;
        }
        catch(Exception $e){
            throwDatabaseError();
        }
    }


    if(isLogged()){
        header("Location: index.php");
        die();
    }

    $ans = null;

    if(isPost()){
        $ans = signinPost();
        if($ans["error"] === null){
            $_SESSION["user_id"] = $ans["user_id"];
            header("Location: index.php");
        }
    }
?>

<?php require_once __DIR__ . "/template/header.php"; ?>

    <div class="splitted-container">
        <div class="form-container form-background">
            <header>
                <h2 class="center">
                    Sign in
                </h2>
            </header>
            <form method="POST">
                <input class="form-input" type="text" name="username" placeholder="Username">
                <input class="form-input" type="password" name="password" placeholder="Password">
                <div class="error-banner center">
                    <?php if($ans != null) echo $ans["error"]; ?>
                </div>
                <input class="form-input form-button" type="submit" name="submit" value="SIGN IN">
            </form>
        </div>
        <div class="redirect-container centered-container">
            <header>
                <h2 class="center">
                    Benvenuto!
                </h2>
            </header>
            <p class="center">
                Entra anche tu a far parte della nostra community?
            </p>

            <a href="signup.php" class="form-input form-button">
                SIGN UP
            </a>

        </div>
    </div>

<?php require_once __DIR__ . "/template/footer.php"; ?>