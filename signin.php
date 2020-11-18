<?php require_once "template/header.php"; ?>

    <div class="splitted-container">
        <div class="form-container">
            <h2 class="center">
                Sign in
            </h2>
            <form action="" method="POST">
                <input class="form-input" type="text" name="username" placeholder="Username">
                <input class="form-input" type="password" name="password" placeholder="Password">
                <input class="form-input form-button" type="submit" name="submit" value="SIGN IN">
            </form>
        </div>
        <div class="redirect-container">
            <h2 class="center">
                Benvenuto!
            </h2>
            <p class="center">
                Vuoi entrare anche tu a far parte della nostra community?
            </p>

            <a href="signup.php">
                <input class="form-input form-button" type="button" value="SIGN UP">
            </a>

        </div>
    </div>



<?php require_once "template/footer.php"; ?>