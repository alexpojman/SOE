<?php
	include("connect.php");

	mysql_query("DELETE FROM notes WHERE id = ".$_POST['noteid']." LIMIT 1") or die (mysql_error());
?>