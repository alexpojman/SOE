<?php
	include("connect.php");

	$result = mysql_query("SELECT username FROM users WHERE id=".$_POST["userid"]."") 
	or die (mysql_error());


		while($row = mysql_fetch_array($result))
		{
			echo $row["username"];
		}


?>