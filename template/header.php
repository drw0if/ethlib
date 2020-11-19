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
                <li class="nav__item"><a href="">Home</a></li>
                <li class="nav__item"><a href=""></a></li>
                <li class="nav__item"><a href="">Admin</a></li>
            </ul>
        </nav>
    </header>

    <main class="page-content">
