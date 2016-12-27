<?php
include("include/session.php");
if ($session->logged_in) {
	Global $database;
	if(!isset($_POST['randcheck'])) {$_POST['randcheck'] = 0; $_SESSION['rand'] = 1;}
	if(isset($_POST['keist'])&& $_POST['randcheck']==$_SESSION['rand'])
	{
		if($_POST['pav'] != "")
		$sql="INSERT INTO `tipaitiekejo`(`Pavadinimas`) VALUES ('{$_POST['pav']}')";
		$database->query($sql);
	}
	if($_GET["id"] != -1) 
	{
		$sql="SELECT * FROM `prekestiekejo` WHERE `prekestiekejo`.`id` = '{$_GET["id"]}'";
		$res = $database->query($sql);
		$preke = $res->fetch_array(); 
		$sql = "SELECT * FROM `tipaitiekejo`, `turi3` WHERE `turi3`.`fk_tipaiTiekejo`=`tipaitiekejo`.`id` && `turi3`.`fk_PrekesTiekejo` = '{$_GET["id"]}'";
		$ipatumai = $database->query($sql);
	}
	$sql = "SELECT * FROM `tipaitiekejo`";
	$savybes = $database->query($sql);
	if(!isset($_POST['randcheck'])) {$_POST['randcheck'] = 0; $_SESSION['rand'] = 1;}
	if(isset($_POST['ok'])&& $_POST['randcheck']==$_SESSION['rand'])
	{
		if($_GET["id"] == -1)$sql="INSERT INTO `prekestiekejo`(`prekesKodas`, `Pavadinimas`, `Galiojimas`, `Kaina`, `Kiekis`, `fk_TiekejoSandeliai`) VALUES ('{$_POST['kodas']}','{$_POST['pavadinimas']}','{$_POST['galiojimas']}','{$_POST['kaina']}','{$_POST['kiekis']}','{$_GET["san"]}')";
		else $sql = "UPDATE `prekestiekejo` SET `prekesKodas`='{$_POST['kodas']}',`Pavadinimas`='{$_POST['pavadinimas']}',`Galiojimas`='{$_POST['galiojimas']}',`Kaina`='{$_POST['kaina']}',`Kiekis`='{$_POST['kiekis']}',`fk_TiekejoSandeliai`='{$_GET["san"]}' WHERE `prekestiekejo`.`id`='{$_GET["id"]}'";
		$database->query($sql);
		$sql="DELETE FROM `turi3` WHERE `fk_PrekesTiekejo` = '{$_GET["id"]}'";
		$database->query($sql);
		foreach($_POST['keitimas'] as $key => $val) {
			$sql="INSERT INTO `turi3`(`fk_tipaiTiekejo`, `fk_PrekesTiekejo`) VALUES ('{$val}','{$_GET["id"]}')";
			$database->query($sql);
		}
		header("Location: editPrekesTiekejas.php?id={$_GET['san']}");
	}
	?>
    <html>
        <head>  
            <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8"/>
			<title>Drym Tym elektroninė parduotuvė</title>
			<link href="include/styles.css" rel="stylesheet" type="text/css" />
			<link rel="stylesheet"
					href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
			<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
			<script type="text/javascript" src="scripts/jquery-1.12.0.min.js"></script>
			<script type="text/javascript" src="scripts/datetimepicker/jquery.datetimepicker.full.min.js"></script>
			<script type="text/javascript" src="scripts/main.js"></script>
        </head >
		<style>
		table {
			border-collapse: collapse;
		}
		th{
			text-align: center;
		}
		</style>
        <body style="background-color:orange;">
            <table class="center" style="width:80%;"><tr><td>
				<center><img src="pictures/top.png"/></center>
				</td></tr>
				<tr><td> 
                        <?php
                        include("include/meniu.php");
                        ?>              
                        <table style="border-width: 2px; border-style: dotted;"><tr><td>
                                    Atgal į [<a href="index2.php">Pradžia</a>]
                                </td></tr></table>     
						<div class="container" style="text-align: center;color:green">
							<form method="POST" class="login">
								<?php
								$rand=rand();
								$_SESSION['rand']=$rand;
								?>
								<input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
								<Table style="width:70%">
								<tr>
									<td style="text-align:Right">Prekės kodas</td> 
									<td style="text-align:Left">
									<input name='kodas' id='kodas' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($preke))echo $preke['prekesKodas'];?>">
									</td>
								</tr>
								<tr>
									<td style="text-align:Right">Prekės pavadinimas</td> 
									<td style="text-align:Left">
									<input name='pavadinimas' id='pavadinimas' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($preke))echo $preke['Pavadinimas'];?>">
									</td>
								</tr>
								<tr>
									<td style="text-align:Right">Galiojimas iki</td> 
									<td style="text-align:Left">
									<input name='galiojimas' id='galiojimas' type='date' class="form-control input-sm" required="true" value="<?php if(isset($preke))echo $preke['Galiojimas'];?>">
									</td>
								</tr>
								<tr>
									<td style="text-align:Right">Prekės kaina</td> 
									<td style="text-align:Left">
									<input name='kaina' id='kaina' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($preke))echo $preke['Kaina'];?>">
									</td>
								</tr>
								<tr>
									<td style="text-align:Right">Vienetų skaičius</td> 
									<td style="text-align:Left">
									<input name='kiekis' id='kiekis' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($preke))echo $preke['Kiekis'];?>">
									</td>
								</tr>
								</Table>
								<br>
								<?php if($_GET["id"] != -1){?>
								<fieldset>
									<legend>Savybės</legend>
									<div class="childRowContainer">
										<div class="float-clear"></div>
										<?php if(!isset($ipatumai)) {?>
											<div class="childRow hidden">
												<select class="elementSelector" name="keitimas[]" disabled="disabled">
													<option value="">----------------------------------------</option>
													<?php
													$num = mysqli_num_rows($savybes);
													for ($j = 0; $j < $num; $j++) {
														$tipai = mysqli_result($savybes, $j);
														$selected = "";
														echo "<option{$selected} value='{$tipai['id']}'>{$tipai['Pavadinimas']}</option>";
													}
													?>
												</select>
												<a href="#" title="" class="removeChild">šalinti</a>
											</div>
											<div class="float-clear"></div>
											<?php } else {
												$set_num = mysqli_num_rows($ipatumai);
												if($set_num == 0) $set_num = 1;
												for ($i = 0; $i < $set_num; $i++) {
													?>
												<div class="childRow">
												<select class="elementSelector" name="keitimas[]">
													<option value="">----------------------------------------</option>
													<?php
													$id = mysqli_result($ipatumai, $i);
													$num = mysqli_num_rows($savybes);
													for ($j = 0; $j < $num; $j++) {
														$tipai = mysqli_result($savybes, $j);
														$selected = "";
														if(isset($id['fk_tipaiTiekejo']) && $id['fk_tipaiTiekejo'] == $tipai['id']) {
															$selected = " selected='selected'";
														}
														echo "<option{$selected} value='{$tipai['id']}'>{$tipai['Pavadinimas']}</option>";
													}
													?>
												</select>
												<a href="#" title="" class="removeChild">šalinti</a>
												</div>
												<div class="float-clear"></div>
											<?php
											}
										}?>
									</div>
									<p id="newItemButtonContainer">
										<a href="#" title="" class="addChild">Pridėti</a>
									</p>
								</fieldset>
								<br>
								<div>
									<input name='pav' id='pav' type='textbox-20'>
									<input type='submit' name='keist' value='Pridėti' class="btn btn-default">
								<div>
								<br>
								<?php }?>
								<div>
									<input type='submit' name='ok' value='Patvirtinti' class="btn btn-default">
								</div>
							</form>
						</div>
						<br>
                        <?php
                        include("include/footer.php");
                        ?>
                </td></tr>      
            </table>
        </body>
    </html>
    <?php
    //Jei vartotojas neprisijungęs, užkraunamas pradinis puslapis  
} else {
    header("Location: index2.php");
}
function mysqli_result($res, $row) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow; 
}
?>