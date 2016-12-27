<?php
include("include/session.php");
	//Prisijungimas prie duomenu bazes
	$dbc = mysqli_connect('db.if.ktu.lt', 'donvos', 'joh8Ida8taishahj', 'donvos');
	if(!$dbc ){
		die('Negaliu prisijungti: '.mysqli_error($dbc));
	}
	if (mysqli_connect_errno()) {
		die('Connect failed: '.mysqli_connect_errno().' : '.
		mysqli_connect_error());
	}
	function getBusenos($busena) {
	global $database;
		$query = "  SELECT *
					FROM `uzsakymo_busenos`
					WHERE `uzsakymo_busenos`.`name`!='$busena'";
		$data = mysqli_query($database->connection,$query);
		return $data;
	}
	
	$idas = $_GET['id2'];
	$klientas = $_GET['id'];
	
	$prekes = mysqli_query($dbc, "SELECT `prekes`.`id`,
										 `prekes`.`pavadinimas`,
										 `prekes`.`kaina`,
										 `krepselis`.`prekesKiekis`
										 FROM `prekes`, `krepselis`, `uzsakymas`
										 WHERE `prekes`.`id`=`krepselis`.`fk_preke`
										 AND `krepselis`.`fk_Uzsakymasid_Uzsakymas`=`uzsakymas`.`uzsakymoNr`
										 AND `uzsakymas`.`uzsakymoNr`='$idas'");
										 
	$pilnaSuma2 = mysqli_query($dbc, "SELECT SUM(`prekes`.`kaina`*`krepselis`.`prekesKiekis`) AS pilnaSuma
										 FROM `prekes`, `krepselis`, `uzsakymas`
										 WHERE `prekes`.`id`=`krepselis`.`fk_preke`
										 AND `krepselis`.`fk_Uzsakymasid_Uzsakymas`=`uzsakymas`.`uzsakymoNr`
										 AND `uzsakymas`.`uzsakymoNr`='$idas'");
	$pilnaSuma = mysqli_fetch_assoc($pilnaSuma2);
	$visaSuma = $pilnaSuma['pilnaSuma'];
	
	$gavejai = mysqli_query($dbc, "SELECT `uzsakymogavejas`.`vardas`,
										  `uzsakymogavejas`.`pavarde`,
										  `uzsakymogavejas`.`elpastas`,
										  `uzsakymogavejas`.`telefono_nr`,
										  `uzsakymogavejas`.`atsiemimo_adresas`
									FROM `uzsakymogavejas`, `uzsakymas`
									WHERE `uzsakymas`.`uzsakymoNr`='$idas' 
									AND `uzsakymas`.`fk_uzsakymoGavejasid_uzsakymoGavejas`=`uzsakymogavejas`.`id`");
	$gavejas = mysqli_fetch_assoc($gavejai);
	$gvardas = $gavejas['vardas'];
	$gpavarde = $gavejas['pavarde'];
	$gelpastas = $gavejas['elpastas'];
	$gnumeris = $gavejas['telefono_nr'];
	$gadresas = $gavejas['atsiemimo_adresas'];
	
	$useriai = mysqli_query($dbc, "SELECT `klientas`.`id`,
										  `klientas`.`vardas`,
										  `klientas`.`pavarde`,
										  `klientas`.`el_pastas`,
										  `klientas`.`tel_numeris`
									FROM `klientas`
									WHERE `klientas`.`id`='$klientas'");
	$useris = mysqli_fetch_assoc($useriai);
	
	$uzsakymai = mysqli_query($dbc, "SELECT `uzsakymas`.`uzsakymoNr`,
										 `uzsakymas`.`uzsakymoData`,
										 `uzsakymas`.`uzsakymoSuma`,
										 `uzsakymas`.`uzsakymoBusena`,
										 `uzsakymo_busenos`.`name` AS `busena`
								FROM `uzsakymas`
									LEFT JOIN `uzsakymo_busenos`
										ON `uzsakymas`.`uzsakymoBusena`=`uzsakymo_busenos`.`id_uzsakymo_busenos`
								WHERE `uzsakymas`.`uzsakymoNr`='$idas'
								ORDER BY `uzsakymas`.`uzsakymoNr`");
	$uzsakymas = mysqli_fetch_assoc($uzsakymai);
	
	$vardas = $useris['vardas'];
	$pavarde = $useris['pavarde'];
	$el_pastas = $useris['el_pastas'];
	$tel_numeris = $useris['tel_numeris'];
	$uzsakymo_data = $uzsakymas['uzsakymoData'];
	$busena = $uzsakymas['busena'];
	$uzsakymo_suma = $uzsakymas['uzsakymoSuma'];
	
	if (isset($_POST['Submit3'])) {
		$busena_id = $_POST['busena2'];
		
		$busenos = mysqli_query($dbc, "SELECT `uzsakymo_busenos`.`name`
									FROM `uzsakymo_busenos`
									WHERE `uzsakymo_busenos`.`id_uzsakymo_busenos`='$busena_id'");
		$busena53 = mysqli_fetch_assoc($busenos);
		$busena = $busena53['name'];
		
		$update = mysqli_query($dbc, "UPDATE `uzsakymas` SET `uzsakymas`.`uzsakymoBusena`='$busena_id' 
														WHERE `uzsakymas`.`uzsakymoNr`='$idas'");
	}
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
                                [<a href="uzsakymu_istorijos_apskaita.php">Atgal į užsakymų istoriją</a>]
                            </td>
                        </tr>
                    </table>
			<div style="text-align:left;color:green;font-size: 30px">
                    <br><br>
                    <p style='padding-left:250px; padding-right:250px'>Informacija apie užsakymą #<?php echo $idas; ?></p>
					<br>
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
								<th>Užsakymo būsena</th>
							</tr>
						</thead>
						<tbody>
						<tr>
							<td> <?php echo "<div style='text-align:left'>". $vardas. " ". $pavarde."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $uzsakymo_data."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $el_pastas."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $tel_numeris."</div>"; ?> </td>
							<td>	<form method='post'>
									<select id="username2" name="busena2">
										<option value="-1"><?php echo $busena;?></option>
											<?php
												// busenos redagavimas
												$busena2 = getBusenos($busena);
												foreach($busena2 as $key => $val) {
													$selected = "";
													if(isset($fields['busena2']) && $fields['busena2'] == $val['id_uzsakymo_busenos']) {
														$selected = " selected='selected'";
													}
													echo "<option{$selected} value='{$val['id_uzsakymo_busenos']}'>{$val['name']}</option>";
													echo $form->value($val['id_uzsakymo_busenos']);
												}
											?>
										<div class="form-group">
                                            <input type='submit' name='Submit3' value='Išsaugoti' class="btn btn-default btn-xs">
                                        </div>
                                    </form>
									</select> 
							</td>
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
							<td> <?php echo "<div style='text-align:left'>". $gvardas. " ". $gpavarde."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $gelpastas."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $gnumeris."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $gadresas."</div>"; ?> </td>
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
						if (mysqli_num_rows($prekes) == 0) { ?> 
						<td> <?php echo "<div style='text-align:left'>". "Reikėtų pašalinti šį užsakymą, nes įvyko sistemoje klaida ir jis tuščias"."</div>"; ?> </td>
						<?php } else { ?>
						<?php //Imami irasai is lenteles kol yra irasu
						while($preke = mysqli_fetch_assoc($prekes)): ?>
						<tr>
							<td> <?php echo "<div style='text-align:left'>". $preke['id']."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $preke['pavadinimas']."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $preke['prekesKiekis']."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $preke['kaina']."€"."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $preke['prekesKiekis']*$preke['kaina']."€"."</div>"; ?> </td>
						</tr>
						<?php endwhile; } ?>
						</tbody>
						</table>	
						</div>
						<br>
						<div style="text-align:left;color:green;font-size: 16px; font-weight:bold">
							<p style='padding-left:560px;'>Užsakymo kaina: <?php echo $visaSuma."€" ?></p>
						</div>
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