<?php
include("include/session.php");
if ($session->logged_in) {
	
	Global $database;
	if(isset($_GET['ok']))
	{
		$sql="SELECT `id` FROM `prekestiekejo` WHERE `fk_TiekejoSandeliai`='{$_GET['id']}'";
		$prekes = $database->query($sql);
		$num = mysqli_num_rows($prekes);
		for ($j = 0; $j < $num; $j++) {
			$i = mysqli_result($prekes, $j, "id");
			$darb = "DELETE FROM `turi3` WHERE  `turi3`.`fk_PrekesTiekejo`  = '{$i}'";
			$database->query($darb);
			$sql = "DELETE FROM `prekestiekejo` WHERE `prekestiekejo`.`id` = '{$i}'";
			$database->query($sql);
		}
		$san = "DELETE FROM `sandeliaitiekejo` WHERE `id`='{$_GET['id']}'";
		$database->query($san);
		header("Location: tiekejo_sandelio_administravimas.php");
	}
	$tipas =$_GET['tip'];
	$id = $_GET['id'];
	if($tipas == 2)
	{
		$darb = "DELETE FROM `turi3` WHERE  `turi3`.`fk_PrekesTiekejo`  = '{$id}'";
		$database->query($darb);
		$sql = "DELETE FROM `prekestiekejo` WHERE `prekestiekejo`.`id` = '{$id}'";
		$database->query($sql);
		header("Location: tiekejo_sandelio_administravimas.php");
	}
	if($tipas == 3)
	{
		$now = date("Y/m/d");
		$darb = "UPDATE `tiekimokontraktas` SET `nutData`='{$now}' WHERE `sutartiesKodas`='{$id}'";
		$database->query($darb);
		header("Location: tiekimo_sutartis_administravimas.php");
	}
	if($tipas == 3)
	{
		$now = date("Y/m/d");
		$darb = "UPDATE `prekiusarasas` SET `atsauktas`='1' WHERE `uzsakymoKodas`='{$id}'";
		$database->query($darb);
		header("Location: tiekimo_sutartis_administravimas.php");
	}
	if($tipas == 4)
	{
		$now = date("Y/m/d");
		$darb = "UPDATE `prekiusarasas` SET `atsauktas`='1' WHERE `uzsakymoKodas`='{$id}'";
		$database->query($darb);
		header("Location: uzsakymo_administravimas.php");
	}
	?>
	
	<html>
<head>
<style>
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
    border: 1px solid #888;
    width: 80%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 0.4s;
    animation-name: animatetop;
    animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
    from {top:-300px; opacity:0} 
    to {top:0; opacity:1}
}

@keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
}

/* The Close Button */
.close {
    color: white;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.modal-header {
    padding: 2px 16px;
    background-color: #5cb85c;
    color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
    padding: 2px 16px;
    background-color: #5cb85c;
    color: white;
}
</style>
</head>
<body style="background-color:orange;">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <a class="close" href="tiekejo_sandelio_administravimas.php">&times;</a>
      <h2>Jūs bandote ištrinti sandėlį kureme gali būti prekių</h2>
    </div>
    <div class="center" style="width:100%; text-align: center;">
		<form>
			<?php
			$rand=rand();
			$_SESSION['rand']=$rand;
			?>
			<input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
			<div>
				<input type='hidden' name='id'  value="<?php echo $id; ?>">
				<input type='submit' name='ok' value='Patvirtinti' class="btn btn-default">
			</div>
		</form>
    </div>
  </div>

</body>
</html>

<?php
} else {
	echo "rado";
    header("Location: index2.php");
}
function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow[$field]; 
}?>

