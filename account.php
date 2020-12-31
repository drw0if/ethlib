<?php
    session_start();
    require_once __DIR__ . '/lib/Utils.php';
    require_once __DIR__ . '/lib/Models.php';

    //Only logged users
    if(!isLogged()){
        header('Location: signin.php');
        exit();
    }

    $ans = User::filter_by([
        'user_id' => $_SESSION['user_id']
    ]);

    //Check if the account is sill in the database
    if(count($ans) == 0){
        header('Location: logout.php');
        exit();
    }

    $user = User::toObject($ans[0]);

    //POST requests handler
    function accountPost($user){
        $ans = [
            'error' => null
        ];

        //Check for old password presence
        if(!isset($_POST['oldPassword']) || !is_string($_POST['oldPassword']) || empty(trim($_POST['oldPassword']))){
            $ans['error'] = 'Password corrente mancante';
            return $ans;
        }

        //Check for old password match
        $oldPassword = trim($_POST['oldPassword']);
        if(!password_verify($oldPassword, $user->hash)){
            $ans['error'] = 'Password corrente errata';
            return $ans;
        }

        //Check for email in submit form
        if(isset($_POST['email']) && is_string($_POST['email'])){
            //User wants to update the email
            $email = trim($_POST['email']);

            //Check if it is a correct email
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $ans['error'] = "L'email inserita non è valida!";
                return $ans;
            }
            //Update email
            $user->email = $email;
        }

        //Check for new password in submit form
        if(isset($_POST['newPassword']) && is_string($_POST['newPassword']) && !empty(trim($_POST['newPassword'])) &&
            isset($_POST['passwordConfirm']) && is_string($_POST['passwordConfirm'])){
            //User wants to update the password
            $newPassword = trim($_POST['newPassword']);
            $passwordConfirm = trim($_POST['passwordConfirm']);

            //Check if the new password is correct against password policy
            if(!checkPassword($newPassword)){
                $ans['error'] = 'La password non rispetta i criteri minimi di sicurezza!';
                return $ans;
            }

            //Check if user submited same password as confirm
            if($newPassword != $passwordConfirm){
                $ans['error'] = 'Le due password non coincidono';
                return $ans;
            }

            //Update password
            $user->setPassword($newPassword);
        }

        //Save updated data
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
            <input class="form-input" type="text" name="username" id="username" value="<?php echo escapeString($user->username); ?>" disabled>
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