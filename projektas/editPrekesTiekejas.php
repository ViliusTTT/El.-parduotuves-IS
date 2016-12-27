<?php
include("include/session.php");
if ($session->logged_in) {
    ?>
	<?php
		Global $database;
		$sql = "SELECT *
		FROM `prekestiekejo`
		WHERE `prekestiekejo`.`fk_TiekejoSandeliai` = '{$_GET['id']}'";
		$prekes = $database->query($sql);
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
						[<a href="editPrekesTiekejo.php?id=-1&san=<?php echo $_GET['id'] ?>">Nauja prekė</a>]
						<table style="width:100%; text-align: center;">
							<tr style="border-bottom: 2px solid #ddd;">
								<th>Prekės kodas</th>
								<th>Pavadinimas</th>
								<th>Galiojimas</th>
								<th>Kaina</th>
								<th>Kiekis</th>
								<th>Įpatumai</th>
								<th style="width:10%;">Redagavimas</th>
							</tr>
							
							<?php
							$num_rows = mysqli_num_rows($prekes);
							for ($i = 0; $i < $num_rows; $i++) {
								$id = mysqli_result($prekes, $i, "id");
								$prekesKodas = mysqli_result($prekes, $i, "prekesKodas");
								$pavadinimas = mysqli_result($prekes, $i, "Pavadinimas");
								$galiojimas = mysqli_result($prekes, $i, "Galiojimas");
								$kaina = mysqli_result($prekes, $i, "Kaina");
								$kiekis = mysqli_result($prekes, $i, "Kiekis");
								$sql = "SELECT `tipaitiekejo`.`Pavadinimas` FROM `tipaitiekejo`, `turi3` WHERE `turi3`.`fk_tipaiTiekejo`=`tipaitiekejo`.`id` && `turi3`.`fk_PrekesTiekejo` = '{$id}'";
								$ipatumai = $database->query($sql);
								?>
								<tr style="border-bottom: 2px solid #ddd;">
									<td><?php echo $prekesKodas;?></td>
									<td><?php echo $pavadinimas;?></td>
									<td><?php echo $galiojimas;?></td>
									<td><?php echo $kaina;?></td>
									<td><?php echo $kiekis;?></td>
									<td><?php
									$num = mysqli_num_rows($ipatumai);
									for ($j = 0; $j < $num; $j++) {
										$pav = mysqli_result($ipatumai, $j, "Pavadinimas");
										echo "$pav ";
									}
									?></td>
									
									<td style="width:10%;">
									<a href="delete.php?id=<?php echo $id ?>&tip=2"><img src="pictures/delbut.jpg" /></a>
									<a href="editPrekesTiekejo.php?id=<?php echo $id ?>&san=<?php echo $_GET['id'] ?>"><img src="pictures/editbut.jpg" /></a>
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