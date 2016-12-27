<?php
include("include/session.php");
if ($session->logged_in) {
    ?>
	<?php
		Global $database;
		$user = $session->username;
		if($session->isEmployee())
			$sql = "SELECT `imone`.`imones_kodas` FROM `imone`,`darbuotojas` WHERE `darbuotojas`.`fk_Imoneid_Imone` = `imone`.`imones_kodas` &&  `darbuotojas`.`slapyvardis` = '{$user}'";
		else 
		{
			$sql = "SELECT `tiekejas`.`imones_kodas`
			FROM `tiekejas`, `tiekejoatstovas`, `klientas`
			WHERE `tiekejas`.`imones_kodas` = `tiekejoatstovas`.`fk_Tiekejas`&&
			`tiekejoatstovas`.`fk_Darbuotojasid_Darbuotojas` = `klientas`.`slapyvardis` &&
			`klientas`.`slapyvardis` = '{$user}'";
		}
		$imone = $database->query($sql);
		$iKodas = $imone->fetch_array()['imones_kodas'];
		if($session->isEmployee())
		{
			$sql ="SELECT `prekiusarasas`.*, `sandeliai`.* FROM `prekiusarasas`, `sandeliai` WHERE `sandeliai`.`fk_Imoneid_Imone` ='{$iKodas}' && `sandeliai`.`id`=`prekiusarasas`.`fk_ImonesSandelys`";
		}
		else{
			$sql ="SELECT `prekiusarasas`.*, `sandeliai`.* FROM `prekiusarasas`,`sandeliai` WHERE `sandeliai`.`id`=`prekiusarasas`.`fk_ImonesSandelys` && `prekiusarasas`.`fk_Tiekejas`='{$iKodas}'";
		}
		$sutartis = $database->query($sql);
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
		table {
			border-collapse: collapse;
		}
		th{
			text-align: center;
		}
		</style>
		</script>
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
						<?php if($session->isEmployee()){?>[<a href="editpristatymas.php?id=-1&kodas=<?php echo $iKodas; ?>&leid=<?php if($session->isClient()) echo "0"; else echo "1"; ?>">Naujas užsakymas</a>]<?php }?>
						<table style="width:100%; text-align: center;">
							<tr style="border-bottom: 2px solid #ddd;">
								<th>Užsakymo kodas</th>
								<th>Užsakymo data</th>
								<th>Pageidaujamo pristatymo data</th>
								<th>Pristatymo data</th>
								<th>Pristatymas atšauktas</th>
								<th>Pristatymo adresas</th>
								<th style="width:10%;">Redagavimas</th>
							</tr>
							
							<?php
							$num_rows = mysqli_num_rows($sutartis);
							for ($i = 0; $i < $num_rows; $i++) {
								$info = mysqli_result($sutartis, $i);
								
								?>
								<tr style="border-bottom: 2px solid #ddd;" <?php if(isset($info['nutData'])) echo "bgcolor='#FF0000';"; if($info['atsauktas'] == 1) echo "bgcolor='#FF5111';";?> >
									<td><?php echo $info['uzsakymoKodas'];?></td>
									<td><?php echo $info['uzsakymoData'];?></td>
									<td><?php echo $info['norimasPristatymas'];?></td>
									<td><?php if($info['pristatymoData'] != "") echo $info['pristatymoData']; else echo "nepristatyta";?></td>
									<td><?php if($info['atsauktas'] == 0) echo "neatšauktas"; else echo "atšauktas";?></td>
									<td><?php echo "{$info['Miestas']} {$info['Adresas']}";?></td>
									<td>
									<?php if($session->isClient() && $info['atsauktas'] == 0 && $info['pristatymoData'] == ""){?><a href="pristatymas.php?id=<?php echo $info['uzsakymoKodas'] ?>"><img src="pictures/prisbut.jpg" /></a><?php }?>
									<?php if($info['pristatymoData'] == "" && $info['atsauktas'] == 0){?><a href="editpristatymas.php?id=<?php echo $info['uzsakymoKodas'] ?>&kodas=<?php echo $iKodas; ?>&leid=<?php if($session->isClient()) echo "0"; else echo "1"; ?>"><img src="pictures/editbut.jpg" /></a><?php }?>
									<?php if($info['atsauktas'] == 0 && $info['pristatymoData'] == "") {?><a href="delete.php?id=<?php echo $info['uzsakymoKodas'] ?>&tip=4"><img src="pictures/delbut.jpg" /></a><?php }?>
									</td>
								</tr>
								<?php
							}
							?>
						</table>
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
function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow; 
}
?>