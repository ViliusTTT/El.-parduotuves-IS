<?php
ini_set('display_errors', 1);
include("include/session.php");
include("include/prekiuAdm.php");
if ($session->logged_in) {
    ?>
	<?php
		global $database;
        $helper = new BrazzersHelper();
		$user = $session->username;
		$warehouses = $helper->getAllWarehouses();
        if(isset($_POST['deleteItem']) and is_numeric($_POST['deleteItem'])){
            $amount = $helper->getAmmountAtWh($_POST['deleteItem']);
            if($amount == 0){
                $helper->deleteWh($_POST['deleteItem']);
                header("Location: warehouses.php");
            }else{
                $error = "Sandelyje yra prekiu!";
            }
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
                        [<a href="warehouseEdit.php?id=-1">Naujas sandelys</a>]
                        <?php
                            if(isset($error)){
                                echo "<div class='alert alert-danger'>
                                          <strong>Negalima!</strong> ".$error."
                                        </div>";
                            }
                        ?>
                        <form action="" method="post">
    						<table class="table table-hover" style="margin-top: 40px">
    							<tr>
    								<th>Adresas</th>
    								<th>Talpa</th>
    								<th>Kontaktinis_nr</th>
    								<th>Miestas</th>
                                    <th>Imone</th>
                                    <th style="width:10%;">Veiksmai</th>
    							</tr>

    							<?php
                                if(sizeof($warehouses)!=0){
                                    foreach($warehouses as $wh){
                                        $imon = $helper->getCompany($wh['id']);
                                        echo "<tr style='text-align: center'>";
                                        echo "<td>".$wh['Adresas']."</td>";
                                        echo "<td>".$wh['Talpa']."</td>";
                                        echo "<td>".$wh['Kontaktinis_nr']."</td>";
                                        echo "<td>".$wh['Miestas']."</td>";
                                        echo "<td>".$imon['imones_pavadinimas']."</td>";
                                        //if($session->isClient()){
                                        echo "<td>
                                        <button type='submit' name='deleteItem' value='".$wh['id']."'>DELETE</button>
                                        <a href='warehouseEdit.php?id=".$wh['id']."'><img src='pictures/editbut.jpg' /></a></td>";
                                        //}
                                        echo "</tr>";
                                    }
                                }
    							?>
    						</table>
                        </form>
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
?>
