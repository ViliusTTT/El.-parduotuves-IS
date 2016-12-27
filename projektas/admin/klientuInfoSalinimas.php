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
function displayClients() {
    global $database;
    $q = "SELECT slapyvardis,vardas,pavarde, el_pastas,prisijungimo_data,adresas,tel_numeris,slaptazodis,id "
            . "FROM " . TBL_KLIENTAS . " ORDER BY id";
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
    echo "<tr><td><b>Slapyvardis</b></td><td><b>Vardas</b></td><td><b>Pavardė</b></td><td><b>El paštas</b></td><td><b>Tel numeris</b></td><td><b>adresas</b></td><td><b>Veiksmai</tr>\n";
    for ($i = 0; $i < $num_rows; $i++) {
        $username = mysqli_result($result, $i, "slapyvardis");
        $name = mysqli_result($result, $i, "vardas");
		$lastname = mysqli_result($result, $i, "pavarde");
		$email = mysqli_result($result, $i, "el_pastas");
		$number = mysqli_result($result, $i, "tel_numeris"); 
		$address = mysqli_result($result, $i, "adresas");
        echo "<tr><td>$username</td><td>$name</td><td>$lastname</td>
		<td>$email</td><td>$number</td><td>$address</td><td> <a href='AdminProcess.php?c=1&deluser=$username' onclick='return confirm(\"Ar tikrai norite trinti?\");'>Trinti</a></td></tr>\n";
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
            <title>Klientų informacija</title>
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
                                    <h3>Klientai:</h3>
                                    <?php
                                    displayClients();
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