<?php
if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                        }
?>
<!DOCTYPE html>
<html lang="cs" data-bs-theme="dark">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Tarkov Wiki</title>
    <link rel="icon" href="img/Fake_Mustache_NoBackground.png" type="image/x-icon">
    <link rel="stylesheet" href="css/main.css?v=<?= time() ?>"/>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="fonts/6.0/css/all.min.css"/>

    <script src="js/cookieManager.js"></script>
</head>
<body>

<header>
    <nav class="header-top">
        <div id="brand">

            <img src="img/Fake_Mustache_NoBackground.png" alt="Mustage" class="logo">


        </div>
        <div id="menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#quests">Úkoly</a></li>
                <?php
                
                global $conn;
                require_once('dbsConnect.php');


                $logedIn=false;
                if(isset($_SESSION["logged"])){
                    if($_SESSION["logged"]){
                        $logedIn=true;

                        $name = $_SESSION['loggedName'];
                        $stm = $conn -> prepare('SELECT isMod((SELECT getIDByName(:name))) as "nm"' );
                        $stm -> bindParam(':name', $name);
                        $stm -> execute();
                        $row = $stm -> fetch();
                        $isMod = $row['nm'];
                    }

                }
                if($logedIn){

                    if($isMod==1){
                        echo '<li><a href="changeApproval.php">Potvzení změn</a></li>';
                        echo '<li><a href="questSubmit.php">Nahrát Úkol</a></li>';
                    }


                    echo '<li><a href="logout.php"><div class="underline-text">'.$_SESSION['loggedName'].'</div>Odhlásit se</a></li>';


                }
                else{
                    echo'<li><a href="login.php" data-login>Přihlásit se</a></li>';
                    echo '<li><a href="register.php" data-login>Zaregistrovat se</a></li>';
                }


                ?>

            </ul>
        </div>
    </nav>
</header>


<div class="clearfix"><br></div>
<div class="main">


    <div class="clearfix"><br></div>

    <div class="content">
        <h2>Nahrát Úkol</h2>

        <div class='odstavec'>
            <form action='questUpload.php' method="post" enctype="multipart/form-data">

                <!--
                        Quest obsahuje:

                        quest name
                        quest xp
                        quest reward - plain text
                        obrázek

                  -->

                <label for="name"> Název úkolu: </label> <br>
                <input type="text" name="name" id="name"> <br>


                <label for="description"> Popis úkolu: </label> <br>
                <textarea type="text" name="description" id="description"> </textarea> <br>


                <label for="xp"> Zkušenostní body za úkol: </label> <br>
                <input type="number" min="1" max="100000" name="xp" id="xp"> <br>


                <label for="reward"> Peníze za úkol: </label> <br>
                <input type="number" min="1" max="2000000" name="reward" id="reward"> <br>




                <br><br>
                <label class='custom-file-upload'>
                    Vybrat soubor
                    <input type='file' accept='image/*' name='image' onchange='previewImage(event)'>
                </label>
                <img id='image-preview' alt='Image preview' style='display: none; max-width: 300px; margin-top: 15px;'>
                <br>
                <label for="traders">Vyberte Tradera:</label>
                <select name="traders" id="traders">
                    <option value="2">Prapor</option>
                    <option value="3">The-Rapist</option>
                    <option value="4">Fence</option>
                    <option value="5">Skier</option>
                    <option value="6">Peacekeeper</option>
                    <option value="7">Mechanic</option>
                    <option value="8">Ragman</option>
                    <option value="9">Jaeger</option>
                    <option value="10">Lightkeeper</option>
                    <option value="11">Referee</option>
                </select>
                <br>
                <label for="maps">Vyberte Mapu:</label>
                <select name="maps" id="maps">
                    <option value="2">Factory</option>
                    <option value="3">Customs</option>
                    <option value="4">Woods</option>
                    <option value="5">Shoreline</option>
                    <option value="6">Interchange</option>
                    <option value="7">Reserve</option>
                    <option value="8" class="L">Lighthouse</option>
                    <option value="9">Streets of Tarkov</option>
                    <option value="10">Labs</option>
                    <option value="11">Ground zero</option>
                </select>
                <br> <br>
                <label class="submit-btn" for="submit">
                Nahrát
                <input type='submit' value='Nahrát' id='submit' class="btn-hide">
                </label>
            </form>
        </div>


    </div>
</div>
<footer>
    FIM UHK Seminární práce pro předměty TNPW2 a DBS2, Autoři: Jáchym Málek, Daniel Mrva, Datum poslední změny 26.04.2025
</footer>
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('image-preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>
