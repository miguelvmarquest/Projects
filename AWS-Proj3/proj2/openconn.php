<?php
$dbhost = "appserver.di.fc.ul.pt";
$dbuser = "asw47139";
$dbpass = "mikeTondo95";
$dbname = "asw47139";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (mysqli_connect_error()){
	die("Database connection failed:".mysqli_connect_error());
}

?>
