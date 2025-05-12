<?php
if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                        }

global $conn;
require_once('dbsConnect.php');
$formOk = false;

$questname='default';
$questXP=1;
$questMoney=1;
$questDscr='default description, pokud tohle vidíte něco se pokazilo';
$trader = 1;
$map=1;
$imageContent=null;
if( isset($_POST['name']) )
{
    $questname = $_POST['name'];

}
else{
    $errorMsg[]="chybí název questu";
}

if( isset($_POST['xp']) )
{
    $questXP = $_POST['xp'];

}
else{
    $errorMsg[]="chybí xp";
}

if( isset($_POST['reward']) )
{
    $questMoney = $_POST['reward'];

}
else{
    $errorMsg[]="chybí moni";
}

if( isset($_POST['description']) )
{
    $questDscr = $_POST['description'];

}
else{
    $errorMsg[]="chybí popis";
}

if( isset($_POST['maps']) )
{
    $map = $_POST['maps'];
}
else{
    $errorMsg[]="chybí mapa";
}
if( isset($_POST['traders']) ){
     $trader = $_POST['traders'];
}
else{
    $errorMsg[]="chybí trader";
}





// kontroluje zda byl soubor nahrán
    if (isset($_FILES["image"])) {
      $image = $_FILES["image"];
      $imageName = $image["name"];
      $imageError = $image["error"];
      $imgExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
      $valid_extensions = array("jpeg", "jpg", "png");
// kontrluje formát souboru
      if (in_array($imgExt, $valid_extensions)) {
          // kontrolue zda nahrání proběhlo v pořádku
        if($imageError === 0){
          //kontroluje za není obráek moc velký
            if($image["size"] < 5000000){
                // deklarujeme že upload proběhl v pořádku a lze odeslat do databáze
                $formOk = true;
                $imageContent = file_get_contents($image["tmp_name"]);
            }
            else{
                $errorMsg[]='obrázek je příliš velký(max povolená velikost je 500mb)';
            }

        }
        else{
            $errorMsg[]= "neco se pokazilo : " . $imageError ;
        }
      }
      else{
          $errorMsg[]="nepovolený formát obrázku";
      }

    }
    else{
        $errorMsg[]= 'chybí obrázek';
    }
if($_SESSION["logged"]==false){
    $errorMsg[]='uživatel není přihlášen';
    $formOk = false;
}
else{
$username = $_SESSION["loggedName"];



    $stmt2 = $conn->prepare("SELECT getIDByName(:name)");
    $stmt2->execute(['name' => $username]);
    $result = $stmt2->fetch(PDO::FETCH_ASSOC);
    $firstKey = array_key_first($result);
    $result = $result[$firstKey];

    $stmt3 = $conn->prepare("SELECT isMod(:userID)");
    $stmt3->execute(['userID' => $result]);
    $result2 = $stmt3->fetch(PDO::FETCH_ASSOC);
    $firstKey = array_key_first($result2);
    $result2 = $result2[$firstKey];
    if($result2==0)
    {
        $errorMsg[]='nedostatečná oprávnění';
        $formOk = false;
    }


}


if($formOk == true){

    try {
        $stmt = $conn->prepare("call CreateQuest(:questname,:questDscr,:questXP,:questMoney,:image,:maps,:traders)");
        $stmt->bindParam(':questname', $questname);
        $stmt->bindParam(':questDscr', $questDscr);
        $stmt->bindParam(':questXP', $questXP);
        $stmt->bindParam(':questMoney', $questMoney);
        $stmt->bindParam(':maps', $map);
        $stmt->bindParam(':traders', $trader);
        $stmt->bindParam(':image', $imageContent,PDO::PARAM_LOB);
        $stmt->execute();

        echo '<script type="text/javascript">
           window.location = "index.php";
      </script>';
    }
    catch(PDOException $e){
        echo $e->getMessage().' probléms připojením';
    }

}
else{
   var_dump($errorMsg);
}




?>
