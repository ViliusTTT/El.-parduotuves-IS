<?php
include("../include/session.php");

//Iš pradžių aprašomos funkcijos, po to jos naudojamos.

/**
 * displayUsers - Displays the users database table in
 * a nicely formatted html table.
 */

function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow[$field]; 
} 

function updateContract($paymentID) {
	global $database;
	$uzsakymoBusena = 4;
	$q = "UPDATE `uzsakymas` SET `uzsakymoBusena`='$uzsakymoBusena',`fk_uzsakymo_busenosid_uzsakymo_busenos`='$uzsakymoBusena' WHERE `fk_mokejimaiid_mokejimai`='$paymentID'";
	$database->query($q);
}

function deletePayment($paymentID) {
	// global $database;
	// $q = "DELETE FROM `mokejimai` WHERE `mokejimoNr`='$paymentID'";
	// $database->query($q);
	updateContract($paymentID);
}

if (isset($_GET['remove'])) {
	$paymentID = $_GET['remove'];
	deletePayment($paymentID);
}

function displayPayments() {
    global $database;
    $q = "SELECT mokejimoNr, galutineKaina, `pristatymo_budai`.`name` as `pristatymoBudas`, vardas, pavarde, elpastas, telefono_nr, atsiemimo_adresas, `uzsakymo_busenos`.`name` as `uzsakymoBusena`" . " FROM " . "`uzsakymas`, `mokejimai`, `uzsakymogavejas`, `pristatymo_budai`, `uzsakymo_busenos`" . " WHERE `mokejimai`.`mokejimoNr`=`uzsakymas`.`fk_mokejimaiid_mokejimai` AND `uzsakymogavejas`.`id`=`uzsakymas`.`fk_uzsakymoGavejasid_uzsakymoGavejas` AND `mokejimai`.`pristatymoBudas`=`pristatymo_budai`.`id_pristatymo_budai` AND `uzsakymas`.`fk_uzsakymo_busenosid_uzsakymo_busenos`=`uzsakymo_busenos`.`id_uzsakymo_busenos` ORDER BY `galutineKaina` DESC";
	$result = $database->query($q);
    /* Error occurred, return given name by default */
    $num_rows = mysqli_num_rows($result);
    if (!$result || ($num_rows < 0)) {
        echo "Error displaying info";
        return;
    }
    if ($num_rows == 0) {
        echo "Lentelė tuščia.";
        return;
    }
    /* Display table contents */
    echo "<table align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "<tr><td><b>Mokėjimo numeris</b></td><td><b>Sumokėta</b></td><td><b>Pristatymo būdas</b></td><td><b>Vardas</b></td><td><b>Pavardė</b></td><td><b>E-Mail</b></td><td><b>Tel. numeris</b></td><td><b>Adresas</b></td><td><b>Būsena</b></td><td><b>Veiksmai</tr>\n";
    for ($i = 0; $i < $num_rows; $i++) {
        $mokejimoNr = mysqli_result($result, $i, "mokejimoNr");
        $galutineKaina = mysqli_result($result, $i, "galutineKaina");
		$pristatymoBudas = mysqli_result($result, $i, "pristatymoBudas");
		$vardas = mysqli_result($result, $i, "vardas");
		$pavarde = mysqli_result($result, $i, "pavarde");
		$email = mysqli_result($result, $i, "elpastas");
		$number = mysqli_result($result, $i, "telefono_nr"); 
		$address = mysqli_result($result, $i, "atsiemimo_adresas");
		$uzsakymoBusena = mysqli_result($result, $i, "uzsakymoBusena");
        echo "<tr><td>$mokejimoNr</td><td>$galutineKaina</td><td>$pristatymoBudas</td><td>$vardas</td><td>$pavarde</td>
		<td>$email</td><td>$number</td><td>$address</td><td>$uzsakymoBusena</td><td><a href='mokejimuSalinimas.php?remove=$mokejimoNr' onclick='return confirm(\"Ar tikrai norite trinti?\");'>Atšaukti</a></td></tr>\n";
    }
    echo "</table><br>\n";
}

if (!$session->isAdmin()) {
    header("Location: ../index.php");
} else { //Jei administratorius
    ?>
    <html>
  <body style="background-color:orange;">
        <head>  
            <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8"/> 
            <title>Mokėjimų informacija</title>
            <link href="../include/styles.css" rel="stylesheet" type="text/css" />
				<link rel="stylesheet"
			href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        </head>  
        <body>
            <table class="center"><tr><td>
                        <img src="../pictures/top.png"/>
                    </td></tr><tr><td> 
                        <?php
                        $_SESSION['path'] = '../';
                        include("../include/meniu.php");
                        //Nuoroda į pradžią
                        ?>
                        <table style="border-width: 2px; border-style: dotted;"><tr><td>
                                    Atgal į [<a href="../index.php">Pradžia</a>]
                                </td></tr>		   Atgal į [<a href="../admin/admin.php">Administratoriaus sąsają</a>]<tr><td></table>               
                        <br> 
                        <?php
                        if ($form->num_errors > 0) {
                            echo "<font size=\"4\" color=\"#ff0000\">"
                            . "!*** Error with request, please fix</font><br><br>";
                        }
                        ?>
                        <table style=" text-align:left;" border="0" cellspacing="5" cellpadding="5">
                            <tr><td>
                                    <?php
                                    /**
                                     * Display Users Table
                                     */
                                    ?>
                                    <h3>Mokėjimai:</h3>
                                    <?php
                                    displayPayments();
                                    ?>
                                   
                                </td>
                            </tr>
      
                            
                    </td></tr>
                        
                          
                    </td></tr>
				

            </td></tr>
                    
                <tr><td><hr></td></tr>
            </td></tr>
        
    </table>
    </td></tr>
    <?php
    echo "<tr><td>";
    include("../include/footer.php");
    echo "</td></tr>";
    ?>
    </table>       
    </body>
    </html>
    <?php
}
?>