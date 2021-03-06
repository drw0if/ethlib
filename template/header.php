<?php
    require_once __DIR__ . '/../lib/Utils.php';
    exitIfRequested(__FILE__);
?>
<!DOCTYPE html>
<html lang="it">
    <!-- Il validatore HTML da warning in questo punto dicendo che non è specificato l'attributo "lang" nel tag html,
        tuttavia come si può notare esso è presente. Credo che l'estrattore non funzioni a dovere. -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="template/style.css">

    <script src="js/common.js"></script>

    <title>EthLib</title>
</head>
<body>
    <header class="page-header">
        <div id="logo">
            <a href=".">
                ETHLIB
            </a>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="text" class="search-input" placeholder="es: 1984" name="query">
            <input type="submit" class="search-button" value="&#128269;">
        </form>
        <nav id="navbar">
            <ul>
                <?php if(isLogged()){ ?>
                    <li class="nav-item"><a href="index.php">Home</a></li>
                    <li class="nav-item"><a href="upload.php">Upload</a></li>
                    <li class="nav-item"><a href="bookcase.php">Libreria</a></li>
                    <li class="nav-item"><a href="account.php">Account</a></li>

                    <?php if(isAdmin()){?>
                        <li class="nav-item"><a href="manage_users.php">Gestione utenti</a></li>
                        <li class="nav-item"><a href="manage_books.php">Gestione Libri</a></li>
                    <?php }?>

                    <li class="nav-item"><a href="logout.php">Logout</a></li>

                <?php } else { ?>
                    <li class="nav-item"><a href="signin.php">Sign in</a></li>
                    <li class="nav-item"><a href="signup.php">Sign up</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>

    <main class="page-content">
