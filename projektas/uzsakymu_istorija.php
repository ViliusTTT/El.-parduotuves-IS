<?php
include("include/session.php");
if(DB_SERVER === null)
	include("include/constants.php");

	//Prisijungimas prie duomenu bazes
	$dbc = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	if(!$dbc ){
		die('Negaliu prisijungti: '.mysqli_error($dbc));
	}
	if (mysqli_connect_errno()) {
		die('Connect failed: '.mysqli_connect_errno().' : '.
		mysqli_connect_error());
	}
	
	$pilnaSumos = mysqli_query($dbc, "SELECT SUM(`prekes`.`kaina`*`krepselis`.`prekesKiekis`) AS pilnaSuma
										 FROM `prekes`, `krepselis`, `uzsakymas`
										 WHERE `prekes`.`id`=`krepselis`.`fk_preke`
										 AND `krepselis`.`fk_Uzsakymasid_Uzsakymas`=`uzsakymas`.`uzsakymoNr`");
	
	$useriai = mysqli_query($dbc, "SELECT `klientas`.`id`,
										  `klientas`.`vardas`,
										  `klientas`.`pavarde`
									FROM `klientas`
									WHERE `klientas`.`slapyvardis`='$session->username'");
	$useris = mysqli_fetch_assoc($useriai);
	
	$user = $useris['id'];
	$vardas = $useris['vardas'];
	$pavarde = $useris['pavarde'];
	
	$irasai = mysqli_query($dbc, "SELECT `uzsakymas`.`uzsakymoNr`,
										 `uzsakymas`.`uzsakymoData`,
										 `uzsakymas`.`uzsakymoSuma`,
										 `uzsakymas`.`uzsakymoBusena`,
										 `uzsakymo_busenos`.`name` AS `busena`
								FROM `uzsakymas`
									LEFT JOIN `uzsakymo_busenos`
										ON `uzsakymas`.`uzsakymoBusena`=`uzsakymo_busenos`.`id_uzsakymo_busenos`
								WHERE `uzsakymas`.`fk_Klientasid_Klientas`='$user'
								ORDER BY `uzsakymas`.`uzsakymoNr`");
										
	if (isset($_POST['Submit1'])) {
	$date=$_POST["vardas"];
	$date2=$_POST["epastas"];
	$irasai = mysqli_query($dbc, "SELECT `uzsakymas`.`uzsakymoNr`,
										 `uzsakymas`.`uzsakymoData`,
										 `uzsakymas`.`uzsakymoSuma`,
										 `uzsakymas`.`uzsakymoBusena`,
										 `uzsakymo_busenos`.`name` AS `busena`
								FROM `uzsakymas`
									LEFT JOIN `uzsakymo_busenos`
										ON `uzsakymas`.`uzsakymoBusena`=`uzsakymo_busenos`.`id_uzsakymo_busenos`
								WHERE `uzsakymas`.`fk_Klientasid_Klientas`='$user' AND uzsakymoData>'$date' AND uzsakymoData<'$date2'
								ORDER BY `uzsakymas`.`uzsakymoNr`");
	}
	if (isset($_POST['Submit2'])) {
	$date=$_POST["vardas"];
	$date2=$_POST["epastas"];
	$irasai2 = mysqli_query($dbc, "SELECT COUNT(`uzsakymas`.`uzsakymoNr`) AS kiekis,
										 SUM(`uzsakymas`.`uzsakymoSuma`) AS visoSuma,
										 `uzsakymas`.`uzsakymoNr`,
										 `uzsakymas`.`uzsakymoData`,
										 `uzsakymas`.`uzsakymoSuma`,
										 `uzsakymas`.`uzsakymoBusena`,
										 `uzsakymo_busenos`.`name` AS `busena`
								FROM `uzsakymas`
									LEFT JOIN `uzsakymo_busenos`
										ON `uzsakymas`.`uzsakymoBusena`=`uzsakymo_busenos`.`id_uzsakymo_busenos`
								WHERE `uzsakymas`.`fk_Klientasid_Klientas`='$user' AND uzsakymoData>'$date' AND uzsakymoData<'$date2'
								ORDER BY `uzsakymas`.`uzsakymoNr`");
	$irasas2 = mysqli_fetch_assoc($irasai2);
	$kiekis = $irasas2['kiekis'];
	$visoSuma = $irasas2['visoSuma'];
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
	width: 75%;
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
			
            //Jei vartotojas prisijungęs
            if ($session->logged_in) {
                include("include/meniu.php");
                ?>
                <div style="text-align:left;color:green;font-size: 30px">
                    <br><br>
                    <p style='padding-left : 30px'>Mano užsakymai <?php ?></p>
						<div style="text-align:left;color:green;font-size: 16px">
                            <p style='padding-left : 30px'>Išsirinkite užsakymų istorijos laikotarpį</p>
                                <div class="container">
                                    <form method='post'>
                                        <div class="form-group col-md-2">
                                            <label for="vardas" class="control-label">Nuo</label>
                                            <input name='vardas' id='vardas' type='date' class="form-control input-sm" required="true">
                                        </div>
                                        <div class="form-group col-md-2">
											<label for="epastas" class="control-label">Iki</label>
											<input name='epastas' id="epastas" type='date' class="form-control input-sm" required="true">
                                        </div>
										<br>
                                        <div class="form-group">
                                            <input type='submit' name='Submit1' value='rodyti' class="btn btn-default">
                                        </div>
                                    </form>
								</div>
						</div>
						<?php if (mysqli_num_rows($irasai) == 0) { ?>
						<div style="text-align:left;color:green;font-size: 20px">
							<p style='padding-left : 30px'><u>Jūs neturite jokių sudarytų užsakymų.</u></p>
						</div>
						<?php } else { ?>
						<div class="datagrid">
						<table class="lentele">
						<thead>
							<tr>
								<th>Užsakymo numeris:</th>
								<th>Užsakymo data:</th>
								<th>Užsakymo būklė:</th>
								<th>Apie užsakymą:</th>
							</tr>
						</thead>
						<tbody>
						<?php //Imami irasai is lenteles kol yra irasu
						while($irasas = mysqli_fetch_assoc($irasai)): ?>
						<tr>
							<td> <?php echo "<div style='text-align:left'>". $irasas['uzsakymoNr']."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $irasas['uzsakymoData']."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $irasas['busena']."</div>"; ?> </td>
							<td> <a href="uzsakymu_istorijos_perziura.php?id=<?php echo $irasas['uzsakymoNr']; ?>" <?php echo "<div style='text-align:left'>"."daugiau..."."</div>"; ?> </a> 
						</tr>
						<?php endwhile; ?>
						</tbody>
						</table>
						</div>
						<?php } ?>
						<br>
						
					<p style='padding-left : 30px'>Užsakymų apskaita</p>
					
						<div style="text-align:left;color:green;font-size: 16px">
                            <p style='padding-left : 30px'>Išsirinkite užsakymų apskaitos laikotarpį</p>
                                <div class="container">
                                    <form method='post'>
                                        <div class="form-group col-md-2">
                                            <label for="vardas" class="control-label">Nuo</label>
                                            <input name='vardas' id='vardas' type='date' class="form-control input-sm" required="true">
                                        </div>
                                        <div class="form-group col-md-2">
											<label for="epastas" class="control-label">Iki</label>
											<input name='epastas' id="epastas" type='date' class="form-control input-sm" required="true">
                                        </div>
										<br>
                                        <div class="form-group">
                                            <input type='submit' name='Submit2' value='rodyti' class="btn btn-default">
                                        </div>
                                    </form>
								</div>
						</div>
						
						<?php if (isset($_POST['Submit2'])) { 
						$date=$_POST["vardas"];
						$date2=$_POST["epastas"]; ?>
						<div class="datagrid">
						<table class="lentele">
						<thead>
							<tr>
								<th>Užsakovas:</th>
								<th>Data nuo:</th>
								<th>Data iki:</th>
								<th>Užsakymų kiekis:</th>
								<th>Užsakymų suma:</th>
							</tr>
						</thead>
						<tbody>
						<tr>
							<td> <?php echo "<div style='text-align:left'>". $vardas. " ". $pavarde."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $date."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $date2."</div>"; ?> </td>
							<td> <?php echo "<div style='text-align:left'>". $kiekis."</div>"; ?> </td>
							<td> <?php if ($visoSuma > 0) { echo "<div style='text-align:left'>". $visoSuma."€"."</div>";
									} else { echo "<div style='text-align:left'>"."0€"."</div>";
									}?> </td>
						</tr>
						</tbody>
						</table>	
                </div>
				<?php } ?>
				<br>
                <?php
                //Jei vartotojas neprisijungęs, rodoma prisijungimo forma
                //Jei atsiranda klaidų, rodomi pranešimai.
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