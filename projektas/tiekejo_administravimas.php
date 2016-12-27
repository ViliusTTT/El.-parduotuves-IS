<?php
include("include/session.php");
if ($session->logged_in) {
	Global $database;
	$user = $session->username;
	$sql = "SELECT `tiekejas`.*, `tiekejoatstovas`.`redagavimas`
		FROM `tiekejas`, `tiekejoatstovas`, `klientas`
		WHERE `tiekejas`.`imones_kodas` = `tiekejoatstovas`.`fk_Tiekejas`&&
		`tiekejoatstovas`.`fk_Darbuotojasid_Darbuotojas` = `klientas`.`slapyvardis` &&
		`klientas`.`slapyvardis` = '{$user}'";
	$raw = $database->query($sql);
	$imone = $raw->fetch_array();
	$tur = mysqli_num_rows($raw);
	if($tur == 0) $admin = 1;
	
	$sql = "SELECT `klientas`.*, `tiekejoatstovas`.`redagavimas`
	FROM `tiekejas`, `tiekejoatstovas`, `klientas`
	WHERE `tiekejas`.`imones_kodas` = `tiekejoatstovas`.`fk_Tiekejas`&&
	`tiekejoatstovas`.`fk_Darbuotojasid_Darbuotojas` = `klientas`.`slapyvardis` &&
	`tiekejas`.`imones_kodas`='{$imone['imones_kodas']}'";
	$darbuotojiai = $database->query($sql);
	$sql = "SELECT * FROM `klientas`";
	$klientai = $database->query($sql);
	if(!isset($_POST['randcheck'])) {$_POST['randcheck'] = 0; $_SESSION['rand'] = 1;}
	if(isset($_POST['ok'])&& $_POST['randcheck']==$_SESSION['rand'])
	{
		if(!isset($admin))$sql="UPDATE `tiekejas` SET `Pavadinimas`='{$_POST['Pavadinimas']}',`Ikurimolaikas`='{$_POST['Ikurimolaikas']}',`miestas`='{$_POST['miestas']}',`gatve`='{$_POST['gatve']}',`telefonoNumeris`='{$_POST['telefonoNumeris']}' WHERE `imones_kodas`='{$_POST['imones_kodas']}'";
		else $sql="INSERT INTO `tiekejas`(`imones_kodas`, `Pavadinimas`, `Ikurimolaikas`, `miestas`, `gatve`, `telefonoNumeris`) VALUES ('{$_POST['imones_kodas']}','{$_POST['Pavadinimas']}','{$_POST['Ikurimolaikas']}','{$_POST['miestas']}','{$_POST['gatve']}','{$_POST['telefonoNumeris']}')";
		$database->query($sql);
		$sql="DELETE FROM `tiekejoatstovas` WHERE `fk_Tiekejas`='{$_POST['imones_kodas']}'";
		$database->query($sql);
		$result = count($_POST['keitimas']);
		for ($j = 0; $j < $result; $j++) {
			$red = 0;
			if(isset($_POST['redagavimas'][$j])) $red = 1;
			$sql="INSERT INTO `tiekejoatstovas`(`redagavimas`, `fk_Darbuotojasid_Darbuotojas`, `fk_Tiekejas`) VALUES ('{$red}','{$_POST['keitimas'][$j]}','{$_POST['imones_kodas']}')";
			$database->query($sql);
		}
		header("Location: tiekejo_administravimas.php");
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
									<td style="text-align:Right">Įmonės kodas</td> 
									<td style="text-align:Left">
									<input name='imones_kodas' id='imones_kodas' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($imone))echo $imone['imones_kodas'];?>">
									</td>
								</tr>
								<tr>
									<td style="text-align:Right">Pavadinimas įmonės</td> 
									<td style="text-align:Left">
									<input name='Pavadinimas' id='Pavadinimas' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($imone))echo $imone['Pavadinimas'];?>">
									</td>
								</tr>
								<tr>
									<td style="text-align:Right">Įjurimo data</td> 
									<td style="text-align:Left">
									<input name='Ikurimolaikas' id='Ikurimolaikas' type='date' class="form-control input-sm" required="true" value="<?php if(isset($imone))echo $imone['Ikurimolaikas'];?>">
									</td>
								</tr>
								<tr>
									<td style="text-align:Right">Miestas</td> 
									<td style="text-align:Left">
									<input name='miestas' id='miestas' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($imone))echo $imone['miestas'];?>">
									</td>
								</tr>
								<tr>
									<td style="text-align:Right">Gatvė</td> 
									<td style="text-align:Left">
									<input name='gatve' id='gatve' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($imone))echo $imone['gatve'];?>">
									</td>
								</tr>
								<tr>
									<td style="text-align:Right">Kontaktinė informacija</td> 
									<td style="text-align:Left">
									<input name='telefonoNumeris' id='telefonoNumeris' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($imone))echo $imone['telefonoNumeris'];?>">
									</td>
								</tr>
								</Table>
								<br>
								<?php if($imone['redagavimas'] == 1){?>
								<fieldset>
									<legend>Darbuotojai</legend>
									<div class="childRowContainer">
										<div class="float-clear"></div>
										<?php if(!isset($darbuotojiai)) {?>
											<div class="childRow hidden">
												<input type="checkbox" name='redagavimas[]' id='redagavimas' class="elementSelector" >
												<select class="elementSelector" name="keitimas[]" disabled="disabled">
													<option value="">----------------------------------------</option>
													<?php
													$num = mysqli_num_rows($klientai);
													for ($j = 0; $j < $num; $j++) {
														$tipai = mysqli_result($klientai, $j);
														$selected = "";
														echo "<option{$selected} value='{$tipai['id']}'>{$tipai['Pavadinimas']}</option>";
													}
													?>
												</select>
												<a href="#" title="" class="removeChild">šalinti</a>
											</div>
											<div class="float-clear"></div>
											<?php } else {
												$set_num = mysqli_num_rows($darbuotojiai);
												if($set_num == 0) $set_num = 1;
												for ($i = 0; $i < $set_num; $i++) {
													$id = mysqli_result($darbuotojiai, $i);
													?>
												<div class="childRow">
												
												<select class="elementSelector" name="keitimas[]" style="width:70%">
													<option value="">----------------------------------------</option>
													<?php
													$id = mysqli_result($darbuotojiai, $i);
													$num = mysqli_num_rows($klientai);
													for ($j = 0; $j < $num; $j++) {
														$tipai = mysqli_result($klientai, $j);
														$selected = "";
														if(isset($id['id']) && $id['id'] == $tipai['id']) {
															$selected = " selected='selected'";
														}
														echo "<option{$selected} value='{$tipai['slapyvardis']}'>{$tipai['vardas']} {$tipai['pavarde']} {$tipai['tel_numeris']} {$tipai['el_pastas']}</option>";
													}
													?>
												</select>
												Redagavimas <input type="checkbox" name='redagavimas[]' id='redagavimas' class="elementSelector" <?php if(isset($id) && $id['redagavimas']) echo "checked"?>>
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
								<?php }?>
								<div> 
									<input id="but" type='submit' name='ok' value='Patvirtinti' class="btn btn-default">
								</div>
							</form>
						</div>
						<script type="text/javascript">
						function uzdarymas() {
							document.getElementById("imones_kodas").readOnly = true;
							document.getElementById("Pavadinimas").readOnly = true;
							document.getElementById("Ikurimolaikas").readOnly = true;
							document.getElementById("miestas").readOnly = true;
							document.getElementById('gatve').readOnly = true;
							document.getElementById('but').disabled = true;
							document.getElementById("telefonoNumeris").readOnly = true;
						}
						function id() {
							document.getElementById("imones_kodas").readOnly = true;
						}
						<?php
						if($imone['redagavimas'] == 0 && !isset($admin)) echo "uzdarymas();";
						if($admin == 0) echo "id();";
						?>
						</script>
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