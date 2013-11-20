<?php

include("connect.php");

$query = mysql_query("SELECT id FROM users WHERE username='".$_GET['username']."'") or die (mysql_error());
if(mysql_num_rows($query)!=0){
	echo json_encode($_GET['username']." is not available.");
}else{
	echo json_encode(true);
}

?>