<?php
define('DB_SERVER','Localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// Change this setting after creating DB
define('DB_NAME', 'BEAssignment');

// Use the following define for firstly creating a new database from php code
// define('DB_NAME', '');


$conn=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
// var_dump($conn);

if($conn == false)
{
    die('ERROR: Could not connect!'.mysqli_connect_error());
}
else
{
   echo "Connection Successfully <br> ";
}
?>

