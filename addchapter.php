<?php
	include("connect.php");

	mysql_query("INSERT INTO chapters (name, subtitle, projectid) VALUES ('".$_POST['name']."', '".$_POST['subtitle']."', '".$_POST['projectid']."')") or die (mysql_error());

	echo mysql_insert_id();
?>