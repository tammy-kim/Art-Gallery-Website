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
            <form method="GET" action="wrapper.php">
                <input type="hidden" id="loginRequest" name="loginRequest">
                Email: <input type="text" name="loginEmail"> <br /><br />
                <p><input type="submit" value="Login" name="login"></p>
            </form>
        
        <h2>View My Artwork in the Gallery</h2>
            <form method="GET" action="wrapper.php">
                <input type="hidden" id="seeMyArtRequest" name="seeMyArtRequest">
                <input type="radio" id="all" name="select_art_type" value="All">
                <label for="all">All</label><br>
                <input type="radio" id="paintings" name="select_art_type" value="Paintings">
                <label for="paintings">Paintings</label><br>
                <input type="radio" id="sculptures" name="select_art_type" value="Sculptures">
                <label for="sculptures">Sculptures</label>
                
                <p><input type="submit" value="View" name="view"></p>
            </form>
        
        <h2>My Exhibition Fees</h2>
            <form method="GET" action="wrapper.php">
                <input type="hidden" id="seeMyFeesRequest" name="seeMyFeesRequest">
                <p><input type="submit" value="Sum Fees" name="sum_fees"></p>
            </form>

        <h2>Logout</h2>
            <form method="GET" action="wrapper.php">
                <input type="hidden" id="logoutRequest" name="logoutRequest">
                <p><input type="submit" value="Logout" name="logout"></p>
            </form>


        <?php
            //echo"hello";
            require_once('owner_portal.php');

        ?>


    </body>
</html>