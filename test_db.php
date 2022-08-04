

<html>
    
<head>
    <title>Testing db</title>
    <link rel="stylesheet" href="style.css">
</head>

    <body>

        <h2>Get Name with ID 1</h2>
        <form method="GET" action="test_db.php">
            <input type="hidden" id="getName1" name="getName1">
            <input type="submit" name="getName1"></p>
        </form>

        <h2>Count the Tuples in DemoTable</h2>
        <form method="GET" action="test_db.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" name="countTuples"></p>
        </form>


        <?php
        /**
         * Credit to oracle, link below
         * https://docs.oracle.com/database/121/TDPPH/ch_three_db_access_class.htm#TDPPH147
         * ac_cred.inc.php: Secret Connection Credentials for a database class
         * @package Oracle
         */
        
        // test_db.php
        $success = True; //keep track of errors so it redirects the page only if there are no errors   (ADDED)
        $db_conn = NULL; // edit the login credentials in connectToDB()       (ADDED)

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

        function printResult($result) { //prints results from a select statement
            echo "<br>Retrieved data from table demoTable:<br>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_yak226", "a30777149", "dbhost.students.cs.ubc.ca:1522/stu");

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

/*         function handleUpdateRequest() {
            global $db_conn;

            $old_name = $_POST['oldName'];
            $new_name = $_POST['newName'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE demoTable SET name='" . $new_name . "' WHERE name='" . $old_name . "'");
            OCICommit($db_conn);
        } */

/*         function handleResetRequest() {
            global $db_conn;
            // Drop old table
            executePlainSQL("DROP TABLE demoTable");

            // Create new table
            echo "<br> creating new table <br>";
            executePlainSQL("CREATE TABLE demoTable (id int PRIMARY KEY, name char(30))");
            OCICommit($db_conn);
        } */

/*         function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insID'],
                ":bind2" => $_POST['insName']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into demoTable values (:bind1, :bind2)", $alltuples);
            OCICommit($db_conn);
        } */

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM demoTable");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in demoTable: " . $row[0] . "<br>";
            }
        }

        function handleGetName1Request() {    // why won't this work?? TODO 
            global $db_conn;

            $result = executePlainSQL("SELECT name FROM demoTable WHERE id=1");

            printResult($result);
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handlePOSTRequest() {
        if (connectToDB()) {
            if (array_key_exists('resetTablesRequest', $_POST)) {
                handleResetRequest();
            } else if (array_key_exists('updateQueryRequest', $_POST)) {
                handleUpdateRequest();
            } else if (array_key_exists('insertQueryRequest', $_POST)) {
                handleInsertRequest();
            }

            disconnectFromDB();
        }
    }

    // HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handleGETRequest() {
        if (connectToDB()) {
            if (array_key_exists('countTuples', $_GET)) {
                handleCountRequest();
                //handleGetName1Request();
            }
            if (array_key_exists('getName1', $_GET)) {
                handleGetName1Request();
            }

            disconnectFromDB();
        }
    }

    if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])) {
        handlePOSTRequest();
    } else if (isset($_GET['countTuples']) || isset($_GET['getName1'])) {
        handleGETRequest();
    }


        
        require('ac_db.inc.php');
        // this sets up a connection to the oracle database. The names don't really matter
        $db = new \Oracle\Db("test_db", "Mine");
        // sql to make a demo table and insert one value
        $a = "CREATE TABLE demoTable (id int PRIMARY KEY, name char(30)), INSERT INTO demoTable VALUES (1, 'meen')";
        // actually runs the sql code above
        $db->execute($a, "create table");
        $sql = "SELECT id, name FROM demoTable ORDER BY id";
        // runs a sql query that returns values. the returned info is stored in res
        $res = $db->execFetchAll($sql, "Query Example");
        // echo "<pre>"; var_dump($res); echo "</pre>\n";
        
        // create table
        echo "<table border='1'>\n";
        echo "<tr><th>id</th><th>name</th></tr>\n";
        // adds each row of information in res to the table
        foreach ($res as $row) {
            $id = htmlspecialchars($row['ID'], ENT_NOQUOTES, 'UTF-8');
            $name   = htmlspecialchars($row['NAME'], ENT_NOQUOTES, 'UTF-8');
            echo "<tr><td>$id</td><td>$name</td></tr>\n";
        }
        echo "</table>";
        
        ?>
    </body>
</html>