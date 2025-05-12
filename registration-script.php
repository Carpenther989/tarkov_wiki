<?php
if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                        }

global $conn;
require_once('dbsConnect.php');
$totalCheck = true;
if( isset($_POST['name']) )
{
    $name = $_POST['name'];
}
else{
    $name = "";
    $totalCheck =false;
    $errorMsg[]="username";
}

if( isset($_POST['email']) )
{
    $email = $_POST['email'];
}
else{
    $email= "";
    $totalCheck =false;
    $errorMsg[]="mail";
}

if( isset($_POST['password']) )
{
    $passwd = $_POST['password'];


}
else{
    $totalCheck =false;
    $errorMsg[]="password";
}



$stmt = $conn->prepare("SELECT usernameFree(:name)");
$stmt->execute(['name' => $name]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$firstKey = array_key_first($result);
$result = $result[$firstKey];


if($result==0)
{
$totalCheck =false;
$errorMsg[]="username";
}
$emailJson ='';
if(validateMail($email))
{
    $emailJson = emailToJson($email);
}
else{
    $errorMsg[]='mail';
    $totalCheck =false;
}

$uppercase = preg_match('@[A-Z]@', $passwd);
$lowercase = preg_match('@[a-z]@', $passwd);
$number    = preg_match('@[0-9]@', $passwd);
$specialChars = preg_match('@[^\w]@', $passwd);

if(!$uppercase || !$lowercase ||!$number || !$specialChars || strlen($passwd) < 8) {
    $errorMsg[]='heslo musí obsahovat alespoň jedno velké a malé pismeno, čislo, zvláštní znak a být dlouhé alespoň 8 znaků'; //enisa doporučuje
    $totalCheck =false;
}


if($totalCheck == true)
{
    $passwd= password_hash($passwd, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("call newUser(:username,:password,:email)");
    $stmt->execute(['username' => $name, 'password' => $passwd, 'email' => $emailJson]);
    $_SESSION['logged']=true;
    $_SESSION['loggedName']=$name;

    echo '<script type="text/javascript">
           window.location = "index.php";
      </script>';

}
else{
    echo 'něco se domradlo <br>';
    var_dump($errorMsg);

    echo '<script type="text/javascript">
           window.location = "register.php?error='.$errorMsg[0].'";
      </script>';

}


function  emailToJson($mail) :string
{
    $split = explode("@", $mail);
    $host = $split[0];
    $splitDomain = explode('.', $split[1]);
    $tld = $splitDomain[sizeof($splitDomain) - 1];
    $domain = '';
    for ($i = 0; $i < sizeof($splitDomain)-1; $i++) {
        $domain=  $domain.'.'.$splitDomain[$i];
    }

    $domain=substr_replace($domain,'',0,1);


    $test = $host.'@'.$domain.'.'.$tld;
    if($test == $mail)
    {//echo true;
    }

    $output = '[{ "username":"'.$host.'", "domain":"'.$domain.'", "tld":"'.$tld.'"}]';
    // echo $output;
    return $output;

}

function validateMail($mail) {
    if(filter_var($mail, FILTER_VALIDATE_EMAIL)==true)
    {
        return true;
    }
    else{
        return false;
    }
}


?>