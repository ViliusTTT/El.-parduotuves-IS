<?php
include("include/session.php");
if ($session->logged_in) {
	$kodas = $_GET['kodas'];
	$leidimas = $_GET['leid'];
	if($_GET["id"] != -1) 
	{
		$sql="SELECT * FROM `prekiusarasas` WHERE  `prekiusarasas`.`uzsakymoKodas`='{$_GET["id"]}'";
		$res = $database->query($sql);
		$uzsakymoinfo = $res->fetch_array(); 
		$sql="SELECT * FROM `pruzsakymas` WHERE `fk_PrekiuSarasasid_PrekiuSarasas`='{$uzsakymoinfo['uzsakymoKodas']}'";
		$prsarasas = $database->query($sql);
	}
	if(isset($_POST['ok']) || $leidimas == 0)
	{
		$patv = 1;
		if($leidimas == 1)$post = $_POST;
		else $post = $uzsakymoinfo;
		$sql = "SELECT * FROM `pruzsakymas` WHERE `fk_PrekiuSarasasid_PrekiuSarasas`='{$post['uzsakymoKodas']}'";
		$uzsakytos = $database->query($sql);
		$sql = "SELECT `prekestiekejo`.* FROM `prekestiekejo`, `sandeliaitiekejo` WHERE `sandeliaitiekejo`.`fk_Tiekejas`='{$post['fk_Tiekejas']}' && `sandeliaitiekejo`.`id`=`prekestiekejo`.`fk_TiekejoSandeliai`";
		$prekes = $database->query($sql);
	}
	$sql ="SELECT  `tiekejas`.`imones_kodas`,`tiekejas`.`Pavadinimas` FROM `tiekimokontraktas`, `tiekejas` WHERE `tiekejas`.`imones_kodas`=`tiekimokontraktas`.`fk_Tiekejas` &&
	`tiekimokontraktas`";
	if($leidimas == 1) $sql .=".`fk_Imone`='{$kodas}'";
	else $sql .=".`fk_Tiekejas`='{$kodas}'";
	$sql.="Group by  `tiekejas`.`imones_kodas`";
	$tiekejai = $database->query($sql);
	$rado ="SELECT * FROM `sandeliai` WHERE ";
	if($leidimas == 1) $rado .="`fk_Imoneid_Imone`='{$kodas}'";
	else $rado .="`id`='{$uzsakymoinfo['fk_ImonesSandelys']}'";
	$sandeliai = $database->query($rado);
	if(!isset($_POST['randcheck'])) {$_POST['randcheck'] = 0; $_SESSION['rand'] = 1;}
	if(isset($_POST['pat']))
	{
		$now = date("Y/m/d");
		$sql="DELETE FROM `pruzsakymas` WHERE `fk_PrekiuSarasasid_PrekiuSarasas`='{$_POST['uzsakymoKodas']}'";
		$database->query($sql);
		$sql="DELETE FROM `prekiusarasas` WHERE `uzsakymoKodas`='{$_POST['uzsakymoKodas']}'";
		$database->query($sql);
		echo $_POST['fk_Tiekejas'];
		echo $_POST['fk_ImonesSandelys'];
		$sql="INSERT INTO `prekiusarasas`(`uzsakymoPatvirtinimas`, `uzsakymoData`, `atsauktas`, `norimasPristatymas`, `uzsakymoKodas`, `fk_Tiekejas`, `fk_ImonesSandelys`) 
		VALUES ('1','{$now}','0','{$_POST['norimasPristatymas']}','{$_POST['uzsakymoKodas']}','{$_POST['Tiekejas']}','{$_POST['ImonesSandelys']}')";
		$database->query($sql);
		$result = count($_POST['prekiuKiekis']);
		for ($j = 0; $j < $result; $j++) {
			$sql="INSERT INTO `pruzsakymas`(`prekiuKiekis`, `fk_Preke`, `fk_PrekiuSarasasid_PrekiuSarasas`) VALUES ('{$_POST['prekiuKiekis'][$j]}','{$_POST['prekes'][$j]}','{$_POST['uzsakymoKodas']}')";
			$database->query($sql);
		}
		header("Location: uzsakymo_administravimas.php");
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
		</style>
        <body style="background-color:orange;">
            <table class="center" style="width:80%;"><tr><td>
				<center><img src="pictures/top.png"/></center>
				</td></tr>
				<tr><td> 
				<?php
                include("include/meniu.php");
                ?>              
                <table style="border-width: 2px; border-style: dotted;">
					<tr><td>
						Atgal į [<a href="index2.php">Pradžia</a>]
					</td></tr>
				</table>
				<br>
				<div class="container" style="text-align: center;color:green">
					<form method="POST" class="login">
						<?php
						$rand=rand();
						$_SESSION['rand']=$rand;
						?>
						
						<input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
						<input type="hidden" name="ImonesSandelys" value="<?php if(isset($post['fk_ImonesSandelys']))echo $post['fk_ImonesSandelys']; ?>" />
						<input type="hidden" name="Tiekejas" value="<?php if(isset($post['fk_Tiekejas']))echo $post['fk_Tiekejas']; ?>"/>
						<Table style="width:70%">
							<tr>
								<th style="text-align:Right">Užsakymo numeris </th> 
								<th style="text-align:Left">
								<input name='uzsakymoKodas' id='uzsakymoKodas'  type='textbox' class="form-control input-sm" required="true" value="<?php if(isset($uzsakymoinfo))echo $uzsakymoinfo['uzsakymoKodas'];?>">
								</th>
							</tr>
							<tr>
								<td style="text-align:Right">Pageidaujama pristatymo data</td> 
								<td style="text-align:Left">
								<input name='norimasPristatymas' id='norimasPristatymas'  type='date' class="form-control input-sm" required="true" value="<?php if(isset($uzsakymoinfo))echo $uzsakymoinfo['norimasPristatymas'];?>">
								</td>
							</tr>
							<tr>
								<td style="text-align:Right">Tiekejas</td> 
								<td style="text-align:Left">
									<select class="elementSelector" name="fk_Tiekejas" id="tiek">
										<option value="">----------------------------------------</option>
										<?php
										$num = mysqli_num_rows($tiekejai);
										for ($j = 0; $j < $num; $j++) {
											$tipai = mysqli_result($tiekejai, $j);
											$selected = "";
											if(isset($uzsakymoinfo['fk_Tiekejas']) && $tipai['imones_kodas'] == $uzsakymoinfo['fk_Tiekejas']) $selected = " selected='selected'";
											echo "<option{$selected} value='{$tipai['imones_kodas']}'>{$tipai['Pavadinimas']} {$tipai['imones_kodas']}</option>";
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td style="text-align:Right">Sandėlys</td> 
								<td style="text-align:Left">
									<select class="elementSelector" name="fk_ImonesSandelys" id="sand">
										<option value="">----------------------------------------</option>
										<?php
										$num = mysqli_num_rows($sandeliai);
										for ($j = 0; $j < $num; $j++) {
											$tipai = mysqli_result($sandeliai, $j);
											$selected = "";
											if(isset($uzsakymoinfo['fk_ImonesSandelys']) && $tipai['id'] == $uzsakymoinfo['fk_ImonesSandelys']) $selected = " selected='selected'";
											echo "<option{$selected} value='{$tipai['id']}'>{$tipai['Miestas']} {$tipai['Adresas']}</option>";
										}
										?>
									</select>
								</td>
							</tr>
						</Table>
						<div>
							<input type='<?php if($leidimas == 0 || isset($patv)) echo "hidden"; else echo "submit"?>' name="ok" value='Saugoti' class="btn btn-default">
						</div>
						<?php if ((isset($_POST['ok']) || $leidimas == 0 ) && (!isset($uzsakymoinfo) || ($uzsakymoinfo['atsauktas'] == 0 && $uzsakymoinfo['pristatymoData'] == ""))){?>
						<fieldset>
									<legend>Prekės</legend>
									<div class="childRowContainer">
										<div class="float-clear"></div>
										<?php if(!isset($uzsakytos)) {?>
											<div class="childRow hidden">
												<input style="width:10%"name='prekiuKiekis[]' id='prekiuKiekis'  type='textbox-20' class="elementSelector" required="true" value="">
												<select class="elementSelector" name="prekes[]" disabled="disabled">
													<option value="">----------------------------------------</option>
													<?php
													$num = mysqli_num_rows($prekes);
													for ($j = 0; $j < $num; $j++) {
														$tipai = mysqli_result($prekes, $j);
														$selected = "";
														echo "<option{$selected} value='{$tipai['id']}'>{$tipai['Pavadinimas']} {$tipai['prekesKodas']}</option>";
													}
													?>
												</select>
												<a href="#" title="" class="removeChild">šalinti</a>
											</div>
											<div class="float-clear"></div>
											<?php } else {
												$set_num = mysqli_num_rows($uzsakytos);
												if($set_num == 0) $set_num = 1;
												for ($i = 0; $i < $set_num; $i++) {
													$id = mysqli_result($uzsakytos, $i);
													?>
												<div class="childRow">
												<input style="width:10%"name='prekiuKiekis[]' id='prekiuKiekis'  type='textbox-20' class="elementSelector" required="true" value="<?php echo $id['prekiuKiekis']?>">
												<select class="elementSelector" name="prekes[]" id='prekes'>
													<option value="">----------------------------------------</option>
													<?php
													$num = mysqli_num_rows($prekes);
													for ($j = 0; $j < $num; $j++) {
														$tipai = mysqli_result($prekes, $j);
														$sql = "SELECT SUM(`prekiuKiekis`) as kiek FROM `pruzsakymas`, `prekiusarasas` WHERE `fk_Preke`='{$tipai['id']}' && `fk_PrekiuSarasasid_PrekiuSarasas`=`prekiusarasas`.`uzsakymoKodas` && `prekiusarasas`.`atsauktas`=0 && `prekiusarasas`.`pristatymoData` IS NULL";
														$row = $database->query($sql);
														$uzs = $row->fetch_array();
														$skirt = $tipai['Kiekis']-$uzs['kiek'];
														$selected = "";
														if(isset($id['fk_Preke']) && $id['fk_Preke'] == $tipai['id']) {
															$selected = " selected='selected'";
														}
														echo "<option{$selected} value='{$tipai['id']}'>{$tipai['Pavadinimas']} {$tipai['prekesKodas']} {$skirt}</option>";
													}
													?>
												</select>
												<?php if($leidimas == 1) {?><a href="#" title="" class="removeChild">šalinti</a><?php }?>
												</div>
												<div class="float-clear"></div>
											<?php
											}
										}?>
									</div>
									<p id="newItemButtonContainer">
										<?php if($leidimas == 1) {?><a href="#" title="" class="addChild">Pridėti</a><?php }?>
									</p>
						</fieldset>
						<?php }?>
						<div>
							<input type='<?php if(!isset($patv)) echo "hidden"; else echo "submit"?>' name="<?php if(!isset($patv)) echo "hidden"; else echo "pat"?>" value='Saugoti' class="btn btn-default">
						</div>
					</form>
				</div>
				<script type="text/javascript">
				function uzdarymas() {
					document.getElementById("uzsakymoKodas").readOnly = true;
					document.getElementById("norimasPristatymas").readOnly = true;
					document.getElementById("sand").disabled = true;
					document.getElementById("tiek").disabled = true;
					document.getElementById('uzsakymoKodas').value = "<?php echo $post['uzsakymoKodas'];?>";
					document.getElementById("norimasPristatymas").value = "<?php echo $post['norimasPristatymas'];?>";
					document.getElementById("sand").value = "<?php echo $post['fk_ImonesSandelys'];?>";
					document.getElementById("tiek").value = "<?php echo $post['fk_Tiekejas'];?>";
				}
				function id() {
					document.getElementById("uzsakymoKodas").readOnly = true;
				}
				function enable() {
					document.getElementById("sand").disabled = false;
					document.getElementById("tiek").disabled = false;
				}
				<?php
				if($_GET["id"] != -1) echo "id();";
				if(isset($patv)) echo "uzdarymas();";
				if(isset($_POST['pat'])) echo "enable()";
				?>
				</script>
				<br>
                <?php include("include/footer.php"); ?>
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