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
        <?php
        //session_start();
        global $conn;
        require_once('dbsConnect.php');
        $questname = $_GET['questname'];

        echo '<h1 class="quest-name">'.$questname.'</h1><hr class="line">';
        echo '<br>';
        $stmt = $conn->prepare(query: "SELECT * from questselect WHERE name=:qwest;");
        $stmt->bindParam(':qwest', $questname);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['questID'] = $result['idq'];
        echo '<img class="image" src="data:image/jpeg;base64,'.base64_encode($result['img']).'"/><br>';
        echo '<div class="description-segment"><div class="description-name"><hr>';
        echo 'Popis úkolu</div> <hr class="line">';
        echo '<p>'.$result['popis'].'</p>';

            if(isset($_SESSION['logged']) and $_SESSION['logged'] ){
                echo '<br> <form action="edit.php" method="post">';
                echo '
            <input type="hidden" value="'.$questname.'" name="qn">
            Je v popisku něco v nepořádku? Můžete úkol upravit 
            <label for="edit" class="bluehypertext">
            zde.
            <input type="submit" value="editovat" id="edit" class="btn-hide"> </form>
            </label>
            ';} else {
                echo 'Je v popisku něco v nepořádku? Přihlaste se a můžete chybu opravit.';
            }

        echo '</div>';
        echo '<div class="description-segment"><div class="description-name">';
        echo 'Zkušenostní body (XP)</div><hr class="line">'.$result['xp'].'<br>';
        echo '</div>';
        echo '<div class="description-segment"><div class="description-name">';
        echo 'Finančí odměna</div><hr class="line">'.$result['moni'].' rublů blyat <br>';
        echo '</div>';
        echo '<div class="description-segment"><div class="description-name">';
        echo 'Trader</div><hr class="line">'.$result['trader'].'<br>';
        echo '</div>';
        echo '<div class="description-segment"><div class="description-name">';
        echo 'Mapa</div><hr class="line">'.$result['mapa'].'<br>';
        echo '</div>';


        ?>
    </div>
    <h2>Komentáře</h2>

    <?php
    if(isset($_SESSION['logged']) and $_SESSION['logged'] ){


    echo '
    
    <div class="comment-submit">
        <form action="commentUpload.php" method="post">
            Přidejte svůj komentář: <br>
            <textarea type="text" name="commentText" id="commentText" class="comment-textarea"></textarea> <br>
            <input name="qn" type="hidden" value="'.$questname.'">
            <label class="submit-btn">
           
                Zveřejnit
                <input type="submit" class="btn-hide">
            </label>


        </form>
    </div>';
    }

    ?>
    <div class="comment-section">


        <?php
        $comStm = $conn->prepare("SELECT * from coments where coments.quest = :questname");
        $comStm->bindParam(':questname', $questname);
        $comStm->execute();
        $result = $comStm->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row){
            echo '<div class="comment">';
            echo '<div class="comment-header">'.$row['name'].' '.$row['timestamp'].'</div>';
            echo '<div class="comment-text">'.$row['text'].'</div> </div>';
        }
        ?>


    </div>

</div>
<footer>
    FIM UHK Seminární práce pro předměty TNPW2 a DBS2, Autoři: Jáchym Málek, Daniel Mrva, Datum poslední změny 26.04.2025
</footer>

</body>
</html>
