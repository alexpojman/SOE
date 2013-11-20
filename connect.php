<?php

$myServer = "localhost";
$myUser = "root";
$myPass = "ferret2391";
$myDB = "savadaba";

$dbhandle = mysql_connect($myServer, $myUser, $myPass)
  or die("Couldn't connect to SQL Server on $myServer");
  
mysql_select_db($myDB) or die(mysql_error());

?>