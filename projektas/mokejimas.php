<?php
ini_set('display_errors', 1);
include("include/session.php");

if (!isset($_SESSION['lastContract']) && !isset($_POST['SudarytiMokejima']))
	die("KLAIDA: Pirmiau sudarykite užsakymą!");

if (isset($_SESSION['lastContract'])) {
	$mokejimas = $_SESSION['lastContract'];
	$_SESSION['mokejimas'] = $mokejimas;
	unset($_SESSION['lastContract']);
} else if (isset($_POST['SudarytiMokejima'])) {
	$mokejimas = $_SESSION['mokejimas'];
	unset($_SESSION['mokejimas']);

	function updateContract() {
		global $database, $mokejimas;
		$uzsakymoBusena = 3;
		$uzsakymoNr = $mokejimas['uzsakymoID'];
		$q = "UPDATE `uzsakymas` SET `uzsakymoBusena`='$uzsakymoBusena',`fk_uzsakymo_busenosid_uzsakymo_busenos`='$uzsakymoBusena' WHERE `uzsakymoNr`='$uzsakymoNr'";
		$database->query($q);
	}
	
	function makePayment($POST) {
		global $database, $mokejimas;
		$pristatymoBudas = $POST['pristatymoBudai'];
		$galutineKaina = $mokejimas['uzsakymoSuma'];
		$fk_kuponaiid_kuponai = (isset($mokejimas['kuponas']) ? $mokejimas['kuponas']->id : 0);
		$q = "INSERT INTO `mokejimai`(`mokejimoNr`, `galutineKaina`, `pristatymoBudas`, `fk_kuponaiid_kuponai`) VALUES (NULL, '$galutineKaina', '$pristatymoBudas','$fk_kuponaiid_kuponai')";
		$database->query($q);
		return true;
	}
	// var_dump($mokejimas);
	// var_dump($_POST);
	updateContract();
	makePayment($_POST);
	die(Header("Location: uzsakymu_istorijos_perziura.php?id=" . $mokejimas['uzsakymoID']));
}

include("include/prekiuAdm.php");
include("include/krepselis.php");

function getUzsakovasInfo($value) {
	global $database, $session;
	return $database->query("SELECT * FROM `klientas` WHERE `klientas`.`slapyvardis`='$session->username'")->fetch_assoc()[$value];	
}

function getGavejasInfo($value) {
	global $database, $mokejimas;
	$mokejimoID = $mokejimas['uzsakymoID'];
	$gavejoID = $database->query("SELECT `fk_uzsakymoGavejasid_uzsakymoGavejas` AS `gavejas` FROM `uzsakymas` WHERE `uzsakymas`.`uzsakymoNr`='$mokejimoID'")->fetch_assoc()['gavejas'];
	$q = "SELECT * FROM `uzsakymogavejas` WHERE `uzsakymogavejas`.`id`='$gavejoID';";
	return $database->query($q)->fetch_assoc()[$value];
}

$helper = new BrazzersHelper();
$prekiuKrepselis = $krepselis->getCart($mokejimas['uzsakymoID']);
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
	#background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #1FAB03), color-stop(1, #198246) );
	#background:-moz-linear-gradient( center top, #1FAB03 5%, #198246 100% );
	#filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1FAB03', endColorstr='#198246');
	#background-color:#1FAB03; 
	color:#FFFFFF; 
	#font-size: 15px; 
	font-weight: bold; 
	#border-left: 0px solid #0F9926; 
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
                        [<a href="uzsakymu_istorija.php">Atgal į užsakymų istoriją</a>]
                    </td>
                </tr>
            </table>
			<div style="text-align:left;color:green;font-size: 30px">
                    <br><br>
                    <p style='padding-left:250px; padding-right:250px'>Mokėjimo sudarymas</p>
					<br>
						<form action="mokejimas.php" method="POST">
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
							<td> <?php echo "<div style='text-align:left'>". getGavejasInfo('vardas') . " " . getGavejasInfo('pavarde') . "</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". getGavejasInfo('elpastas')."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". getGavejasInfo('telefono_nr')."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". getGavejasInfo('atsiemimo_adresas')."</div>"; ?> </td>
						</tr>
						</tbody>
						</table>	
						</div>
						<br>
						<div style="text-align:left;color:green;font-size: 20px">
                            <p style='padding-left:30px'>Užsakytos prekės:</p>
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
                            <p style='padding-left:30px'>Pristatymo būdai:					
								<select name="pristatymoBudai">
									<?php 
										$q = "SELECT * FROM `pristatymo_budai`";
										$result = $database->query($q);
										while ($pristatymoBudai = $result->fetch_assoc()) {
											$id = $pristatymoBudai['id_pristatymo_budai'];
											$budas = $pristatymoBudai['name'];
											echo "<option value='$id' ". (intval($id) == 1 ? 'selected="selected"' : '') .">$budas</option>";
										}
									?>
								</select>
							</p>
						</div>
						<div style="text-align:left;color:green;font-size: 16px; font-weight:bold">
							<p style='padding-left:560px;'>Užsakymo kaina: <?php echo $mokejimas['uzsakymoSuma'] . "€" . " (su " . (isset($mokejimas['kuponas']) ? $mokejimas['kuponas']->nuolaidosProc : 0) . "% nuolaida)" ?></p>
						</div>					
						<input style="margin: 0 auto; display: inherit;" type="submit" name="SudarytiMokejima" value="Sumokėti"/>
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