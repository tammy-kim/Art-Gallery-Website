

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
            //require('init.php');
            // this sets up a connection to the oracle database. The names don't really matter
            //$db = new \Oracle\Db("test_db", "Mine");
            		//this tells the system that it's no longer just parsing html; it's now parsing PHP

            $success = True; //keep track of errors so it redirects the page only if there are no errors
            $db_conn = NULL; // edit the login credentials in connectToDB()
            $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

            function debugAlertMessage($message) {
                global $show_debug_alert_messages;
    
                if ($show_debug_alert_messages) {
                    echo "<script type='text/javascript'>alert('" . $message . "');</script>";
                }
            }
            
            function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
                //echo "<br>running ".$cmdstr."<br>";
                global $db_conn, $success;
    
                $statement = OCIParse($db_conn, $cmdstr);
                //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work
    
                if (!$statement) {
                    echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                    echo htmlentities($e['message']);
                    $success = False;
                }
    
                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                    echo htmlentities($e['message']);
                    $success = False;
                }
    
                return $statement;
            }
            
            function connectToDB() {
                global $db_conn;
    
                // Your username is ora_(CWL_ID) and the password is a(student number). For example,
                // ora_platypus is the username and a12345678 is the password.
                $db_conn = OCILogon("ora_minesher", "a28495142", "dbhost.students.cs.ubc.ca:1522/stu");
    
                if ($db_conn) {
                    debugAlertMessage("Database is Connected");
                    return true;
                } else {
                    debugAlertMessage("Cannot connect to Database");
                    $e = OCI_Error(); // For OCILogon errors pass no handle
                    echo htmlentities($e['message']);
                    return false;
                }
            }

            function disconnectFromDB() {
                global $db_conn;
    
                debugAlertMessage("Disconnect from Database");
                OCILogoff($db_conn);
            }

            function printResult($result) { //prints results from a select statement
                echo "<br>Retrieved data from table demoTable:<br>";
                echo "<table>";
                echo "<tr><th>fn</th><th>ln</th></tr>";
    
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<tr><td>" . $row["FIRSTNAME"] . "</td><td>" . $row["LASTNAME"] . "</td></tr>"; //or just use "echo $row[0]"
                }
    
                echo "</table>";
            }
            
            
            function handleDisplayRequest(){
                //$db = new \Oracle\Db("initialize", "Mine");
                global $db_conn;
                $sql = "SELECT FirstName, LastName FROM ArtOwner";
                // runs a sql query that returns values. the returned info is stored in res
                //$res = $db->execFetchAll($sql, "Query Example");
                $res = executePlainSQL($sql);
                // echo "<pre>"; var_dump($res); echo "</pre>\n";
                // create table
                //printResult(executePlainSQL("SELECT * FROM ArtOwner"));
                printResult($res);
                // echo "<table border='1'>\n";
                // echo "</th><th>". "Art Owners" . "</th></tr>";
                // echo "<tr><th>first name</th><th>last name</th></tr>\n";
                // // adds each row of information in res to the table
                // foreach ($res as $row) {
                //     $f = htmlspecialchars($row['FIRSTNAME'], ENT_NOQUOTES, 'UTF-8');
                //     $l   = htmlspecialchars($row['LASTNAME'], ENT_NOQUOTES, 'UTF-8');
                //     echo "<tr><td>$f</td><td>$l</td></tr>\n";
                // }
                // echo "</table>";
                OCICommit($db_conn);
            }


            function handleResetRequest() {
                global $db_conn;
                //$db = new \Oracle\Db("initialize", "Mine");
                // Drop old table
                $dropsql = "DROP TABLE ArtOwner";
                executePlainSQL($dropsql);
    
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
                executePlainSQL($sql, "create initial tables");
                OCICommit($db_conn);
            }

            function handleOwnerInsertRequest() {
                global $db_conn;
                //$db = new \Oracle\Db("initialize", "Mine");
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
                echo$fn;

    
                // $alltuples = array (
                //     $tuple
                // );
                //$sql = "INSERT INTO ArtOwner values (40, $fn, $ln, $em)";
                $sql = "INSERT INTO ArtOwner values (10000, 'abc', 'def', 'asdfasdf')";
                executePlainSQL($sql);
                OCICommit($db_conn);
            }

            function handlePOSTRequest() {
                if (connectToDB()){
                    if (array_key_exists('displayTablesRequest', $_POST)) {
                        handleDisplayRequest();
                    } else if (array_key_exists('resetTablesRequest', $_POST)) {
                       handleResetRequest();
                    } else if (array_key_exists('insertQueryRequest', $_POST)) {
                        handleOwnerInsertRequest();
                    }
                }


                disconnectFromDB();
            
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