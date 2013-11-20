<?php

include("connect.php");

$query = mysql_query("SELECT id FROM users WHERE email='".$_GET['email']."'") or die (mysql_error());
if(mysql_num_rows($query)!=0){
	echo json_encode($_GET['email']." is already used.");
}else{
	echo json_encode(true);
}

?>