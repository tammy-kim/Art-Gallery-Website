
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

            function printArtOwnerResult($result) { //prints results from a select statement
                echo "<br>Retrieved data from table demoTable:<br>";
                echo "<table>";
                echo "<tr><th>fn</th><th>ln</th></tr>";
    
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
                }
    
                echo "</table>";
            }

            function printResult($result) { //prints results from a select statement
                echo "<br>Retrieved data from table demoTable:<br>";
                echo "<table>";
                echo "<tr><th>ID</th><th>Name</th></tr>";
    
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]" 
                }
    
                echo "</table>";
            }

            function handleDisplayRequest() {
                global $db_conn;
    
                $result = executePlainSQL("SELECT id, name FROM demoTable");
    
                // if (($row = oci_fetch_row($result)) != false) {
                    printResult($result);
                // }
            }
            
            
            function handleArtOwnerDisplayRequest(){
                //$db = new \Oracle\Db("initialize", "Mine");
                global $db_conn;
                //$sql = "SELECT FirstName, LastName FROM ArtOwner ORDER BY OwnerID";

                // runs a sql query that returns values. the returned info is stored in res
                //$res = $db->execFetchAll($sql, "Query Example");
                //$res = executePlainSQL($sql);
                $res = executePlainSQL("SELECT FirstName, LastName FROM ArtOwner");
                OCICommit($db_conn);
                // echo "<pre>"; var_dump($res); echo "</pre>\n";
                // create table
                //printResult(executePlainSQL("SELECT * FROM ArtOwner"));
                printArtOwnerResult($res);
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
            function executeBoundSQL($cmdstr, $list) {
                /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
            In this case you don't need to create the statement several times. Bound variables cause a statement to only be
            parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
            See the sample code below for how this function is used */
    
                global $db_conn, $success;
                $statement = OCIParse($db_conn, $cmdstr);
    
                if (!$statement) {
                    echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($db_conn);
                    echo htmlentities($e['message']);
                    $success = False;
                }
    
                foreach ($list as $tuple) {
                    foreach ($tuple as $bind => $val) {
                        //echo $val;
                        //echo "<br>".$bind."<br>";
                        OCIBindByName($statement, $bind, $val);
                        unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
                    }
    
                    $r = OCIExecute($statement, OCI_DEFAULT);
                    if (!$r) {
                        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                        $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                        echo htmlentities($e['message']);
                        echo "<br>";
                        $success = False;
                    }
                }
            }

            function handleResetRequest() {
                // global $db_conn;
                // //$db = new \Oracle\Db("initialize", "Mine");
                // // Drop old table
                // $dropsql = "DROP TABLE ArtOwner";
                // executePlainSQL($dropsql);
    
                // // Create new table
                // echo "<br> resetting art owner table <br>";
                // //$sql = file_get_contents('initialize.sql');
                // $sql = "CREATE TABLE ArtOwner(
                //     OwnerID integer PRIMARY KEY,
                //     FirstName char(50),
                //     LastName char(50),
                //     Email char(50),
                //     UNIQUE (Email)
                // )";

                // // actually runs the sql code above
                // executePlainSQL($sql, "create initial tables");
                // OCICommit($db_conn);
            }

            function handleOwnerInsertRequest() {
                global $db_conn;
                //$db = new \Oracle\Db("initialize", "Mine");
                //Getting the values from user and insert data into the table
                $tuple = array (

                    ":bind1" => $_POST['insFN'],
                    ":bind2" => $_POST['insLN'],
                    ":bind3" => $_POST['insemail']
                );
                // $ownerID = 5;
                // $fn = $_POST['insFN'];
                // $ln = $_POST['insLN'];
                // $em = $_POST['insemail'];
                
                //$ownerid = ...;
                $alltuples = array (
                    $tuple
                );
                //$sql = "INSERT INTO ArtOwner values (41, '" . $fn . "', '" . $ln . "', '" . $em . "')";
                //$sql = "INSERT INTO ArtOwner values (5, 'abcf', 'deff', 'asdfasdff')";
                executeBoundSQL("insert into ArtOwner values (32, :bind1, :bind2, :bind3)", $alltuples);
                OCICommit($db_conn);
            }

            function handlePOSTRequest() {
                if (connectToDB()){
                    if (array_key_exists('resetTablesRequest', $_POST)) {
                       handleResetRequest();
                    } else if (array_key_exists('insertQueryRequest', $_POST)) {
                        handleOwnerInsertRequest();
                    }
                }


                disconnectFromDB();
            
            }

            function handleGETRequest() {
                if (connectToDB()) {
                    if (array_key_exists('displayTablesRequest', $_GET)) {
                        //echo"2";
                        handleArtOwnerDisplayRequest();
                    } else if (array_key_exists('displayTuples', $_GET)) {
                        handleDisplayRequest();
                    }
    
                    
                }
                disconnectFromDB();
            }

            if (isset($_POST['reset']) || isset($_POST['insertSubmit'])) {
                
                handlePOSTRequest();
            } else if (isset($_GET['display']) || isset($_GET['displayTupleRequest'])) {
                //echo"1";
                handleGETRequest();
            }



            
            // $drop = "DROP TABLE ArtOwner";
            // $db->execute($drop, "delete the artowner table");
            ?>
        </body>
    </html>