<?php
include("include/session.php");
	$id = $_GET['e'];
	$deleteUzs = $_GET['deluzsakymas'];
	$dbc = mysqli_connect('db.if.ktu.lt', 'donvos', 'joh8Ida8taishahj', 'donvos');
	if(!$dbc ){
		die('Negaliu prisijungti: '.mysqli_error($dbc));
	}
	if (mysqli_connect_errno()) {
		die('Connect failed: '.mysqli_connect_errno().' : '.
		mysqli_connect_error());
	}
	
	if($id == 1) {
		//MySqli Delete Query
		$results = mysqli_query($dbc, "DELETE FROM `uzsakymas` WHERE `uzsakymas`.`uzsakymoNr`='$deleteUzs'");

		if($results){ ?>

		<html>
		<body style="background-color:orange;">
		<head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8"/>
        <title>Drym Tym elektroninė parduotuvė</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet"
			href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	
		</head>
		<body> 

        <table class="center" ><tr><td>
           <center><img src="pictures/top.png"/></center>
        </td></tr><tr><td> 
            <?php
			
            //Jei vartotojas prisijunges
            if ($session->logged_in) {
                include("include/meniu.php");
                ?>
                    <table style="border-width: 2px; border-style: dotted;">
                        <tr>
                            <td>
                                [<a href="uzsakymu_istorijos_apskaita.php">Atgal į užsakymų istoriją</a>]
                            </td>
                        </tr>
                    </table>
			<div style="text-align:left;color:green;font-size: 24px">
                    <br><br>
                    <p style='padding-left:30px; padding-right:30px'>Užsakymas #<?php echo $deleteUzs; ?> buvo sėkmingai ištrintas!</p>
					<br>
                <?php
                //Jei vartotojas neprisijungęs, rodoma prisijungimo forma
                //Jei atsiranda klaidų, rodomi pranešimai.
            } else {
                echo "<div align=\"center\">";
                if ($form->num_errors > 0) {
                    echo "<font size=\"3\" color=\"#ff0000\">Klaidą: " . $form->num_errors . "</font>";
                }
                echo "<table class=\"center\"><tr><td>";
                include("include/prisijungimas.php");
                echo "</td></tr></table></div><br></td></tr>";
				
            } ?>
			<?php
            echo "<tr><td>";
			
            include("include/footer.php");
            echo "</td></tr>";
            ?>		
</table>
</body>
</html>		
		<?php } else {
		print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
		}
	}
?>