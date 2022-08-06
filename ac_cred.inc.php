<?php
 
/**
 * Credit to oracle, link below
 * https://docs.oracle.com/database/121/TDPPH/ch_three_db_access_class.htm#TDPPH147
 * ac_cred.inc.php: Secret Connection Credentials for a database class
 * @package Oracle
 */
 
/**
 * DB user name
 */
define('SCHEMA', 'ora_minesher');
 
/**
 * DB Password.
 *
 * Note: In practice keep database credentials out of directories
 * accessible to the web server.
 */
define('PASSWORD', 'a28495142');
 
/**
 * DB connection identifier
 */
define('DATABASE', 'dbhost.students.cs.ubc.ca:1522/stu');
 
/**
 * DB character set for returned data
 */
define('CHARSET', 'UTF8');
 
/**
 * Client Information text for DB tracing
 */
define('CLIENT_INFO', 'Animal Art Gallery');
 
?>