<?php
    session_start();
    require_once __DIR__ . '/lib/Utils.php';
    require_once __DIR__ . '/lib/Models.php';

    if(!isLogged()){
        header('Location: signin.php');
    }

    $ans = User::filter_by([
        'user_id' => $_SESSION['user_id']
    ]);

    $user = User::toObject($ans[0]);

    function accountPost($user){
        $ans = [
            'error' => null
        ];

        if(!isset($_POST['oldPassword']) || !is_string($_POST['oldPassword']) || empty(trim($_POST['oldPassword']))){
            $ans['error'] = 'Password corrente mancante';
            return $ans;
        }

        $oldPassword = trim($_POST['oldPassword']);
        if(!password_verify($oldPassword, $user->hash)){
            $ans['error'] = 'Password corrente errata';
            return $ans;
        }

        if(isset($_POST['email']) && is_string($_POST['email'])){
            //L'utente vuole modificare la mail
            $email = trim($_POST['email']);

            //Controllo se sia formulata per bene
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $ans['error'] = "L'email inserita non è valida!";
                return $ans;
            }
            $user->email = $email;
        }

        if(isset($_POST['newPassword']) && is_string($_POST['newPassword']) && !empty(trim($_POST['newPassword'])) &&
            isset($_POST['passwordConfirm']) && is_string($_POST['passwordConfirm'])){
            //L'utente vuole modificare la password
            $newPassword = trim($_POST['newPassword']);
            $passwordConfirm = trim($_POST['passwordConfirm']);

            if(!checkPassword($newPassword)){
                $ans['error'] = 'La password non rispetta i criteri minimi di sicurezza!';
                return $ans;
            }

            if($newPassword != $passwordConfirm){
                $ans['error'] = 'Le due password non coincidono';
                return $ans;
            }

            $user->setPassword($newPassword);
        }
        $user->save();
        return $ans;
    }

    if(isPost()){
        $ans = accountPost($user);
        if($ans['error'] === null){
            $success = 'Modifiche apportate con successo';
        }
    }
?>
<?php require_once __DIR__ . '/template/header.php'; ?>

    <div class="form-background">
        <div class="row">
            <h2>
                Modifica i dati del tuo profilo
            </h2>
        </div>
        <form action="#" method="POST">
            <input class="form-input" type="text" name="username" id="username" value="<?php echo $user->username; ?>" disabled>
            <input class="form-input" type="email" name="email" id="email" placeholder="Email" value="<?php echo $user->email; ?>">
            <input class="form-input" type="password" name="oldPassword" id="oldPassword" placeholder="Password attuale">
            <input class="form-input" type="password" name="newPassword" id="newPassword" placeholder="Nuova password">
            <input class="form-input" type="password" name="passwordConfirm" id="passwordConfirm" placeholder="Conferma password">
            <div class="error-banner center"><?php if(isset($ans['error'])) echo $ans['error']; ?></div>
            <div class="success-banner center"><?php if(isset($success)) echo $success; ?></div>
            <input class="form-input form-button background-red" type="submit" value="AGGIORNA">
        </form>
    </div>

<?php require_once __DIR__ . '/template/footer.php'; ?>