<html>
    
    <head>
        <title>Art Owner Page</title>
        <link rel="stylesheet" href="style.css">
    </head>
    
        <body>


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

            <h2>Art Owner Update Email</h2>
            <form method="POST" action="index.php"> <!--refresh page when submitted-->
                <input type="hidden" id="updateOwnerQueryRequest" name="updateOwnerQueryRequest">
                Old email: <input type="text" name="insOldEmail"> <br /><br />
                New email: <input type="text" name="insNewEmail"> <br /><br />

                <input type="submit" value="Update" name="insertNewEmail"></p>
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