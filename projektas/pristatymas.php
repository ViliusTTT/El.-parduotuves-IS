<?php
include("include/session.php");
if ($session->logged_in) {
	
	Global $database;
	$id = $_GET['id'];
	$now = date("Y/m/d");
	$sql = "UPDATE `prekiusarasas` SET `pristatymoData`='{$now}' WHERE `uzsakymoKodas`='{$id}'";
	$database->query($sql);
	$sql = "SELECT `pruzsakymas`.*,`prekestiekejo`.`id`  FROM `pruzsakymas`,`prekestiekejo` WHERE `fk_PrekiuSarasasid_PrekiuSarasas`='{$id}'&& `prekestiekejo`.`id`= `pruzsakymas`.`fk_Preke`";
	$nupirktos = $database->query($sql);
	$sql = "SELECT `fk_ImonesSandelys` FROM `prekiusarasas` WHERE `uzsakymoKodas`='{$id}'";
	$raw = $database->query($sql);
	$sand = $raw->fetch_array();
	$sand = $sand['fk_ImonesSandelys'];
	$tur = mysqli_num_rows($nupirktos);
	for ($j = 0; $j < $tur; $j++) {
		$tipai = mysqli_result($nupirktos, $j);
		$sql = "UPDATE `prekestiekejo` SET `Kiekis`=`Kiekis`-'{$tipai['prekiuKiekis']}' WHERE `id`='{$tipai['fk_Preke']}'";
		$database->query($sql);
		$sql = "SELECT * FROM `prekestiekejo` WHERE `id`='{$tipai['fk_Preke']}'";
		$raw = $database->query($sql);
		$prek = $raw->fetch_array();
		$sql = "SELECT `tipaitiekejo`.*  FROM `tipaitiekejo`, `turi3` WHERE `tipaitiekejo`.`id`=`turi3`.`fk_tipaiTiekejo` && `turi3`.`fk_PrekesTiekejo`='{$prek['id']}'";
		$tipas = $database->query($sql);
		$sql = "SELECT * FROM `prekes` WHERE `prekesKodas`='{$prek['prekesKodas']}' && `fk_sandeliaiid_sandeliai`='{$sand}'";
		$raw = $database->query($sql);
		$preksan = $raw->fetch_array();
		$sql = "SELECT * FROM `prekestiekejo` WHERE `id`='{$tipai['fk_Preke']}'";
		$raw = $database->query($sql);
		$prek = $raw->fetch_array();
		if(isset($preksan['prekesKodas']))
		{
		
			 $sql = "UPDATE `prekes` SET `Kiekis`=`Kiekis`+'{$tipai['prekiuKiekis']}' WHERE `prekesKodas`='{$prek['prekesKodas']}' && `fk_sandeliaiid_sandeliai`='{$sand}'";
			 $database->query($sql);
			 
			 $sql = "SELECT `tipai`.*, sum(CASE WHEN `turi2`.`fk_prekesid_prekes`='{$tipai['id']}' and `tipai`.`id`=`turi2`.`fk_tipaiid_tipai` THEN 1 Else 0 END) as turi FROM `tipai`, `turi2` group by `tipai`.`id`";
			 $pardtip = $database->query($sql);
			 
			 $sk = mysqli_num_rows($tipas);
			 $sk2 = mysqli_num_rows($pardtip);
			 
			for ($i = 0; $i < $sk; $i++) {
				$rado = 0;
				$t1 = mysqli_result($tipas, $i);
				for ($k = 0; $k < $sk2; $k++) {
					$t2 = mysqli_result($pardtip, $k);
					if($t1['Pavadinimas'] == $t2['Pavadinimas'] ) 
					{
						$rado = 1;
						if($t2['turi'] != 1) 
						{
							$sql = "SELECT * FROM `prekes` WHERE `prekesKodas`='{$prek['prekesKodas']}'";
							$raw = $database->query($sql);
							$prekespard = $raw->fetch_array();
							$sql = "INSERT INTO `turi2`(`fk_tipaiid_tipai`, `fk_prekesid_prekes`) VALUES ('{$t2['id']}','{$prekespard['id']}')";
							echo $sql;
							$database->query($sql);
						}
					}
				}
				if($rado == 0)
				{
					$sql = "INSERT INTO `tipai`(`Pavadinimas`) VALUES ('{$t1['Pavadinimas']}')";
					echo $sql;
					$database->query($sql);
					$sql = "SELECT * FROM `tipai` WHERE `Pavadinimas`='{$t1['Pavadinimas']}'";
					$raw=$database->query($sql);
					$idas = $raw->fetch_array();
					$sql = "SELECT * FROM `prekes` WHERE `prekesKodas`='{$prek['prekesKodas']}'";
					echo $sql;
					$raw = $database->query($sql);
					$prekespard = $raw->fetch_array();
					$sql = "INSERT INTO `turi2`(`fk_tipaiid_tipai`, `fk_prekesid_prekes`) VALUES ('{$idas['id']}','{$prekespard['id']}')";
					$database->query($sql);
				}
			}

		}
		else 
		{
			$sql = "INSERT INTO `prekes`(`prekesKodas`, `Pavadinimas`, `Galiojimas`, `Kaina`, `Kiekis`, `fk_sandeliaiid_sandeliai`) VALUES ('{$prek['prekesKodas']}','{$prek['Pavadinimas']}','{$prek['Galiojimas']}','{$prek['Kaina']}','{$tipai['prekiuKiekis']}','{$sand}')";
			$database->query($sql);
			$sql = "SELECT `tipai`.* FROM `tipai`";
			echo $sql;
			$pardtip = $database->query($sql);
			 
			$sk = mysqli_num_rows($tipas);
			$sk2 = mysqli_num_rows($pardtip);
			$rado = 0;
			for ($i = 0; $i < $sk; $i++) {
				$t1 = mysqli_result($tipas, $i);
				for ($k = 0; $k < $sk2; $k++) {
					$t2 = mysqli_result($pardtip, $k);
					if($t1['Pavadinimas'] == $t2['Pavadinimas'] ) 
					{
						$sql = "SELECT * FROM `prekes` WHERE `prekesKodas`='{$prek['prekesKodas']}'";
						echo $sql;
						$raw = $database->query($sql);
						$prekespard = $raw->fetch_array();
						$rado = 1;
						$sql = "INSERT INTO `turi2`(`fk_tipaiid_tipai`, `fk_prekesid_prekes`) VALUES ('{$t2['id']}','{$prekespard['id']}')";
						$database->query($sql);
					}
				}
				if($rado == 0)
				{
					$sql = "INSERT INTO `tipai`(`Pavadinimas`) VALUES ('{$t1['Pavadinimas']}')";
					echo $sql;
					$database->query($sql);
					$sql = "SELECT * FROM `tipai` WHERE `Pavadinimas`='{$t1['Pavadinimas']}'";
					$raw=$database->query($sql);
					$idas = $raw->fetch_array();
					$sql = "SELECT * FROM `prekes` WHERE `prekesKodas`='{$prek['prekesKodas']}'";
					$raw = $database->query($sql);
					$prekespard = $raw->fetch_array();
					$sql = "INSERT INTO `turi2`(`fk_tipaiid_tipai`, `fk_prekesid_prekes`) VALUES ('{$idas['id']}','{$prekespard['id']}')";
					echo $sql;
					$database->query($sql);
				}
			}
		}
	}
	header("Location: uzsakymo_administravimas.php");
} else {
    header("Location: index2.php");
}
function mysqli_result($res, $row) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow; 
}
?>
