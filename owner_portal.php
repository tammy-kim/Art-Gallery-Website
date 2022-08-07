<?php
    /**
     * Credit to Test Oracle file for UBC CPSC304 2018 Winter Term 1
     * Created by Jiemin Zhang
     * Modified by Simona Radu
     * Modified by Jessica Wong (2018-06-22)
     */
    

    $success = True; //keep track of errors so it redirects the page only if there are no errors
    $db_conn = NULL; // edit the login credentials in connectToDB()
    $show_debug_alert_messages = True; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())
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
        echo"heelo";
    }

    function handlePOSTRequest() {
        if (connectToDB()){
            if (array_key_exists('resetTablesRequest', $_POST)) {
               handleResetRequest();
        }


        disconnectFromDB();
    
    }

    function handleGETRequest() {
        if (connectToDB()) {
            if (array_key_exists('loginRequest', $_GET)) {
                //echo"2";
                handleLoginRequest();
            } 
        }
        disconnectFromDB();
    }

    if (isset($_POST['reset']) ) {
        
        handlePOSTRequest();
    } else if (isset($_GET['login']) ) {
        echo"get";
        handleGETRequest();
    }

?>