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

    function handleLoginRequest() {
        global $db_conn;
        $loginEmail = $_GET['loginEmail'];
        
        session_save_path("/home/m/minesher/public_html/project_q2z1b_r0x2b_y5v1r");
        //echo session_save_path();
        session_start(); # start session handling.
        $_SESSION['current_user']=$loginEmail;
        //exit();
        
        $fetchName = executePlainSQL("SELECT FirstName FROM ArtOwner WHERE Email='" . $loginEmail . "'");
        $userName = oci_fetch_row($fetchName);
        echo "welcome to the gallery ";
        echo "$userName[0]";

    }

    function printMyArt($result) { //prints results from a select statement
        
        echo "<table>";
        echo "<tr><th>title</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td></tr>"; //or just use "echo $row[0]"
        }

        echo "</table>";
    }

    function handleSeeMyArtRequest() {
        global $db_conn;
        $userEmail = NULL;
        session_save_path("/home/m/minesher/public_html/project_q2z1b_r0x2b_y5v1r");
        session_start(); # start session handling again.
        //echo $_SESSION['current_user'];
        $userEmail = $_SESSION['current_user'];
        //exit();
        //echo "$userEmail";
        $radioSelection = $_GET['select_art_type'];

        if ($radioSelection == "painting"){
            $fetchMyArt = executePlainSQL("SELECT a.Title FROM ArtOwner ao, Art3 a, Painting p WHERE a.OwnerID = ao.OwnerID AND a.IdentificationNumber = p.IdentificationNumber AND Email='" . $userEmail . "'");
        } else if ($radioSelection == "sculpture"){
            $fetchMyArt = executePlainSQL("SELECT a.Title FROM ArtOwner ao, Art3 a, Sculpture s WHERE a.OwnerID = ao.OwnerID AND a.IdentificationNumber = s.IdentificationNumber AND Email='" . $userEmail . "'");
        } else {
            $fetchMyArt = executePlainSQL("SELECT a.Title FROM ArtOwner ao, Art3 a WHERE a.OwnerID = ao.OwnerID AND ao.Email='" . $userEmail . "'");
        }
        //$fetchMyArt = executePlainSQL("SELECT a.Title FROM ArtOwner ao, Art3 a WHERE a.OwnerID = ao.OwnerID AND Email='" . $userEmail . "'");
        echo "<br>List of art I own: <br>";
        echo "$radioSelection";
        printMyArt($fetchMyArt);
    }

    function handleSeeMyFeesRequest() {
        global $db_conn;
        $userEmail = NULL;
        session_save_path("/home/m/minesher/public_html/project_q2z1b_r0x2b_y5v1r");
        session_start(); # start session handling again.
        //echo $_SESSION['current_user'];
        $userEmail = $_SESSION['current_user'];
        //exit();
        //echo "$userEmail";

        // create a view where art is labeled with it's medium type. Only contains art owned by logged in owner.
        executePlainSQL("CREATE VIEW myArt(artID, Medium) AS 
                                        SELECT a.IdentificationNumber, 'N/A' as Medium
                                        FROM ArtOwner ao, Art3 a, Sculpture s, Painting p
                                        WHERE a.OwnerID = ao.OwnerID AND a.IdentificationNumber <> s.IdentificationNumber AND a.IdentificationNumber <> p.IdentificationNumber AND ao.Email='" . $userEmail . "'

                                        UNION 
                                        SELECT a.IdentificationNumber, 'Sculpture' as Medium
                                        FROM ArtOwner ao, Art3 a, Sculpture s
                                        WHERE a.OwnerID = ao.OwnerID AND a.IdentificationNumber = s.IdentificationNumber AND ao.Email='" . $userEmail . "'
                                        
                                        UNION 
                                        SELECT a.IdentificationNumber, 'Painting' as Medium
                                        FROM ArtOwner ao, Art3 a, Painting p
                                        WHERE a.OwnerID = ao.OwnerID AND a.IdentificationNumber = p.IdentificationNumber AND ao.Email='" . $userEmail . "' 
                                         ");
        
        $fetchMyFees = executePlainSQL("SELECT ma.Medium, SUM(a.ExhibitionFee)
                                        FROM Art3 a, myArt ma
                                        WHERE a.IdentificationNumber = ma.IdentificationNumber
                                        GROUP BY ma.Medium");

        //printMyFees($fetchMyFees);
    }

    function handleLogoutRequest(){
        session_save_path("/home/m/minesher/public_html/project_q2z1b_r0x2b_y5v1r");

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