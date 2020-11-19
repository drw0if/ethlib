<?php require_once "template/header.php"; ?>

    <div class="splitted-container">
        <div class="form-container">
            <header>
                <h2 class="center">
                    Sign in
                </h2>
            </header>
            <form method="POST">
                <input class="form-input" type="text" name="username" placeholder="Username">
                <input class="form-input" type="password" name="password" placeholder="Password">
                <div class="error-banner center"></div>
                <input class="form-input form-button" type="submit" name="submit" value="SIGN IN">
            </form>
        </div>
        <div class="redirect-container">
            <header>
                <h2 class="center">
                    Benvenuto!
                </h2>
            </header>
            <p class="center">
                Vuoi entrare anche tu a far parte della nostra community?
            </p>

            <a href="signup.php" class="form-input form-button">
                SIGN UP
            </a>

        </div>
    </div>

<?php require_once "template/footer.php"; ?>