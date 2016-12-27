<?php
include("include/session.php");
if ($session->logged_in) {
	$user = $session->username;
	if($_GET["id"] != -1) 
	{
		$sql="SELECT * FROM `sandeliaitiekejo` WHERE `sandeliaitiekejo`.`id`='{$_GET["id"]}'";
		$res = $database->query($sql);
		$sandeliai = $res->fetch_array(); 
	}
	if(!isset($_POST['randcheck'])) {$_POST['randcheck'] = 0; $_SESSION['rand'] = 1;}
	if(isset($_POST['ok'])&& $_POST['randcheck']==$_SESSION['rand'])
	{
		
		$sql = "SELECT `tiekejoatstovas`.`fk_Tiekejas` as tiekejas FROM `tiekejoatstovas` WHERE  `tiekejoatstovas`.`fk_Darbuotojasid_Darbuotojas` = '{$user}'";
		$duom = $database->query($sql);
		$imone = $duom->fetch_array();
		if($_GET["id"] == -1)$sql="INSERT INTO `sandeliaitiekejo`(`Adresas`, `Talpa`, `Kontaktinis_nr`, `Miestas`, `fk_Tiekejas`) VALUES ('{$_POST['adresas']}','{$_POST['talpa']}','{$_POST['numeris']}','{$_POST['miestas']}','{$imone['tiekejas']}')";
		else $sql = "UPDATE `sandeliaitiekejo` SET `Adresas`='{$_POST['adresas']}',`Talpa`='{$_POST['talpa']}',`Kontaktinis_nr`='{$_POST['numeris']}',`Miestas`='{$_POST['miestas']}',`fk_Tiekejas`='{$imone['tiekejas']}' WHERE `id`='{$_GET["id"]}'";
		$database->query($sql);
		header("Location: tiekejo_sandelio_administravimas.php");
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
        </head >
		<style>
		</style>
        <body style="background-color:orange;">
            <table class="center" style="width:80%;"><tr><td>
				<center><img src="pictures/top.png"/></center>
				</td></tr>
				<tr><td> 
				<?php
                include("include/meniu.php");
                ?>              
                <table style="border-width: 2px; border-style: dotted;">
					<tr><td>
						Atgal į [<a href="index2.php">Pradžia</a>]
					</td></tr>
				</table>
				<br>
				<div class="container" style="text-align: center;color:green">
					<form method="POST" class="login">
						<?php
						$rand=rand();
						$_SESSION['rand']=$rand;
						?>
						<input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
						<Table style="width:70%">
						<tr>
							<th style="text-align:Right">Sandėlio numeris </th> <th style="text-align:Left"><?php echo ": {$sandeliai['id']}";?></th>
						</tr>
						<tr>
							<td style="text-align:Right">Miestas</td> 
							<td style="text-align:Left">
							<input name='miestas' id='miestas' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($sandeliai))echo $sandeliai['Miestas'];?>">
							</td>
						</tr>
						<tr>
							<td style="text-align:Right">Adresas</td> 
							<td style="text-align:Left">
							<input name='adresas' id='adresas' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($sandeliai))echo $sandeliai['Adresas'];?>">
							</td>
						</tr>
						<tr>
							<td style="text-align:Right">Talpa</td> 
							<td style="text-align:Left">
							<input name='talpa' id='talpa' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($sandeliai))echo $sandeliai['Talpa'];?>">
							</td>
						</tr>
						<tr>
							<td style="text-align:Right">Kontaktinis numeris</td> 
							<td style="text-align:Left">
							<input name='numeris' id='numeris' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($sandeliai))echo $sandeliai['Kontaktinis_nr'];?>">
							</td>
						</tr>
						</Table>
						<div>
							<input type='submit' name='ok' value='Patvirtinti' class="btn btn-default">
						</div>
					</form>
				</div>
				<br>
				<div style="text-align:center">
					<a href="editPrekesTiekejas.php?id=<?php echo $_GET['id']; ?>">Prekių sąrašas</a>
				</div>
				<br>
                <?php include("include/footer.php"); ?>
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