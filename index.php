<html>
    
    <head>
        <title>Art Owner Page</title>
        <link rel="stylesheet" href="style.css">
    </head>
    
        <body>
            <div class="vertical-menu">
                <a href="index.php" class="active">Account Management</a>
                <a href="art_index.php">Art Owner Portal</a>
            </div>


            <h2>Display Art Owners</h2>
            <form method="GET" action="index.php">
                <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
                <input type="hidden" id="displayTablesRequest" name="displayTablesRequest">
                <p><input type="submit" value="Display" name="display"></p>
            </form>

            <h2>Art Owner Signup</h2>
            <form method="POST" action="index.php"> <!--refresh page when submitted-->
                <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                First Name: <input type="text" name="insFN"> <br /><br />
                Last Name: <input type="text" name="insLN"> <br /><br />
                Email: <input type="text" name="insemail"> <br /><br />

                <input type="submit" value="Insert" name="insertSubmit"></p>
            </form>

            <h2>Art Owner Update Information</h2>
            <form method="POST" action="index.php"> <!--refresh page when submitted-->
                <input type="hidden" id="updateOwnerQueryRequest" name="updateOwnerQueryRequest">
                <input type="radio" id="first_name" name="select_update_value" value="FirstName">
                <label for="first_name">First Name</label><br>
                <input type="radio" id="last_name" name="select_update_value" value="LastName">
                <label for="last_name">Last Name</label><br>
                <input type="radio" id="email" name="select_update_value" value="Email">
                <label for="email">Email</label><br>
                Current email: <input type="text" name="insCurrEmail"> <br /><br />
                New First Name/Last Name/Email: <input type="text" name="insNewValue"> <br /><br />

                <input type="submit" value="Update" name="insertNewValue"></p>
            </form>

            <h2>Art Owner Delete Account</h2>
            <form method="POST" action="index.php"> <!--refresh page when submitted-->
                <input type="hidden" id="deleteOwnerRequest" name="deleteOwnerRequest">
                Email: <input type="text" name="delEmail"> <br /><br />

                <input type="submit" value="Delete Myself" name="deleteOwner"></p>
            </form>
            <?php
                require_once('art_owner.php');
        
		    ?>
</body>
</html>