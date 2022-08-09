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
    $current_user = NULL; // person who logs in

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

        $r = OCI_Execute($statement, OCI_DEFAULT);
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



    function handleLoginRequest() {
        global $db_conn;
        $loginEmail = $_GET['loginEmail'];  // gets the entered email
        //echo $loginEmail;

        session_save_path("/home/t/tammykim/public_html");
        //echo session_save_path();
        session_start(); # start session handling.
        $_SESSION['current_user']=$loginEmail;
        //exit();

        $fetchName = executePlainSQL("SELECT FirstName FROM ArtOwner WHERE Email='$loginEmail'");  // get FirstName column from ArtOwner with loginEmail... should only be one row since email is unique.
        $userName = OCI_Fetch_Array($fetchName);
        echo "Welcome to the gallery ";
        echo "$userName[0]";
    }

    function printMyArt($result, $numAttributes) { //prints results from a select statement

        echo "<table>";
        if ($numAttributes == 1){
            echo "<tr><th>title</th></tr>";
        } else if ($numAttributes == 2){
            if (!empty($_GET['attributeYear'])){
                $columnName = "year";
            } else {
                $columnName = "price";
            }
            echo "<tr><th>Title</th><th>" . $columnName . "</th></tr>";
        }  else if ($numAttributes == 3){
            echo "<tr><th>Title</th><th>Year</th><th>Price</th></tr>";
        }

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            if ($numAttributes == 1){
                echo "<tr><td>" . $row[0] . "</td></tr>";
            }
            else if ($numAttributes == 2){
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }
            else if ($numAttributes == 3){
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . " </td><td>" . $row[2] . "</td></tr>";
            }
        }

        echo "</table>";

    }

    function handleSeeMyArtRequest() {
        //echo"in function";
        global $db_conn;
        $userEmail = NULL;
        session_save_path("/home/t/tammykim/public_html");
        session_start(); # start session handling again.
        //echo $_SESSION['current_user'];
        $userEmail = $_SESSION['current_user'];
        //exit();
        //echo "$userEmail";
        $radioSelection = $_GET['select_art_type'];
        //choose which attributes get selected
        $year = "";
        $price = "";
        $numAttributes = 1;
        if (!empty($_GET['attributeYear'])){
            $year = $_GET['attributeYear'];
            $numAttributes++;
        }

        if (!empty($_GET['attributePrice'])){
            $price = $_GET['attributePrice'];
            $numAttributes++;
        }
        //specify WHERE condition
        $whereCondition = "AND ";
        $whereAttribute = "a." . $_GET['where_attribute'];

        $whereCondition = $whereCondition . $whereAttribute . $_GET['where_operation'] . $_GET['where_value'];
        if ($radioSelection == "Paintings"){
            $fetchMyArt = executePlainSQL("SELECT a.Title " . $year . " " . $price . " FROM ArtOwner ao, Art3 a, Painting p WHERE a.OwnerID = ao.OwnerID AND a.IdentificationNumber = p.IdentificationNumber AND Email='" . $userEmail . "'" . $whereCondition);
        } else if ($radioSelection == "Sculptures"){
            $fetchMyArt = executePlainSQL("SELECT a.Title " . $year . " " . $price . " FROM ArtOwner ao, Art3 a, Sculpture s WHERE a.OwnerID = ao.OwnerID AND a.IdentificationNumber = s.IdentificationNumber AND Email='" . $userEmail . "'" . $whereCondition);
        } else {
            $fetchMyArt = executePlainSQL("SELECT a.Title " . $year . " " . $price . " FROM ArtOwner ao, Art3 a WHERE a.OwnerID = ao.OwnerID AND ao.Email='" . $userEmail . "'" . $whereCondition);
        }
        echo "<br>List of art I own: <br>";
        // echo "$radioSelection";
        printMyArt($fetchMyArt, $numAttributes);
    }

    function printMyFees($result) { //prints results from a select statement
        echo "<br>Fee to exhibit my art, grouped by medium<br>";
        echo "<table>";
        echo "<tr><th>Medium</th><th>Total Fees</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
        }

        echo "</table>";
    }

    function handleSeeMyFeesRequest() {
        global $db_conn;
        $userEmail = NULL;
        session_save_path("/home/t/tammykim/public_html");
        session_start(); # start session handling again.
        //echo $_SESSION['current_user'];
        $userEmail = $_SESSION['current_user'];
        //exit();
        //echo "$userEmail";

        executePlainSQL("drop view myArt");

        // create a view where art is labeled with it's medium type. Only contains art owned by logged in owner.
        executePlainSQL("CREATE VIEW myArt(artID, Medium) AS


                                        SELECT a.IdentificationNumber, 'Sculpture' as Medium
                                        FROM ArtOwner ao, Art3 a, Sculpture s
                                        WHERE a.OwnerID = ao.OwnerID AND a.IdentificationNumber = s.IdentificationNumber AND ao.Email='" . $userEmail . "'

                                        UNION
                                        SELECT a.IdentificationNumber, 'Painting' as Medium
                                        FROM ArtOwner ao, Art3 a, Painting p
                                        WHERE a.OwnerID = ao.OwnerID AND a.IdentificationNumber = p.IdentificationNumber AND ao.Email='" . $userEmail . "'
                                        UNION

                                        SELECT a.IdentificationNumber, 'N/A' as Medium
                                        FROM ArtOwner ao, Art3 a
                                        WHERE a.OwnerID = ao.OwnerID AND ao.Email='" . $userEmail . "'
                                        AND a.IdentificationNumber NOT IN (SELECT s.IdentificationNumber FROM Sculpture s UNION SELECT p.IdentificationNumber FROM Painting p)

                                        ");

        $fetchMyFees = executePlainSQL("SELECT m.Medium, SUM(a.ExhibitionFee)
                                        FROM Art3 a, myArt m
                                        WHERE a.IdentificationNumber = m.ArtID
                                        GROUP BY m.Medium");

        printMyFees($fetchMyFees);
    }

    function handleLogoutRequest(){
        session_save_path("/home/t/tammykim/public_html");

        session_start(); # start session handling.
        //echo $_SESSION['current_user'];
        $_SESSION['current_user']=NULL;
        echo "You have been logged out";
        exit();
    }

    function handlePOSTRequest() {
        if (connectToDB()){
            if (array_key_exists('resetTablesRequest', $_POST)) {
               handleResetRequest();
                }
        }

        disconnectFromDB();

    }

    function handleGETRequest() {
        if (connectToDB()) {
            if (array_key_exists('loginRequest', $_GET)) {
                //echo"2";
                handleLoginRequest();
            } else if (array_key_exists('seeMyArtRequest', $_GET)) {
                //echo"2";
                handleSeeMyArtRequest();
            } else if (array_key_exists('logoutRequest', $_GET)) {
                //echo"2";
                handleLogoutRequest();
            } else if (array_key_exists('seeMyFeesRequest', $_GET)) {
                //echo"2";
                handleSeeMyFeesRequest();
            }
        }
        disconnectFromDB();
    }

    if (isset($_POST['reset']) ) {

        handlePOSTRequest();
    } else if (isset($_GET['login']) || isset($_GET['view']) || isset($_GET['logout']) || isset($_GET['sum_fees'])) {
        //echo"get";
        handleGETRequest();
    }
    ?>
