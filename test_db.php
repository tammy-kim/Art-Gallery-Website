

<html>
    
<head>
    <title>Testing db</title>
    <link rel="stylesheet" href="style.css">
</head>

    <body>

        <?php
        /**
         * Credit to oracle, link below
         * https://docs.oracle.com/database/121/TDPPH/ch_three_db_access_class.htm#TDPPH147
         * ac_cred.inc.php: Secret Connection Credentials for a database class
         * @package Oracle
         */
        
        // test_db.php
        
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