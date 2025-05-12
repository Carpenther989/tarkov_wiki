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

      <div class="hero" id="section00">
          <div class="overlay"></div>
          <div class="hero-content">
              <h1>Tarkov Wiki - Vaše Tarkovská wikipedie...</h1>

          </div>
      </div>
    <div class="clearfix"><br></div>
    <div class="main" id="quests">
        <div class="clearfix"><br></div>

        <h1>Česká Tarkov Wikipedie</h1>

        <div class="clearfix"><br></div>
        <p class="text">
            Vítejte na české wikipedii pro hru Escape From Tarkov. Níže naleznete všechny doposud zpracované úkoly
            (v angličtině questy) s popisem, obrázky, mapou a traderem (obchodníkem, pro kterého se úkoly plní; dále také jako
            'trader'). Naše stránka se každým dnem vyvíjí a naším cílem je vytvořit návod jak splnit všechny příběhové úkoly
            ze hry. A jak nám můžete pomoct i vy? Pokud najdete v popisku úkolu chybu, můžete nám pomoct ji opravit.

        </p>
        <p class="text">
            V případě otázek či dalších návrhů nás neváhejte kontaktovat na našem <a href="https://discord.gg/6J7ASP88">discord serveru. </a>
        </p>
        <div class="clearfix"><br></div>
        <div class="align-content-center">
            <div class="mb-3">
                <label for="search" class="form-label">Vyhledávání</label>
                <input type="text" class="form-control" id="search" placeholder="Zadejte název questu">
                <button type="button" class="search-btn" onclick="filterList()" >Hledat </button>
            </div>
        </div>
        <div class="clearfix"><br></div>

        <div class="clearfix"><br></div>

        <div class="list">

            <h3>Seznam Úkolů</h3>

            <ul id="questList">

<?php

global $conn;
require_once('dbsConnect.php');
$sql = 'SELECT * FROM questyjmena';

//filtrování trader a mapa
if(isset($_POST["trader"]) and isset($_POST["map"])){
    foreach ($conn->query($sql) as $row) {
        if($row['trader']==$_POST['trader'] and $row['mapa']==$_POST['map']){
            print '<li> <a href="content.php?questname='.$row['name'].'">'.$row['name'] . " </a></li>";
        }

    }
}
//filtrování pouze trader
elseif (isset($_POST["map"]) and !isset($_POST["trader"]) ){
    foreach ($conn->query($sql) as $row) {
        if( $row['mapa']==$_POST['map']){
            print '<li> <a href="content.php?questname='.$row['name'].'">'.$row['name'] . " </a></li>";
        }

    }
}
// filtrování pouze mapa
elseif (!isset($_POST["map"]) and isset($_POST["trader"]) ){
    foreach ($conn->query($sql) as $row) {
        if($row['trader']==$_POST['trader']){
            print '<li> <a href="content.php?questname='.$row['name'].'">'.$row['name'] . " </a></li>";
        }

    }
}
// bez filtru na mapy
else {
    foreach ($conn->query($sql) as $row) {
        print '<li> <a href="content.php?questname='.$row['name'].'">'.$row['name'] . " </a></li>";}
}



?>

            </ul>
        </div>
    </div>

      <footer>
          FIM UHK Seminární práce pro předměty TNPW2 a DBS2, Autoři: Jáchym Málek, Daniel Mrva, Datum poslední změny 26.04.2025
      </footer>

      <script>
          function filterList() {
              const input = document.getElementById("search").value.toLowerCase();
              const listItems = document.querySelectorAll("#questList li");

              listItems.forEach(item => {
                  const text = item.textContent.toLowerCase();
                  item.style.display = text.includes(input) ? "list-item" : "none";
              });
          }

          document.getElementById("search").addEventListener("keypress", function(e) {
              if (e.key === "Enter") {
                  filterList();
              }
          });
      </script>

  </body>
</html>

