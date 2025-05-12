<?php 
if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                        }

unset($_SESSION['logged']);
unset($_SESSION['loggedName']);

echo '<script type="text/javascript">
           window.location = "index.php";
      </script>';

?>
