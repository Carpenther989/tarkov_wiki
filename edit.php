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
    <link rel="stylesheet" href="css/main.css?v=<?= time()+1 ?>"/>
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



    <?php

    global $conn;
    require_once('dbsConnect.php');
    $errormsg[]='';
    if(isset($_POST['qn']))
    {
        //SELECT * FROM questnametext WHERE NAME = '11'
        //questname, authorID, txt,
        $qn = $_POST['qn'];
        echo'<a href="content.php?questname='.$qn.'">Zpět na úkol </a> <br>';
        echo '<h1> Chystáte se upravovat popisek úkolu: '.$_POST['qn'].'</h1> <br> ';
        echo 'Zadejte nový popisek: ';

        $stmt = $conn->prepare("SELECT * from questnametext WHERE name=:qwest;");
        $stmt->bindParam(':qwest', $qn);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        //  var_dump($result);
        echo'<form method="post" action="saveEdit.php">
            <textarea class="dark-textarea" name="txt" rows="4" cols="50">'.$result['txt'].'</textarea>
            <input type="hidden" name="qn" value="'.$qn.'"><br>
            <label for="submit" class="submit-btn">
                Uložit
                <input type="submit" name="submit" value="uložit" id="submit" class="btn-hide">
            </label>
            
            </form>';
    }
    else{
        $errormsg[]='chybí název questu';
        var_dump($errormsg);
    }
    ?>


</div>

</body>
</html>



