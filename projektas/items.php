<?php
ini_set('display_errors', 1);
include("include/session.php");
include("include/prekiuAdm.php");
include("include/krepselis.php");
if ($session->logged_in) {
    ?>
	<?php
		global $database;
        $helper = new BrazzersHelper();
		$user = $session->username;
		$items = $helper->getAllItems();
        if(isset($_POST['deleteItem']) and is_numeric($_POST['deleteItem'])){
            $helper->deleteItem($_POST['deleteItem']);
            header("Location: items.php");
        }
				
		if(isset($_POST['suggestItem']) || isset($_GET['removeCart'])) {
			if (isset($_GET['removeCart'])) {
				$krepselis->removeCart($_GET['removeCart']);
				header("Location: items.php");
			} else {
				if (!isset($_SESSION['selectedItems@'.$_SESSION['username']]))
					$_SESSION['selectedItems@'.$_SESSION['username']] = [];
				$krepselis->setSessionItem($_POST);
				// header("Location: items.php");
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
                        <form action="" method="post">
						<table class="table table-hover" style="margin-top: 40px">
							<tr>
								<th>Prekes Kodas</th>
								<th>Pavadinimas</th>
								<th>Galiojimas</th>
								<th>Kaina</th>
                                <th>Kiekis</th>
                                <th>Sandelis</th>
                                <th style="width:10%;">Veiksmai</th>
							</tr>

							<?php
                            if(sizeof($items)!=0){
                                foreach($items as $item){
                                    $sand = $helper->getWarehouseForItem($item['id']);
                                    echo "<tr style='text-align: center'>";
                                    echo "<td>".$item['prekesKodas']."</td>";
                                    echo "<td>".$item['Pavadinimas']."</td>";
                                    echo "<td>".$item['Galiojimas']."</td>";
                                    echo "<td>".$item['Kaina']." €</td>";
                                    echo "<td>".$item['Kiekis']."</td>";
                                    echo "<td>".$sand['Adresas']."</td>";
									echo "<td><button type='submit' name='suggestItem' value='".json_encode($item)."'>Į krepšelį</button></td>";
                                    if($session->isEmployee()){
                                        echo "<td>
                                            <button type='submit' name='deleteItem' value='".$item['id']."'>DELETE</button>
                                            <a href='editItems.php?id=".$item['id']."'><img src='pictures/editbut.jpg' /></a>
                                        </td>";
                                    }
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
