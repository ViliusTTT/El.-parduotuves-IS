<?php
include("../include/session.php");

//Iš pradžių aprašomos funkcijos, po to jos naudojamos.


function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow[$field]; 
} 

function displayPareigos() {
    global $database;
    $q = "SELECT name,id_PareigosTipai  "
            . "FROM " . TBL_PAREIGOS_TIPAI . " ORDER BY id_PareigosTipai";
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
    echo "<tr><td><b>Pareigos pavadinimas</b></td><td><b>Pareigos id</b></td></tr>\n";
    for ($i = 0; $i < $num_rows; $i++) {
		$uname4 = mysqli_result($result, $i, "name");
        $uname = mysqli_result($result, $i, "id_PareigosTipai");

        echo "<tr><td>$uname4</td><td>$uname</td></tr>\n";
    }
    echo "</table><br>\n";
}
function displayEmployees() {
    global $database;
    $q = "SELECT slapyvardis,vardas,pavarde, prisijungimo_data,id,pareigos "
            . "FROM " . TBL_DARBUOTOJAI . " ORDER BY id";
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
    echo "<tr><td><b>Id</b></td><td><b>Slapyvardis</b></td><td><b>Vardas</b></td><td><b>Pavardė</b></td><td><b>Pareigos</b></td><td><b>Veiksmai</tr>\n";
    for ($i = 0; $i < $num_rows; $i++) {
		$uname4 = mysqli_result($result, $i, "id");
        $uname = mysqli_result($result, $i, "slapyvardis");
        $uname2 = mysqli_result($result, $i, "vardas");
		$uname3 = mysqli_result($result, $i, "pavarde");
		$uname5 = mysqli_result($result, $i, "pareigos");

        echo "<tr><td>$uname4</td><td>$uname</td><td>$uname2</td><td>$uname3</td><td>$uname5</td>
		<td> <a href='AdminProcess.php?e=1&deluser=$uname' onclick='return confirm(\"Ar tikrai norite trinti?\");'>Trinti</a></td></tr>\n";
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
            <title>Darbuotojų informacija</title>
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
                                    Atgal į [<a href="../index.php">Pradžia</a>]</tr></td>
									   Atgal į [<a href="../admin/admin.php">Administratoriaus sąsają</a>]<tr><td>
                                </td></tr></table>               
                        <br> 
                        <?php
                        if ($form->num_errors > 0) {
                            echo "<font size=\"4\" color=\"#ff0000\">"
                            . "!*** Error with request, please fix</font><br><br>";
                        }
                        ?>
                        <table style=" text-align:left;" border="0" cellspacing="5" cellpadding="5">
                            <tr><td>
							  <h3>Pareigos:</h3>
                                    <?php
                                    displayPareigos();
                                    ?>
									</tr></td>
                                    <?php
                                    /**
                                     * Display Users Table
                                     */
									 
                                    ?>
									 <tr><td>
                                    <h3>Darbuotojai:</h3>
                                    <?php
                                    displayEmployees();
                                    ?>
                                   
                                </td>
                            </tr>
      
                            
                    </td></tr>
                        
                          
                    </td></tr>
				

            </td></tr>
                      
        <tr>
            <td>
               
        
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