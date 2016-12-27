<?php
	include("include/session.php");
	Global $database;
	$name = mysql_escape_string($_REQUEST["name"]);
	$score = mysql_escape_string($_REQUEST["score"]);
	$sql = "INSERT INTO `leaderbord`(`name`, `score`) VALUES ('{$name}','{$score}')";
	$database->query($sql);
?>