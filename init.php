<?php
        /**
         * Credit to oracle, link below
         * https://docs.oracle.com/database/121/TDPPH/ch_three_db_access_class.htm#TDPPH147
         * ac_cred.inc.php: Secret Connection Credentials for a database class
         * @package Oracle
         */
        
        
        require('ac_db.inc.php');
        // this sets up a connection to the oracle database. The names don't really matter
        $db = new \Oracle\Db("test_db", "Mine");
        // sql to make a demo table and insert one value
        $sql = file_get_contents('initialize.sql');
        // actually runs the sql code above
        $db->execute($sql, "create initial tables");
?>