<html>
    
    <head>
        <title>Art Owner Portal Page</title>
        <link rel="stylesheet" href="style.css">
    </head>
    
    <body>
        <div class="vertical-menu">
            <a href="index.php" class="active">Account Management</a>
            <a href="art_index.php">Art Owner Portal</a>
        </div>

        <h2>Login</h2>
            <form method="GET" action="art_index.php">
                <input type="hidden" id="loginRequest" name="loginRequest">
                <p><input type="submit" value="Login" name="login"></p>
            </form>


        <?php
            //echo"hello";
            require_once('owner_portal.php');

        ?>


    </body>
</html>