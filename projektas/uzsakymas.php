<?php
ini_set('display_errors', 1);

if (!isset($_POST['uzsakymoID']))
	die("KLAIDA: Pirmiau sudarykite krepšelį!");

include("include/session.php");
include("include/prekiuAdm.php");
include("include/krepselis.php");

function getUzsakovasInfo($value) {
	global $database, $session;
	return $database->query("SELECT * FROM `klientas` WHERE `klientas`.`slapyvardis`='$session->username'")->fetch_assoc()[$value];	
}

$helper = new BrazzersHelper();
$prekiuKrepselis = $krepselis->getCart($_POST['uzsakymoID']);
if (isset($_POST['SudarytiUzsakyma'])) {
	function makeReceiver($POST) {
		global $database;
		$q = "SELECT COUNT(`id`) AS `lastID` FROM `uzsakymogavejas`";
		$currID = $database->query($q)->fetch_assoc()['lastID'] + 1;
		$vardas = explode(' ', $POST['gvardas_gpavarde'])[0];
		$pavarde = explode(' ', $POST['gvardas_gpavarde'])[1];
		$elpastas = $POST['gelpastas'];
		$telefono_nr = $POST['gnumeris'];
		$atsiemimo_adresas = $POST['gadresas'];		
		$q = "INSERT INTO `uzsakymogavejas` (`id`, `vardas`, `pavarde`, `elpastas`, `telefono_nr`, `atsiemimo_adresas`) VALUES ('$currID', '$vardas', '$pavarde', '$elpastas', '$telefono_nr', '$atsiemimo_adresas')";
		$database->query($q);
		return $currID;
	}

	function makeContract($POST) {
		global $krepselis, $database, $session;
		$kuponas = json_decode($krepselis->checkCoupon($_POST['kuponas']));
		$atsiemimoAdresas = $POST['gadresas'];
		$uzsakymoNr = $POST['uzsakymoID'];
		$uzsakymoData = date("Y-m-d");
		$uzsakymoSuma = $POST['uzsakymoSuma'] * (1 - (isset($kuponas) ? $kuponas->nuolaidosProc : 0)/100);
		$uzsakymoPirkiniuKrepselis = $POST['uzsakymoID'];
		$uzsakymoBusena = 2;
		$fk_Klientasid_Klientas = getUzsakovasInfo('id');
		$fk_uzsakymoGavejasid_uzsakymoGavejas = makeReceiver($POST);
		$fk_uzsakymo_busenosid_uzsakymo_busenos = $uzsakymoBusena;
		$fk_mokejimaiid_mokejimai = $database->query("SELECT COUNT(`mokejimoNr`) AS `paskutinisMokejimas` FROM `mokejimai`")->fetch_assoc()['paskutinisMokejimas'] + 1;
		$q = "INSERT INTO `uzsakymas` (`atsiemimoAdresas`, `uzsakymoNr`, `uzsakymoData`, `uzsakymoSuma`, `uzsakymoPirkiniuKrepselis`, `uzsakymoBusena`, `fk_Klientasid_Klientas`, `fk_uzsakymoGavejasid_uzsakymoGavejas`, `fk_uzsakymo_busenosid_uzsakymo_busenos`, `fk_mokejimaiid_mokejimai`) VALUES ('{$atsiemimoAdresas}','{$uzsakymoNr}','{$uzsakymoData}','{$uzsakymoSuma}','{$uzsakymoPirkiniuKrepselis}','{$uzsakymoBusena}','{$fk_Klientasid_Klientas}','{$fk_uzsakymoGavejasid_uzsakymoGavejas}','{$fk_uzsakymo_busenosid_uzsakymo_busenos}','{$fk_mokejimaiid_mokejimai}')";
		// echo $q;
		$database->query($q);
		// Update some variables here? ...
		$_POST['uzsakymoSuma'] = $uzsakymoSuma;
		$_POST['kuponas'] = $kuponas;
	}
	makeContract($_POST);
	$_SESSION['lastContract'] = $_POST;
	die(Header("Location: mokejimas.php"));
}
$uzsakymoID = $_POST['uzsakymoID'];
?>
<style>

.lentele{
			width:100%;
			align:left;
			border-collapse:collapse; 
			}
			.lentele th {
				background-color: #991821;
				color: white;
				text-align: left;
			}
			.lentele td{ 

			}
			.lentele tr:nth-child(even){
			background-color: #BEF4C5;
			}
			
