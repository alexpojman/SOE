<?php
	include("connect.php");

	mysql_query("INSERT INTO users (username, email, password) 
		VALUES ('".$_POST['username']."', '".$_POST['email']."', '".md5($_POST['password'])."')") 
	or die (mysql_error());

	$userid = mysql_insert_id();
	
	$random = rand(0, 9999999999999);
	setcookie("auth", $userid."-".$random, time()+60*60*24*30, "/"); //Create cookie with userid and randomized number
	mysql_query("INSERT INTO cookies (userid, token) VALUES (".$userid.", '".md5($random)."')"); //Link cookie to user in database
	echo $random; //Return the token

	echo $userid;

?>