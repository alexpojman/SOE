<?php
	include("connect.php");

	mysql_query("UPDATE notes SET content='".$_POST['content']."' WHERE id = ".$_POST['noteid']."") or die (mysql_error());
	echo $_POST['content']." - ".$_POST['noteid'];
?>