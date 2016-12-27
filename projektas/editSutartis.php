<?php
include("include/session.php");
if ($session->logged_in) {
	$user = $session->username;
	$kodas = $_GET['kodas'];
	$idas = $_GET['id'];
	if(isset($_POST['ok']))
	{
		$patv = 1;
		$post = $_POST;
	}
	if($_GET["id"] != -1) 
	{
		$sql="SELECT * FROM `tiekimokontraktas` WHERE `sutartiesKodas`='{$_GET["id"]}'";
		$res = $database->query($sql);
		$sutartis = $res->fetch_array(); 
	}
	$sql="SELECT `Pavadinimas`, `imones_kodas` FROM `tiekejas`";
	$tiekejai = $database->query($sql);
	$sql="SELECT `imones_pavadinimas`,`imones_kodas` FROM `imone`";
	$parduotuves = $database->query($sql);
	
	if(!isset($_POST['randcheck'])) {$_POST['randcheck'] = 0; $_SESSION['rand'] = 1;}
	if(isset($_POST['pat'])&& $_POST['randcheck']==$_SESSION['rand'] && isset($_POST['patvirtinimas']))
	{
		$sql="DELETE FROM `tiekimokontraktas` WHERE `sutartiesKodas`='{$_POST['kodas']}'";
		$database->query($sql);
		$sql="INSERT INTO `tiekimokontraktas`(`prVardas`, `prPavarde`, `tkVardas`, `tkPavarde`, `sudData`, `patvirtinta`, `miestas`, `sutartiesKodas`, `fk_Tiekejas`, `fk_Imone`) 
		VALUES ('{$_POST['prVardas']}','{$_POST['prPavarde']}','{$_POST['tkVardas']}','{$_POST['tkPavarde']}','{$_POST['sudData']}','1','{$_POST['miestas']}','{$_POST['kodas']}','{$_POST['tiekejas']}','{$_POST['parduotuvee']}')";
		$database->query($sql);
		header("Location: tiekimo_sutartis_administravimas.php");
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
						<input type="hidden" name="tiekejas" value="<?php if(isset($post['teikejas']))echo $post['teikejas']; ?>" />
						<input type="hidden" name="parduotuvee" value="<?php if(isset($post['parduotuve']))echo $post['parduotuve']; ?>"/>
						<Table style="width:70%">
							<tr>
								<th style="text-align:Right">Sutarties kodas </th> 
								<th style="text-align:Left">
								<input name='kodas' id='kodas'  type='textbox' class="form-control input-sm" required="true" value="<?php if(isset($sutartis))echo $sutartis['sutartiesKodas'];?>">
								</th>
							</tr>
							<tr>
								<td style="text-align:Right">Par. atstovo vardas</td> 
								<td style="text-align:Left">
								<input name='prVardas' id='prVardas'  type='textbox' class="form-control input-sm" required="true" value="<?php if(isset($sutartis))echo $sutartis['prVardas'];?>">
								</td>
							</tr>
							<tr>
								<td style="text-align:Right">Par. atstovo pavarde</td> 
								<td style="text-align:Left">
								<input name='prPavarde' id='prPavarde'  type='textbox' class="form-control input-sm" required="true" value="<?php if(isset($sutartis))echo $sutartis['prPavarde'];?>">
								</td>
							</tr>
							<tr>
								<td style="text-align:Right">Tiek. atstovo vardas</td> 
								<td style="text-align:Left">
								<input name='tkVardas' id='tkVardas' type='textbox' class="form-control input-sm" required="true" value="<?php if(isset($sutartis))echo $sutartis['tkVardas'];?>">
								</td>
							</tr>
							<tr>
								<td style="text-align:Right">Tiek. atstovo pavarde</td> 
								<td style="text-align:Left">
								<input name='tkPavarde' id='tkPavarde' type='textbox' class="form-control input-sm" required="true" value="<?php if(isset($sutartis))echo $sutartis['tkPavarde'];?>">
								</td>
							</tr>
							<tr>
								<td style="text-align:Right">Sudarymo data</td> 
								<td style="text-align:Left">
								<input name='sudData' id='sudData' type='date' class="form-control input-sm" required="true" value="<?php if(isset($sutartis))echo $sutartis['sudData'];?>">
								</td>
							</tr>
							<tr>
								<td style="text-align:Right">Miestas</td> 
								<td style="text-align:Left">
								<input name='miestas' id='miestas' type='textbox' class="form-control input-sm" required="true" value="<?php if(isset($sutartis))echo $sutartis['miestas'];?>">
								</td>
							</tr>
							<tr>
								<td style="text-align:Right">Tiekejas</td> 
								<td style="text-align:Left">
									<select class="elementSelector" name="teikejas" id="tiek">
										<option value="">----------------------------------------</option>
										<?php
										$num = mysqli_num_rows($tiekejai);
										for ($j = 0; $j < $num; $j++) {
											$tipai = mysqli_result($tiekejai, $j);
											$selected = "";
											var_dump($tipai['imones_kodas']);
											if($tipai['imones_kodas'] == $kodas) $selected = " selected='selected'";
											if(isset($sutartis['fk_Tiekejas']) && $tipai['imones_kodas'] == $sutartis['fk_Tiekejas']) $selected = " selected='selected'";
											echo "<option{$selected} value='{$tipai['imones_kodas']}'>{$tipai['Pavadinimas']} {$tipai['imones_kodas']}</option>";
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td style="text-align:Right">Parduotuvė</td> 
								<td style="text-align:Left">
									<select class="elementSelector" name="parduotuve" id="pard">
										<option value="">----------------------------------------</option>
										<?php
										$num = mysqli_num_rows($parduotuves);
										for ($j = 0; $j < $num; $j++) {
											$tipai = mysqli_result($parduotuves, $j);
											$selected = "";
											if($tipai['imones_kodas'] == $kodas) $selected = " selected='selected'";
											if(isset($sutartis['fk_Tiekejas']) && $tipai['imones_kodas'] == $sutartis['fk_Imone']) $selected = " selected='selected'";
											echo "<option{$selected} value='{$tipai['imones_kodas']}'>{$tipai['imones_pavadinimas']} {$tipai['imones_kodas']}</option>";
										}
										?>
									</select>
								</td>
							</tr>
							<tr >
								<td style="text-align:Right"><?php if(isset($patv)) echo 'Patvirtinimas'?></td> 
								<td style="text-align:Left">
								<input type="<?php if(!isset($patv)) echo 'hidden'; else echo 'checkbox'?>" name='patvirtinimas' id='patvirtinimas' class="form-control input-sm">
								</td>
							</tr>
						</Table>
						<div>
							<input type='submit' name="<?php if(!isset($patv)) echo 'ok'; else echo 'pat'?>" value='Saugoti' class="btn btn-default">
						</div>
					</form>
				</div>
				<script type="text/javascript">
				function disable() {
					document.getElementById('kodas').readOnly = true;
					document.getElementById('prVardas').readOnly = true;
					document.getElementById("prPavarde").readOnly = true;
					document.getElementById("tkVardas").readOnly = true;
					document.getElementById('tkPavarde').readOnly = true;
					document.getElementById("sudData").readOnly = true;
					document.getElementById("miestas").readOnly = true;
					document.getElementById("pard").disabled = true;
					document.getElementById("tiek").disabled = true;
					document.getElementById('kodas').value = "<?php echo $post['kodas'];?>";
					document.getElementById('prVardas').value = "<?php echo $post['prVardas'];?>";
					document.getElementById("prPavarde").value = "<?php echo $post['prPavarde'];?>";
					document.getElementById("tkVardas").value = "<?php echo $post['tkVardas'];?>";
					document.getElementById('tkPavarde').value = "<?php echo $post['tkPavarde'];?>";
					document.getElementById("sudData").value = "<?php echo $post['sudData'];?>";
					document.getElementById("miestas").value = "<?php echo $post['miestas'];?>";
					document.getElementById("pard").value = "<?php echo $post['parduotuve'];?>";
					document.getElementById("tiek").value = "<?php echo $post['teikejas'];?>";
				}
				function id() {
					document.getElementById('kodas').readOnly = true;
				}
				function enable() {
					document.getElementById("pard").disabled = false;
					document.getElementById("tiek").disabled = false;
				}
				<?php
				if($idas != -1) echo "id();";
				if(isset($patv)) echo "disable();";
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