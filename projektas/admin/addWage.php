<?php
//Operacijos temos rezervacijos vykdymui
include("include/session.php");
$id = $_GET['darbuotojas'];
$suma=$_GET['suma'];
$conn = mysqli_connect('db.if.ktu.lt', 'donvos', 'joh8Ida8taishahj', 'donvos') or die('Connection error!'); 
define('TIMEZONE', 'Europe/Vilnius');
date_default_timezone_set(TIMEZONE);
$date = date('Y/m/d H:i:s');
$query2=mysqli_query($conn, "INSERT INTO algolapis(kiekis,data,id,fk_Darbuotojasid_Darbuotojas)VALUES ($suma,'$date',0,$id)");
header("Location: algolapiuAdministravimas.php");
?>