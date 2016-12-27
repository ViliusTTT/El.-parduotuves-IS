<?php
	include("include/session.php");
	Global $database;
	$sql = "SELECT `name` as name, `score` as score FROM `leaderbord`";
	$res = $database->query($sql);
	echo "\n";
	$num_rows = mysqli_num_rows($res);
	for ($i = 0; $i < $num_rows; $i++) {
		$suma['name'] = mysqli_result($res, $i, "name");
		$suma['score'] = mysqli_result($res, $i, "score");
        echo json_encode($suma) . "\n";
	}
function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array();
    return $datarow[$field]; 
} 
?>