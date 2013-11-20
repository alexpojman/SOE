<?php
	include("connect.php");

	$result = mysql_query("SELECT id FROM users WHERE username='".$_POST["username"]."' && password='".md5($_POST["password"])."'") 
	or die (mysql_error());

	if  (mysql_num_rows($result)>0)
	{
		while($row = mysql_fetch_array($result))
		{
			$random = rand(0, 9999999999999);
			setcookie("auth", $row["id"]."-".$random, time()+60*60*24*30, "/"); //Create cookie with userid and randomized number
			mysql_query("INSERT INTO cookies (userid, token) VALUES (".$row["id"].", '".md5($random)."')"); //Link cookie to user in database
			echo $random; //Return the token
		}
	}else{
		echo "";
	}

?>