<?php

if (session_status() === PHP_SESSION_NONE) {
                        session_start();
}

global $conn;
require_once('dbsConnect.php');
$err[]='';
$qn='';
if(isset($_POST['txt']) and isset($_SESSION['loggedName']) and isset($_POST['qn'])){
    //TODO implementovat insert do navrhovaných změn
    //echo $_POST['txt'];
    $qn=$_POST['qn'];
    $stm=$conn->prepare("CALL newEdit(:qn,:txt,getIDByName(:an))");
    $stm->bindParam(':txt', $_POST['txt']);
    $stm->bindParam(':an', $_SESSION['loggedName']);
    $stm->bindParam(':qn', $_POST['qn']);
    $stm->execute();
    echo 'něco by se mělo uložit';
    //docker inspect <container_id> | grep "IPAddress"
}
else{
    $err[]='uživatel není přihlášen';

}
echo '<script type="text/javascript">
           window.location = "content.php?questname='.$qn.'"
      </script>';

?>