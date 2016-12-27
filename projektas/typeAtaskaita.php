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
		$types = $helper->getAllTypes();
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
            <style>
    		table {
    			border-collapse: collapse;
    		}
    		th{
    			text-align: center;
    		}
    		</style>
        </head >
        <body style="background-color:orange;">
            <table class="center" style="width:80%;"><tr><td>
				<center><img src="pictures/top.png"/></center>
				</td></tr>
				<tr><td>
                        <?php
                        include("include/meniu.php");
                        ?>
                        <h2 style="margin-left: 10px; margin-bottom: 30px">Tipų ataskaita</h2>
                        <?php
                        if(sizeof($types)!=0){
                            foreach($types as $type){
                                    $sum = $helper->getSumOfType($type['id']);
                                    $avg = $helper->getTypeAvg($type['id']);
                                    echo "<div style='width: 70%; margin: 10px 30px 5px 30px'>";
                                    echo "<h4>Tipo pavadinimas: <b>".$type['Pavadinimas']."</b></h4>";
                                    if(is_null($sum['sumKaina']) || is_null($avg['avgKaina']) ){
                                        echo "Sis tipas neturi prekiu.";
                                    }else {
                                        echo "Tipo prekiu kainos Suma: <b>".$sum['sumKaina']." €</b>, Tipo prekiu kainos Vidurkis: <b>".$avg['avgKaina']." €</b>";
                                    }
                                    echo "</div>";
                                    $items = $helper->getTypeItems($type['id']);
                                    ?>
                                    <h5 style="margin-left: 10px">Tipui priklausančio prekės</h5>
                                    <table class="table table-hover" style="margin-top: 40px; margin-bottom: 50px">
            							<tr>
            								<th>Prekės Kodas</th>
            								<th>Pavadinimas</th>
            								<th>Galiojimas</th>
            								<th>Kaina</th>
                                            <th>Kiekis</th>
                                            <th>Sandėlis</th>
            							</tr>

            							<?php
                                        if(sizeof($items)!=0){
                                            foreach($items as $item){
                                                $sand = $helper->getWarehouseForItem($item['id']);
                                                echo "<tr style='text-align: center'>";
                                                echo "<td>".$item['prekesKodas']."</td>";
                                                echo "<td>".$item['prek']."</td>";
                                                echo "<td>".$item['Galiojimas']."</td>";
                                                echo "<td>".$item['Kaina']." €</td>";
                                                echo "<td>".$item['Kiekis']."</td>";
                                                echo "<td>".$sand['Adresas']."</td>";
                                                echo "</tr>";
                                            }
                                        }else{
                                            echo "<td colspan='6'>
                                                Sitas tipas neturi prekiu.
                                            </td>";
                                        }
            							?>
            						</table>
                                    <hr /><?php
                            }
                        } ?>
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
