<?php
include("include/session.php");
if ($session->logged_in) {
    ?>
	<?php
		Global $database;
		$user = $session->username;
		$sql = "SELECT `sandeliaitiekejo`.`id`,`sandeliaitiekejo`.`Adresas`, `sandeliaitiekejo`.`Talpa`, `sandeliaitiekejo`.`Kontaktinis_nr`, `sandeliaitiekejo`.`Miestas` 
		FROM `sandeliaitiekejo` , `tiekejas`, `tiekejoatstovas`, `klientas`
		WHERE `sandeliaitiekejo`.`fk_Tiekejas` = `tiekejas`.`imones_kodas`&&
		`tiekejas`.`imones_kodas` = `tiekejoatstovas`.`fk_Tiekejas`&&
		`tiekejoatstovas`.`fk_Darbuotojasid_Darbuotojas` = `klientas`.`slapyvardis` &&
		`klientas`.`slapyvardis` = '{$user}'";
		$sandeliai = $database->query($sql);
		
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
						[<a href="editTiekejosandelys.php?id=-1">Naujas sandėlys</a>]
						<table style="width:100%; text-align: center;">
							<tr style="border-bottom: 2px solid #ddd;">
								<th>Miestas</th>
								<th>Adresas</th>
								<th>Talpa</th>
								<th>Kont. nr.</th>
								<th style="width:10%;">Redagavimas</th>
							</tr>
							
							<?php
							$num_rows = mysqli_num_rows($sandeliai);
							for ($i = 0; $i < $num_rows; $i++) {
								$id = mysqli_result($sandeliai, $i, "id");
								$adresas = mysqli_result($sandeliai, $i, "Adresas");
								$talpa = mysqli_result($sandeliai, $i, "Talpa");
								$kontaktinisnr = mysqli_result($sandeliai, $i, "Kontaktinis_nr");
								$miestas = mysqli_result($sandeliai, $i, "Miestas");
								?>
								<tr style="border-bottom: 2px solid #ddd;">
									<td><?php echo $miestas;?></td>
									<td><?php echo $adresas;?></td>
									<td><?php echo $talpa;?></td>
									<td><?php echo $kontaktinisnr;?></td>
									
									<td>
									<a href="delete.php?id=<?php echo $id ?>&tip=1"><img src="pictures/delbut.jpg" /></a>
									<a href="editTiekejosandelys.php?id=<?php echo $id ?>"><img src="pictures/editbut.jpg" /></a>
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
    return $datarow[$field]; 
}
?>