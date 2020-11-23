<?php
    session_start();
    require_once __DIR__ . "/lib/Utils.php";

    if(!isLogged()){
        header("Location: signin.php");
    }
?>

<?php require_once __DIR__ . "/template/header.php"; ?>

    <div class="row">
        <label for="file-input" id="file-label">
            <h2>1984</h2>
        </label>

        <input type="file" name="file" id="file-input">

        <div class="centered-container form-background padded-container">
            <label for="private" class="d-block center">
                <input type="checkbox" name="private">
                Private
            </label>

            <label for="name" class="d-block">
                Name
                <input type="text" name="name" class="form-input" placeholder="1984">
            </label>
            <label for="isbn" class="d-block">
                ISBN
                <input type="text" name="isbn" class="form-input" placeholder="isbn">
            </label>

            <input type="button" class="form-input form-button" value="UPLOAD">
        </div>
    </div>

    <div class="row">
        <div id="progress-bar">
            <p id="progress-bar-label"> 50%</p>
            <div class="center" style="width: 70%;"></div>
        </div>
    </div>

    <script>
        document.getElementById("file-input").onchange = (e) => {
            console.log(e.target.files);
        }
    </script>
<?php require_once __DIR__ . "/template/footer.php"; ?>