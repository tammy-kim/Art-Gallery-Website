<html>
    
    <head>
        <title>Art Owner Page</title>
        <link rel="stylesheet" href="style.css">
    </head>
    
        <body>

            <h2>Reset</h2>
            <p>If you wish to reset the table press on the reset button.</p>

            <form method="POST" action="index.php">
                <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
                <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
                <p><input type="submit" value="Reset" name="reset"></p>
            </form>

            <h2>Display the Tuples in DemoTable</h2>
        <form method="GET" action="index.php"> <!--refresh page when submitted-->
        <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
        <input type="submit" name="displayTuples"></p>

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
            <?php
                require_once('art_owner.php');
        
		    ?>
</body>
</html>