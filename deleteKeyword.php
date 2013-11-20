<?php
	include("connect.php");
    // Later add feature to remove keyword, and do noteid and project perhaps?
	mysql_query("DELETE FROM keywords WHERE noteid = ".$_POST['noteid']."") or die (mysql_error());
?>