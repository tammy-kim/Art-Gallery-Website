

<html>
    
    <head>
        <title>Art Owner Page</title>
        <link rel="stylesheet" href="style.css">
    </head>
    
        <body>

            <h2>Reset</h2>
            <p>If you wish to reset the table press on the reset button.</p>

            <form method="POST" action="wrapper.php">
                <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
                <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
                <p><input type="submit" value="Reset" name="reset"></p>
            </form>

            <h2>Display Art Owners</h2>
            <form method="POST" action="wrapper.php">
                <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
                <input type="hidden" id="displayTablesRequest" name="displayTablesRequest">
                <p><input type="submit" value="Display" name="display"></p>
            </form>

            <h2>Art Owner Signup</h2>
            <form method="POST" action="wrapper.php"> <!--refresh page when submitted-->
                <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                First Name: <input type="text" name="insFN"> <br /><br />
                Last Name: <input type="text" name="insLN"> <br /><br />
                Email: <input type="text" name="insemail"> <br /><br />

                <input type="submit" value="Insert" name="insertSubmit"></p>
            </form>

            <hr />
    
            <?php
            /**
             * Credit to oracle for code used in displaying art owners
             * https://docs.oracle.com/database/121/TDPPH/ch_three_db_access_class.htm#TDPPH147
             * ac_cred.inc.php: Secret Connection Credentials for a database class
             * @package Oracle
             */
            
            //require('ac_db.inc.php');
            require('init.php');
            // this sets up a connection to the oracle database. The names don't really matter
            $db = new \Oracle\Db("test_db", "Mine");
            
            function handleDisplayRequest(){
                $db = new \Oracle\Db("test_db", "Mine");
                $sql = "SELECT FirstName, LastName FROM ArtOwner";
                // runs a sql query that returns values. the returned info is stored in res
                $res = $db->execFetchAll($sql, "Query Example");
                // echo "<pre>"; var_dump($res); echo "</pre>\n";
                // create table
                echo "<table border='1'>\n";
                echo "</th><th>". "Art Owners" . "</th></tr>";
                echo "<tr><th>first name</th><th>last name</th></tr>\n";
                // adds each row of information in res to the table
                foreach ($res as $row) {
                    $f = htmlspecialchars($row['FIRSTNAME'], ENT_NOQUOTES, 'UTF-8');
                    $l   = htmlspecialchars($row['LASTNAME'], ENT_NOQUOTES, 'UTF-8');
                    echo "<tr><td>$f</td><td>$l</td></tr>\n";
                }
                echo "</table>";
            }


            function handleResetRequest() {
                //global $db_conn;
                $db = new \Oracle\Db("test_db", "Mine");
                // Drop old table
                $dropsql = "DROP TABLE ArtOwner";
                $db->execute($dropsql, "delete current art owners");
    
                // Create new table
                echo "<br> resetting art owner table <br>";
                //$sql = file_get_contents('initialize.sql');
                $sql = "CREATE TABLE ArtOwner(
                    OwnerID integer PRIMARY KEY,
                    FirstName char(50),
                    LastName char(50),
                    Email char(50),
                    UNIQUE (Email)
                )";
                // actually runs the sql code above
                $db->execute($sql, "create initial tables");
            }

            function handleOwnerInsertRequest() {
                //global $db_conn;
                $db = new \Oracle\Db("test_db", "Mine");
                //Getting the values from user and insert data into the table
                // $tuple = array (
                //     ":bind1" => $_POST['inspin'],
                //     ":bind2" => $_POST['insFN'],
                //     ":bind3" => $_POST['insLN'],
                //     ":bind4" => $_POST['insemail']
                // );
                $ownerID = 5;
                $fn = $_POST['insFN'];
                $ln = $_POST['insLN'];
                $em = $_POST['insemail'];
    
                // $alltuples = array (
                //     $tuple
                // );
                $sql = "INSERT INTO ArtOwner values (1, $fn, $ln, $em)";
                //$sql = "INSERT INTO ArtOwner values (5, 'abc', 'def', 'gmail')";
                $db->execute($sql, "inserting new owner");
                //OCICommit($db_conn);
            }

            function handlePOSTRequest() {

                if (array_key_exists('displayTablesRequest', $_POST)) {
                    handleDisplayRequest();
                } else if (array_key_exists('resetTablesRequest', $_POST)) {
                   handleResetRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleOwnerInsertRequest();
                }

                //disconnectFromDB();
            
            }

            if (isset($_POST['reset']) || isset($_POST['display']) || isset($_POST['insertSubmit'])) {
                handlePOSTRequest();
            } else if (isset($_GET['countTupleRequest'])) {
                //handleGETRequest();
            }



            
            // $drop = "DROP TABLE ArtOwner";
            // $db->execute($drop, "delete the artowner table");
            ?>
        </body>
    </html>