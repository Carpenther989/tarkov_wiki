<?php
if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                        }

global $conn;
require_once('dbsConnect.php');
$username = "";
$questID=1;
$errormsg[]='';
$totalCheck = true;
$txt='něco se pokjazilo';
if(isset($_SESSION["logged"]) && $_SESSION["logged"]==true and isset($_SESSION["loggedName"])){
    $username = $_SESSION["loggedName"];
}
else{
$errormsg[]='uživatel není přihlášen';
$totalCheck = false;
}
if(isset($_SESSION['questID'])){
$questID = $_SESSION['questID'];
}
else{$errormsg[]='chybí id questu';
$totalCheck = false;}
if(isset($_POST['commentText'])){
    $txt = $_POST['commentText'];
}
else{
    $totalCheck = false;
    $errormsg[]='není text';
}


//CALL createComment(4,'každy run někdo campí naproti přes ulici, co mám dělat','testMod')
if($totalCheck){
    $stmt = $conn->prepare("call createComment(:idq,:txt,:usernm)");
    $stmt->execute(['idq' => $questID, 'txt' => $txt, 'usernm' => $username]);
echo'izi';

    echo '<script type="text/javascript">
           window.location = "content.php?questname='.$_POST['qn'].'"
      </script>';
}
else{var_dump($errormsg);
    echo '<script type="text/javascript">
           window.location = "index.php";
      </script>';
}

?>
