<?php
	include("connect.php");

	mysql_query("DELETE FROM sharedprojects WHERE projectid = ".$_POST['project']." AND userid = ".$_POST['user']." LIMIT 1") or die (mysql_error());

?>