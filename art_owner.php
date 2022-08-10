<?php
                /**
                 * Credit to Test Oracle file for UBC CPSC304 2018 Winter Term 1
                 * Created by Jiemin Zhang
                 * Modified by Simona Radu
                 * Modified by Jessica Wong (2018-06-22)
                 */

            $success = True; //keep track of errors so it redirects the page only if there are no errors
            $db_conn = NULL; // edit the login credentials in connectToDB()
            $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())
            $artist_list = NULL;

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
                $db_conn = OCILogon("ora_tammykim", "a57998726", "dbhost.students.cs.ubc.ca:1522/stu");

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
        echo "<br>List of registered Art Owners</br>";
        echo "<table>";
        echo "<tr><th>First Name</th><th>Last Name</th><th>Email</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>"; //or just use "echo $row[0]"
        }

        echo "</table>";
    }

    function printArtResult($result) {
        echo "<br>Here is a list of all art pieces in our gallery.</br>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Title</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
        }

        echo "</table>";
    }

    function printVIPOwners($result) {
        echo "<br>List of art owners who own more than 2 artworks</br>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Fist Name</th><th>Last Name</th><th>Email</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>";
        }

        echo "</table>";
    }

    function printVIPArtists($result) {
        echo "<br>List of artists with expensive artworks on average</br>";
        echo "<table>";
        echo "<tr><th>Artist ID</th><th>Price</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
        }

        echo "</table>";
    }

    function printFloors($result) {
        echo "<br>Floors that have hosted all exhibitions</br>";
        echo "<table>";
        echo "<tr><th>Floor number</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td></tr>";
        }

        echo "</table>";
    }

    function printArtists($result) {
        if ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<br>List of artists whose artwork(s) fall under this name as titles:";
            echo "<table>";
            echo "<tr><th>First Name</th><th>Last Name</th></tr>";
            do {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            } while ($row = OCI_Fetch_Array($result, OCI_BOTH));
        } else {
            echo "<br>Sorry, there are no artists who have artwork(s) with this title...</br>";
        }
    }

    function printResult($result) { //prints results from a select statement    (tutorial example code)
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
        printResult($result);
    }


    function handleArtOwnerDisplayRequest(){
        global $db_conn;

        $res = executePlainSQL("SELECT FirstName, LastName, Email FROM ArtOwner");
        OCICommit($db_conn);

        printArtOwnerResult($res);
        OCICommit($db_conn);
    }

    function handleArtDisplayRequest() {
        global $db_conn;
        $res = executePlainSQL("SELECT IdentificationNumber, Title FROM Art3");
        OCICommit($db_conn);
        printArtResult($res);
        OCICommit($db_conn);
    }

    function handleVIPDisplayRequest(){
        global $db_conn;

        $res = executePlainSQL("SELECT DISTINCT ao.OwnerID, FirstName, LastName, Email
                                FROM ArtOwner ao, Art3 a
                                WHERE ao.OwnerID=a.OwnerID
                                GROUP BY ao.OwnerID, FirstName, LastName, Email
                                Having count(*)>2");
        OCICommit($db_conn);

        printVIPOwners($res);
        OCICommit($db_conn);
    }

    function handleVIPArtistDisplayRequest() {
        global $db_conn;
        $res = executePlainSQL("SELECT a.artistid, a3.price
                                FROM artist a, art3 a3
                                WHERE a3.artistid=a.artistid
                                GROUP BY a.artistid, price
                                HAVING avg(a3.price) > all (select avg(price) from art3)");
        OCICommit($db_conn);

        printVIPArtists($res);
        OCICommit($db_conn);
    }

    function handleFloorDisplayRequest() {
        global $db_conn;
        $res = executePlainSQL("SELECT DISTINCT FloorNumber
                                FROM Room r1
                                WHERE NOT EXISTS
                                ((SELECT ExhibitionName from Exhibition)
                                MINUS
                                (SELECT r2.ExhibitionName from Room r2
                                WHERE r2.FloorNumber=r1.FloorNumber))");
        // This SQL query is not working rn but the rest about floor display is working
        OCICommit($db_conn);

        printFloors($res);
        OCICommit($db_conn);
    }

    function handleArtistDisplayRequest() {
        global $db_conn;
        $artName = $_POST['artName'];  // gets the entered artwork title

        $res = executePlainSQL("SELECT DISTINCT FirstName, LastName
            FROM Artist a, Art3 a3
            WHERE a.ArtistID=a3.ArtistID AND a3.Title='$artName'");
        OCICommit($db_conn);
        printArtists($res);
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

                //Getting the values from user and insert data into the table

                $rowcount = executePlainSQL("SELECT Count(*) FROM ArtOwner");
                $row = oci_fetch_row($rowcount);


                $tuple = array (

                    ":bind1" => $_POST['insFN'],
                    ":bind2" => $_POST['insLN'],
                    ":bind3" => $_POST['insemail']
                );

                // $ownerid = "$row[0]" + 1;
                $ownerid = $rowcount + 1;
                //echo"$ownerid";
                $alltuples = array (
                    $tuple
                );

                executeBoundSQL("insert into ArtOwner values ($ownerid, :bind1, :bind2, :bind3)", $alltuples);
                OCICommit($db_conn);
            }
            function handleOwnerUpdateRequest(){
                global $db_conn;
                $curr = $_POST['insCurrEmail'];
                $new = $_POST['insNewValue'];
                $radioSelection = $_POST['select_update_value'];
                executePlainSQL("UPDATE ArtOwner SET " . $radioSelection . "='" . $new . "' WHERE Email='" . $curr . "'");
                OCICommit($db_conn);
            }
            function handleOwnerDeleteRequest(){
                global $db_conn;
                $email = $_POST['delEmail'];
                executePlainSQL("DELETE FROM ArtOwner WHERE Email='" . $email . "'");
                OCICommit($db_conn);
            }
            function handlePOSTRequest() {
                if (connectToDB()){
                    if (array_key_exists('resetTablesRequest', $_POST)) {
                       handleResetRequest();
                    } else if (array_key_exists('insertQueryRequest', $_POST)) {
                        handleOwnerInsertRequest();
                    } else if (array_key_exists('updateOwnerQueryRequest', $_POST)) {
                        handleOwnerUpdateRequest();
                    } else if (array_key_exists('deleteOwnerRequest', $_POST)) {
                        handleOwnerDeleteRequest();
                    } else if (array_key_exists('displayArtistRequest', $_POST)) {
                        handleArtistDisplayRequest();
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
            } else if (array_key_exists('displayVIPTablesRequest', $_GET)) {
                handleVIPDisplayRequest();
            } else if (array_key_exists('displayVIPArtistsRequest', $_GET)) {
                handleVIPArtistDisplayRequest();
            } else if (array_key_exists('displayFloorsRequest', $_GET)) {
                handleFloorDisplayRequest();
            } else if (array_key_exists('displayArtsRequest', $_GET)) {
                handleArtDisplayRequest();
            }
        }
        disconnectFromDB();
    }

    if (isset($_POST['reset']) || isset($_POST['insertSubmit'])|| isset($_POST['insertNewValue'])|| isset($_POST['deleteOwner']) || isset($_POST['artistdisplay'])) {
        handlePOSTRequest();
    } else if (isset($_GET['display']) || isset($_GET['vipdisplay']) || isset($_GET['vipartistdisplay']) || isset($_GET['floordisplay']) || isset($_GET['artdisplay'])) {
        //echo"1";
        handleGETRequest();
    }
    ?>
