<?php
	include("connect.php");

	mysql_query("DELETE FROM projects WHERE id = ".$_POST['id']." LIMIT 1") or die (mysql_error());
	mysql_query("DELETE FROM chapters WHERE projectid = ".$_POST['id']) or die (mysql_error());
	mysql_query("DELETE FROM notes WHERE projectid = ".$_POST['id']) or die (mysql_error());
?>