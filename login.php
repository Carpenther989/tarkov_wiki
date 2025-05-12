<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Přihlášení</title>
        <link rel="icon" href="img/Fake_Mustache_NoBackground.png" type="image/x-icon">

        <link href="css/login.css" rel="stylesheet">

        <script src="js/cookieManager.js"></script>
        <script src="js/userRecognition.js"></script>
    </head>
    <body>
    <?php
    if(isset($_GET["error"])){
        if($_GET['error'] == 'wrongpassword')
        {
            echo '<script type="text/javascript">
           window.confirm("něco se pokazilo (ノಠ益ಠ)ノ彡┻━┻");
                    </script>';
        }
    }



    ?>
        <div class="container">
            <div class="text">
                <a href="index.php" style="text-decoration: none"><small>Zpátky domů</small></a>
            </div>

            <h3>Přihlášení</h3>

            <form id="login_form" action="login-script.php" method="POST">
                <div class="form-row">
                    <div class="input-data">
                        <input type="text" required name="name"  autocomplete="new-password">
                        <div class="underline"></div>
                        <label for="name">Jméno</label>
                    </div>

                    
                    <div class="input-data">
                        <input type="password" required name="password"  autocomplete="new-password">
                        <div class="underline"></div>
                        <label for="password">Heslo</label>
                    </div>
                </div>
                <div class="form-row submit-btn">
                    <div class="input-data">
                        <div class="inner"></div>
                        <input type="submit" value="Přihlásit se">
                    </div>
                </div>
                    
            </form>
            <!--
            <script>
                document.addEventListener("DOMContentLoaded", function ()
                    {
                        const form = document.getElementById('login_form');

                        form.addEventListener('submit', (e) =>
                            {
                                e.preventDefault();
                                checkExistingUser(form.querySelector('input[id=name]').value, form.querySelector('input[id=password]').value).then(result =>
                                    {
                                        if(result)
                                        {
                                            location.href = "index.html";
                                        } else {
                                            alert('Bad password or name')
                                        }
                                    }
                                );

                            }
                        );
                    }
                );
            </script> -->
        </div>
    </body>
</html>