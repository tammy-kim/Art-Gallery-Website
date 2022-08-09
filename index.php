<html>

    <head>
        <title>Art Owner Page</title>
        <link rel="stylesheet" href="style.css">
    </head>

        <body> <div class="container">
            <div class="menu">
                <a href="index.php" class="active">Gallery & Account Management</a>
                <a href="art_index.php" class="active">Art Owner Portal</a>
            </div>
            <div class="sub-header">
                Gallery & Account Management
            </div>

            <h2>All Art Owners</h2>
            <form method="GET" action="index.php">
                <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
                <input type="hidden" id="displayTablesRequest" name="displayTablesRequest">
                <p><input type="submit" value="Display" name="display"></p>
            </form>
            <div class="filler"></div>

            <h2>VIP Art Owners who own 2 or more artworks in our gallery</h2>
            <form method="GET" action="index.php">
                <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
                <input type="hidden" id="displayVIPTablesRequest" name="displayVIPTablesRequest">
                <p><input type="submit" value="View" name="vipdisplay"></p>
            </form>
            <div class="filler"></div>

            <h2>VIP Artists whose artworks have higher than average price</h2>
            <form method="GET" action="index.php">
                <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
                <input type="hidden" id="displayVIPArtistsRequest" name="displayVIPArtistsRequest">
                <p><input type="submit" value="View" name="vipartistdisplay"></p>
            </form>
            <div class="filler"></div>

            <h2>Floors in our gallery that have hosted all exhibitions</h2>
            <form method="GET" action="index.php">
                <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
                <input type="hidden" id="displayFloorsRequest" name="displayFloorsRequest">
                <p><input type="submit" value="View" name="floordisplay"></p>
            </form>
            <div class="filler"></div>

            <h2>Which Artists Created an Artwork Under This Title?</h2>
            <form method="POST" action="index.php">
                <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
                <input type="hidden" id="displayArtistRequest" name="displayArtistRequest">
                Title: <input type="text" name="artName"> <br /><br />
                <p><input type="submit" value="Search" name="artistdisplay"></p>
            </form>
            <div class="filler"></div>

            <h2>Art Owner SignUp</h2>
            <form method="POST" action="index.php"> <!--refresh page when submitted-->
                <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                First Name: <input type="text" name="insFN"> <br /><br />
                Last Name: <input type="text" name="insLN"> <br /><br />
                Email: <input type="text" name="insemail"> <br /><br />

                <input type="submit" value="Insert" name="insertSubmit"></p>
            </form>
            <div class="filler"></div>

            <h2>Art Owner Update Information</h2>
            <form method="POST" action="index.php"> <!--refresh page when submitted-->
                <input type="hidden" id="updateOwnerQueryRequest" name="updateOwnerQueryRequest">
                Which account information would you like to change? Please select one below.<br /><br />
                <input type="radio" id="first_name" name="select_update_value" value="FirstName">
                <label for="first_name">First Name</label><br>
                <input type="radio" id="last_name" name="select_update_value" value="LastName">
                <label for="last_name">Last Name</label><br>
                <input type="radio" id="email" name="select_update_value" value="Email">
                <label for="email">Email</label><br>
                Current email: <input type="text" name="insCurrEmail"> <br /><br />
                Input your new First Name / Last Name / Email: <input type="text" name="insNewValue"> <br /><br />
                <input type="submit" value="Update" name="insertNewValue"></p>
            </form>
            <div class="filler"></div>

            <h2>Art Owner Delete Account</h2>
            <form method="POST" action="index.php"> <!--refresh page when submitted-->
                <input type="hidden" id="deleteOwnerRequest" name="deleteOwnerRequest">
                Email: <input type="text" name="delEmail"> <br /><br />

                <input type="submit" value="Delete" name="deleteOwner"></p>
            </form>
            <div class="filler"></div>
            <?php
                require_once('art_owner.php');

		    ?>
    </div>
	</body>
</html>
