<?php
    session_start();
    global $conn;
    require_once('dbsConnect.php');
    $postID=1;
    $approved=0;
    $ok = true;
    $errormsg[]='';
    $usrname='testUser';
    if(isset($_POST['postID'])){
        //postID je id návrhu editu
        $postID = $_POST['postID'];
    }
    else{
        $errormsg[]='chybí id příspevku';
        $ok = false;
    }
    if(isset($_POST['dec'])){$approved=$_POST['dec'];}
    else{$errormsg[]='jak se tohle stalo, je tam radio button';
        $ok = false;}

    if(isset($_SESSION['loggedName'])){
        $usrname = $_SESSION['loggedName'];
    }
    else{
        $errormsg[]='nějak chybí jméno idk';
        $ok = false;
    }

    if($ok and $approved==1){
        $userID=1;

        $usrStm = $conn->prepare('SELECT getIDByName(:usr) as "k"');
        $usrStm -> bindParam(':usr', $usrname);
        $usrStm -> execute();
        $result = $usrStm -> fetch();
        $userID = $result['k'];


        $aproveStm = $conn->prepare("CALL approveChange(:usr, :postID)");
        $aproveStm->bindParam('postID', $postID);
        $aproveStm->bindParam('usr', $userID);
        $aproveStm->execute();

    }
    elseif ($ok and $approved==0){

        $dltStm = $conn->prepare("CALL delEdit(:postID)");
        $dltStm->bindParam(':postID', $postID);
        $dltStm->execute();
    }
    echo '<script type="text/javascript">
           window.location = "changeApproval.php"
      </script>';

    ?>




