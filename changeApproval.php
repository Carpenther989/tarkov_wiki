<?php

    session_start();
                        
?>
<!DOCTYPE html>
<html lang="cs" data-bs-theme="dark">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Tarkov Wiki</title>
    <link rel="icon" href="img/Fake_Mustache_NoBackground.png" type="image/x-icon">
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

    <h2>Změny úkolů</h2>
    <hr class="line">

    <?php

    global $conn;
    require_once('dbsConnect.php');
    $errormsg []='';

    if(isset($_SESSION['logged'])and $_SESSION['logged']==true and isset($_SESSION['loggedName'])){
        $name = $_SESSION['loggedName'];
        $stm = $conn -> prepare('SELECT isMod((SELECT getIDByName(:name))) as "nm"' );
        $stm -> bindParam(':name', $name);
        $stm -> execute();
        $row = $stm -> fetch();
        if($row['nm']!=1)
        {
            $errormsg[]='uživatel není moderátorem';
            echo '<script type="text/javascript">
           window.location = "index.php";
      </script>';
        }

    }
    else{
        $errormsg[]='uživatel nepřihlášen';
    }

    $stm2 = $conn -> prepare('SELECT * FROM unapprovedchanges' );
    $stm2 -> execute();
    $result = $stm2 -> fetchAll();
    foreach($result as $row){
        echo'<div style="">';
        echo '<form class="form-one" action="approve.php" method="post">';
        echo'<p> starý text: '.$row['OldTxt'].'</p>';
        echo'<p> nový text: '.$row['NewTxt'].'</p>';
        echo'<br>změnu navrhnul uživatel : '.$row['usrnm'].' v čase : '.$row['timest'];
        echo'<input type="hidden"  name="postID" value="'.$row['id'].'">';
        echo'<br><input type="radio" id="disaprove" name="dec" value="0">
            <label for="disaprove">Smazat</label><br>
            <input type="radio" id="aprove" name="dec" value="1">
            <label for="aprove">Potvrdit</label><br>';
        echo' <input type="submit" value="submit">';
        echo'</form>';
        echo'</div>';
        echo '<hr class="line">';
    }
    ?>



</div>

</body>
</html>







