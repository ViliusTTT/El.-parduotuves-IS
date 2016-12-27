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
		$sql = "SELECT * FROM `tiekimokontraktas` WHERE `tiekimokontraktas`";
		if($session->isEmployee())
		{$sql .= ".`fk_Imone`";} 
		else 
		{$sql .= ".`fk_Tiekejas`";} 
		$sql.="='{$iKodas}'";
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
						[<a href="editSutartis.php?id=-1&kodas=<?php echo $iKodas; ?>">Nauja sutartis</a>]
						<table style="width:100%; text-align: center;">
							<tr style="border-bottom: 2px solid #ddd;">
								<th>Sutarties kodas</th>
								<th>Par. atstovo vardas</th>
								<th>Par. atstovo pavarde</th>
								<th>Tiek. atstovo vardas</th>
								<th>Tiek. atstovo pavarde</th>
								<th>Sud. data</th>
								<th>Nut. data</th>
								<th>Miestas</th>
								<th style="width:10%;">Redagavimas</th>
							</tr>
							
							<?php
							$num_rows = mysqli_num_rows($sutartis);
							for ($i = 0; $i < $num_rows; $i++) {
								$info = mysqli_result($sutartis, $i);
								
								?>
								<tr style="border-bottom: 2px solid #ddd;" <?php if(isset($info['nutData'])) echo "bgcolor='#FF0000';"?>>
									<td><?php echo $info['sutartiesKodas'];?></td>
									<td><?php echo $info['prVardas'];?></td>
									<td><?php echo $info['prPavarde'];?></td>
									<td><?php echo $info['tkVardas'];?></td>
									<td><?php echo $info['tkPavarde'];?></td>
									<td><?php echo $info['sudData'];?></td>
									<td><?php echo $info['nutData'];?></td>
									<td><?php echo $info['miestas'];?></td>
									<td>
									<a href="delete.php?id=<?php echo $info['sutartiesKodas'] ?>&tip=3"><img src="pictures/delbut.jpg" /></a>
									<a href="editSutartis.php?id=<?php echo $info['sutartiesKodas'] ?>&kodas=<?php echo $iKodas; ?>"><img src="pictures/editbut.jpg" /></a>
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