.datagrid table { 
	border-collapse: collapse; 
	text-align: left; width: 100%;
} 
.datagrid {
	font: normal 12px/150% Arial, Helvetica, sans-serif; 
	background: #fff; 
	overflow: hidden; 
	border: 1px solid #0F9926; 
	-webkit-border-radius: 20px; 
	-moz-border-radius: 20px; 
	border-radius: 16px; 
	border-spacing: 10px;
	width: 85%;
	margin-left: 30px;
}
.datagrid table td,
.datagrid table th { 
	padding: 6px 20px; 
}
.datagrid table thead th {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #1FAB03), color-stop(1, #198246) );
	background:-moz-linear-gradient( center top, #1FAB03 5%, #198246 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1FAB03', endColorstr='#198246');
	background-color:#1FAB03; color:#FFFFFF; 
	font-size: 15px; 
	font-weight: bold; 
	border-left: 0px solid #0F9926; 
} 
.datagrid table thead th:first-child { border: none; }
.datagrid table tbody td { color: #095E18; font-size: 12px;font-weight: normal; }
.datagrid table tbody .alt td { background: #BEF4C5; color: #095E18; }
.datagrid table tbody td:first-child { border-left: none; }
.datagrid table tbody tr:last-child td { border-bottom: none; }
.datagrid table tfoot td div { border-top: 1px solid #0F9926;background: #BEF4C5;} 
.datagrid table tfoot td { padding: 0; font-size: 12px } 
.datagrid table tfoot td div{ padding: 2px; }
.datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: right; }
.datagrid table tfoot  li { display: inline; }
.datagrid table tfoot li a { 
	text-decoration: none; 
	display: inline-block;  
	padding: 2px 8px; 
	margin: 1px;color: #FFFFFF;
	border: 1px solid #039903;
	-webkit-border-radius: 3px; 
	-moz-border-radius: 3px; 
	border-radius: 3px; 
	border-spacing:10px;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #1FAB03), color-stop(1, #198246) );
	background:-moz-linear-gradient( center top, #1FAB03 5%, #198246 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1FAB03', endColorstr='#198246');
	background-color:#1FAB03; 
}
.datagrid table tfoot ul.active, 
.datagrid table tfoot ul a:hover { 
	text-decoration: none;
	border-color: #039903; 
	color: #FFFFFF; 
	background: none; 
	background-color:#0F9926;
}
div.dhtmlx_window_active, div.dhx_modal_cover_dv { position: fixed !important; }
</style>
<html>
<body style="background-color:orange;">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8"/>
        <title>Drym Tym elektroninė parduotuvė</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet"
			href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	
    </head>
    <body> 

        <table class="center" ><tr><td>
           <center><img src="pictures/top.png"/></center>
        </td></tr><tr><td> 
            <?php
			
            //Jei vartotojas prisijunges
            if ($session->logged_in) {
                include("include/meniu.php");
                ?>
			<table style="border-width: 2px; border-style: dotted;">
                <tr>
                    <td>
                        [<?php echo "<a href='items.php?removeCart=$uzsakymoID'>Atšaukti užsakymą</a>"; ?>]
                    </td>
                </tr>
            </table>
			<div style="text-align:left;color:green;font-size: 30px">
                    <br><br>
                    <p style='text-align:center;'>Užsakymo sudarymas</p>
					<br>
						<form action="uzsakymas.php" method="POST"> 

						<div style="text-align:left;color:green;font-size: 20px">
                            <p style='padding-left:30px'>Užsakymo duomenys:</p>
						</div>
						<div class="datagrid">
						<table class="lentele">
						<thead>
							<tr>
								<th>Užsakovas</th>
								<th>Užsakymo data</th>
								<th>Užsakovo el.paštas</th>
								<th>Užsakovo tel.</th>
							</tr>
						</thead>
						<tbody>
						<tr>
							<td> <?php echo "<div style='text-align:left'>". getUzsakovasInfo('vardas') . " " . getUzsakovasInfo('pavarde') . "</div>"; ?></td>
							<td> <?php echo "<div style='text-align:left'>". date("Y-m-d") ."</div>"; ?></td>
							<td> <?php echo "<div style='text-align:left'>". getUzsakovasInfo('el_pastas') ."</div>"; ?></td>
							<td> <?php echo "<div style='text-align:left'>". getUzsakovasInfo('tel_numeris') ."</div>"; ?></td>
						</tr>
						</tbody>
						</table>	
						</div>
						
						<br>
						
						<div style="text-align:left;color:green;font-size: 20px">
                            <p style='padding-left:30px'>Prekes atsiims:</p>
						</div>
						<div class="datagrid">
						<table class="lentele">
						<thead>
							<tr>
								<th>Gavėjas</th>
								<th>Gavėjo el.paštas</th>
								<th>Gavėjo tel.</th>
								<th>Atsiėmimo adresas</th>
							</tr>
						</thead>
						<tbody>
						<tr>
							<td> <?php echo "<input style='text-align:left' class='text' type='text' name='gvardas_gpavarde' placeholder='Vardas Pavardė' value='' required/>"; ?> </td>
							<td> <?php echo "<input style='text-align:left' class='text' type='text' name='gelpastas' placeholder='vartotojas@epastas.lt' value='' required/>"; ?> </td>
							<td> <?php echo "<input style='text-align:left' class='text' type='text' name='gnumeris' placeholder='+370XXXXXXXX' value='' required/>"; ?> </td>
							<td> <?php echo "<input style='text-align:left' class='text' type='text' name='gadresas' placeholder='Atsiėmimo adresas' value='' required/>"; ?> </td>
						</tr>
						</tbody>
						</table>	
						</div>
						<br>
						<div style="text-align:left;color:green;font-size: 20px">
                            <p style='padding-left:30px'>Norimos įsigyti prekės:</p>
						</div>
						<div class="datagrid">
						<table class="lentele">
						<thead>
							<tr>
								<th>Prekės kodas</th>
								<th>Prekė</th>
								<th>Kiekis</th>
								<th>Kaina</th>
								<th>Suma</th>
							</tr>
						</thead>
						<tbody>
						<?php
						if (!isset($prekiuKrepselis)) { ?> 
						<td> <?php echo "<div style='text-align:left'>". "Įvyko sistemoje klaida"."</div>"; ?> </td>
						<?php } else { ?>
						<?php //Imami irasai is lenteles kol yra irasu
						$visaSuma = 0;
						foreach($prekiuKrepselis as $prekes) {
							$prekesInfo = $helper->getItem($prekes['fk_preke']);
							$visaSuma = $visaSuma + $prekes['prekesKiekis'] * $prekesInfo['Kaina'];
						?>
						<tr>
							<td> <?php echo "<div style='text-align:left'>". $prekesInfo['prekesKodas']."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $prekesInfo['Pavadinimas']."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $prekes['prekesKiekis']."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $prekesInfo['Kaina']." €"."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $prekes['prekesKiekis'] * $prekesInfo['Kaina']." €"."</div>"; ?> </td>
						</tr>
						<?php }} ?>
						</tbody>
						</table>	
						</div>
						<br>
						<div style="text-align:left;color:green;font-size: 20px">
                            <p style='padding-left:30px'>Nuolaidos kuponas: <input style="text-align:center;" class='text' type='text' name='kuponas' placeholder='kodas' value='' id='coupon'/></p>
							<script>
								$(function() {
									$('#coupon').focusin(function() {
										$('#coupon').val('');
									});
									
									$('#coupon').focusout(function() {
										if ($('#coupon').val() != "") {
											$.ajax({
												type: "POST",
												url: "include/krepselis.php",
												data: {'func': 'checkCoupon', 'args': $('#coupon').val()},
												success: function(response) {
													var nuolaida = JSON.parse(response);
													if (nuolaida != null) {
														$('#coupon').css('background-color', '#a2a2a2');
														$('#coupon').attr('readonly', true);
														$('#coupon').unbind();
														$('#totalSum').text($('#totalSum').text() + " (-" + parseInt(nuolaida.nuolaidosProc) + "%)");														
													} else {
														$('#coupon').attr('placeholder', "Netinkamas kodas!");
														$('#coupon').focus();
													}
												}
											});									
										}
									});
								});
							</script>
						</div>
						<br>
						<div style="text-align:left;color:green;font-size: 16px; font-weight:bold">
							<p style='padding-left:560px;' id='totalSum'>Užsakymo kaina: <?php echo $visaSuma."€" ?></p>
						</div>		
							<input type="hidden" name='uzsakymoSuma' value="<?php echo $visaSuma;?>"/>
							<input type="hidden" name='uzsakymoID' value="<?php echo $_POST['uzsakymoID'];?>"/>
							<input style="margin: 0 auto; display: inherit;" type="submit" name="SudarytiUzsakyma" value="Sudaryti"/>
						</form>
			<br>
                <?php
                //Jei vartotojas neprisijunges, rodoma prisijungimo forma
                //Jei atsiranda klaidu, rodomi pranesimai.
            } else {
                echo "<div align=\"center\">";
                if ($form->num_errors > 0) {
                    echo "<font size=\"3\" color=\"#ff0000\">Klaidų: " . $form->num_errors . "</font>";
                }
                echo "<table class=\"center\"><tr><td>";
                include("include/prisijungimas.php");
                echo "</td></tr></table></div><br></td></tr>";
				
            } ?>
			<?php
            echo "<tr><td>";
			
            include("include/footer.php");
            echo "</td></tr>";
            ?>
			
</table>
</body>
</html>