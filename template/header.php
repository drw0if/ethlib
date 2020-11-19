<?php
    require_once __DIR__ . "/../lib/Utils.php";
    exitIfRequested(__FILE__);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="template/style.css">

    <title>Ethib</title>
</head>
<body>
    <header class="page-header">
        <div class="logo">
            ETHIB
        </div>
        <form method="GET" action="search.php" class="search-box">
            <input type="text" class="search-input" placeholder="George Orwell" name="query">
            <input type="submit" class="search-button" value="&#128269;">
        </form>
        <nav class="navbar">
            <ul>
                <?php if(isLogged()){ ?>
                    <li class="nav__item"><a href="index.php">Home</a></li>
                    <li class="nav__item"><a href="upload.php">Upload</a></li>
                    <li class="nav__item"><a href="profile.php">Profilo</a></li>

                    <?php if(isAdmin()){?>
                        <li class="nav__item"><a href="admin.php">Admin</a></li>
                    <?php }?>

                <?php } else { ?>
                    <li class="nav__item"><a href="signin.php">Sign in</a></li>
                    <li class="nav__item"><a href="signup.php">Sign up</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>

    <main class="page-content">
