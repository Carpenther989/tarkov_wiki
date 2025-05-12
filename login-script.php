<?php
if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                        }

global $conn;
require_once('dbsConnect.php');
if($_SESSION["logged"]==true){
    echo '<script type="text/javascript">
           window.location = "index.php";
      </script>';
}
//------------------------------------

$name='testUser';
$password='';

if( isset($_POST['name']) )
{
    $name = $_POST['name'];
}

if( isset($_POST['password']) )
{
    $password = $_POST['password'];
}


$stmt = $conn->prepare("SELECT getHashByName(:name)");
$stmt->execute(['name' => $name]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$firstKey = array_key_first($result);
$result = $result[$firstKey];
//echo $result;
echo '<br>';
$validuser = password_verify($password, $result);

/*if($validuser) {
    //v session "logged" skladujeme jestli je uživatel přihlášen
    $_SESSION["logged"]=true;
    $_SESSION['loggedName']=$name;
  //  echo 'izi';
    echo '<script type="text/javascript">
           window.location = "index.php";
      </script>';
}
else{
    $_SESSION["logged"]=false;
    echo '<script type="text/javascript">
           window.location = "login.php?error=wrongpassword";
      </script>';
}*/

?>