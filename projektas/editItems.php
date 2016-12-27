<?php
ini_set('display_errors', 1);
include("include/session.php");
include("include/prekiuAdm.php");
if ($session->logged_in) {
	global $database;
    $helper = new BrazzersHelper();
	if(!isset($_POST['randcheck'])) {$_POST['randcheck'] = 0; $_SESSION['rand'] = 1;}
	if(isset($_POST['keist'])&& $_POST['randcheck']==$_SESSION['rand'])
	{
		if($_POST['pav'] != "")
		$helper->editType(-1,$_POST);
	}
	if($_GET["id"] != -1)
	{
		$preke = $helper->getItem($_GET["id"]);
		$ipatumai = $helper->getAssociatedTypes($_GET["id"]);
        $sandelys = $helper->getWarehouseForItem($_GET["id"]);
        $sandeliai = $helper->getAllWarehouses();
	}
	$savybes = $helper->getAllTypes();
	if(!isset($_POST['randcheck'])) {$_POST['randcheck'] = 0; $_SESSION['rand'] = 1;}
	if(isset($_POST['ok'])&& $_POST['randcheck']==$_SESSION['rand'])
	{
		var_dump($_POST);
		$helper->editItem($_GET["id"],$_POST);
		$helper->removeType($_GET["id"]);
		foreach($_POST['keitimas'] as $key => $val) {
			$helper->addTypeConnection($_GET["id"],$val);
		}
		header("Location: items.php");
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
								<div class="form-group">
									<label class='control-label'>Prekės kodas</label>
									<input name='kodas' id='kodas' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($preke))echo $preke['prekesKodas'];?>">
								</div>
								<div class="form-group">
									<label class='control-label'>Prekės pavadinimas</label>
									<input name='pavadinimas' id='pavadinimas' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($preke))echo $preke['Pavadinimas'];?>">
								</div>
								<div class="form-group">
									<label class='control-label'>Galiojimas iki</label>
									<input name='galiojimas' id='galiojimas' type='date' class="form-control input-sm" required="true" value="<?php if(isset($preke))echo $preke['Galiojimas'];?>">
								<div class="form-group">
									<label class='control-label'>Prekės kaina</label>
									<input name='kaina' id='kaina' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($preke))echo $preke['Kaina'];?>">
								</div>
								<div class="form-group">
									<label class='control-label'>Vienetų skaičius</label>
									<input name='kiekis' id='kiekis' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($preke))echo $preke['Kiekis'];?>">
								</div>
								<select class="elementSelector" name="sand[]">
									<option value="">----------------------------------------</option>
									<?php
									foreach ($sandeliai as $sand) {
										$selected = "";
										if(isset($sandelys['id']) && $sandelys['id'] == $sand['id']) {
											$selected = " selected='selected'";
										}
										echo "<option{$selected} value='{$sand['id']}'>{$sand['Adresas']}</option>";
									}
									?>
								</select>
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
													foreach ($savybes as $savybe) {
														$selected = "";
														echo "<option{$selected} value='{$savybe['id']}'>{$savybe['Pavadinimas']}</option>";
													}
													?>
												</select>
												<a href="#" title="" class="removeChild">šalinti</a>
											</div>
											<div class="float-clear"></div>
											<?php } else {
												foreach($ipatumai as $ipatumas) {
													?>
												<div class="childRow">
												<select class="form-control elementSelector" name="keitimas[]">
													<option value="">----------------------------------------</option>
													<?php
													foreach($savybes as $savybe) {
														$selected = "";

														if(isset($ipatumas['id']) && $ipatumas['id'] == $savybe['id']) {
															$selected = " selected='selected'";
														}
														echo "<option{$selected} value='{$savybe['id']}'>{$savybe['Pavadinimas']}</option>";
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
?>
