<?php
	include("connect.php");

	mysql_query("DELETE FROM cookies WHERE userid = ".$_POST['userid']." AND token ='".md5($_POST['token'])."' LIMIT 1") or die (mysql_error());
	
?>