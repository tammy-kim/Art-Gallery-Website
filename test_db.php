

<html>
    
<head>
    <title>Art Owner Page</title>
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
        
        $db = new \Oracle\Db("test_db", "Mine");
        $a = "CREATE TABLE demoTable (id int PRIMARY KEY, name char(30))";
        $db->execute($a, "create table");
        $b = "INSERT INTO demoTable VALUES (1, 'meen')";
        $db->execute($b, "insert me");
        $sql = "SELECT id, name FROM demoTable ORDER BY id";
        $res = $db->execFetchAll($sql, "Query Example");
        // echo "<pre>"; var_dump($res); echo "</pre>\n";
        
        echo "<table border='1'>\n";
        echo "<tr><th>id</th><th>name</th></tr>\n";
        foreach ($res as $row) {
            $id = htmlspecialchars($row['ID'], ENT_NOQUOTES, 'UTF-8');
            $name   = htmlspecialchars($row['NAME'], ENT_NOQUOTES, 'UTF-8');
            echo "<tr><td>$id</td><td>$name</td></tr>\n";
        }
        echo "</table>";
        
        ?>
    </body>
</html>