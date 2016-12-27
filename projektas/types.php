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
        if(isset($_POST['deleteItem']) and is_numeric($_POST['deleteItem'])){
            $helper->deleteType($_POST['deleteItem']);
            header("Location: types.php");
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
                        <table style="border-width: 2px; border-style: dotted;"><tr><td>
                                    Atgal į [<a href="index2.php">Pradžia</a>]
                                </td></tr></table>
                        [<a href="typeEdit.php?id=-1">Naujas tipas</a>]
                        [<a href="typeAtaskaita.php">Ataskaita</a>]
                        <form action="" method="post">
    						<table class="table table-hover" style="margin-top: 40px">
    							<tr>
    								<th>Pavadinimas</th>
                                    <th style="width:10%;">Veiksmai</th>
    							</tr>

    							<?php
                                if(sizeof($types)!=0){
                                    foreach($types as $type){
                                        echo "<tr style='text-align: center'>";
                                        echo "<td>".$type['Pavadinimas']."</td>";
                                        echo "<td>
                                        <button type='submit' name='deleteItem' value='".$type['id']."'>DELETE</button>
                                        <a href='typeEdit.php?id=".$type['id']."'><img src='pictures/editbut.jpg' /></a></td>";
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
