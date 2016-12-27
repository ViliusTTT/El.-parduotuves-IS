<?php
include("../include/session.php");

function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow[$field]; 
} 
/**
Išveda prekių sąrašą nuo data iki data2
*/
function displayPrekes($data,$data2) {
    global $database;
    $q = "SELECT uzsakymoNr,uzsakymoData,fk_Klientasid_Klientas, uzsakymoSuma,fk_uzsakymo_busenosid_uzsakymo_busenos
            FROM " . TBL_UZSAKYMAI . " WHERE uzsakymoData>'$data' AND uzsakymoData<'$data2' ORDER BY uzsakymoData";
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

    for ($i = 0; $i < $num_rows; $i++) {
		 
        $uname = mysqli_result($result, $i, "uzsakymoNr");
        $uname2 = mysqli_result($result, $i, "uzsakymoData");
		$uname3 = mysqli_result($result, $i, "fk_Klientasid_Klientas");
		$q2 = "SELECT vardas,pavarde,slapyvardis FROM " . TBL_KLIENTAS . " WHERE id=$uname3 ";
		$result2 = $database->query($q2);
		$record2 = mysqli_fetch_assoc($result2);
		$uname3 = $record2["slapyvardis"];
		$uname7 = $record2["vardas"];
		$uname6 = $record2["pavarde"];
		$uname4 = mysqli_result($result, $i, "uzsakymoSuma");
		$uname5 = mysqli_result($result, $i, "fk_uzsakymo_busenosid_uzsakymo_busenos"); 
		$q3 = "SELECT name FROM uzsakymo_busenos WHERE id_uzsakymo_busenos=$uname5 ";
		$result3 = $database->query($q3);
		$record3 = mysqli_fetch_assoc($result3);
		$uname5 = $record3["name"];
        echo "<tr><td>$uname</td><td>$uname2</td><td>$uname7 $uname6($uname3)</td>
		<td>$uname4 €</td><td>$uname5</td></tr>\n";
    }
	echo "</table><br>\n";
	echo "<b>Iš viso užsakymų  </b>       $num_rows";
    
}

if (!$session->isAdmin()) {
    header("Location: ../index.php");
} else { //Jei administratorius
    ?>
<html>

<body style="background-color:orange;">

    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8" />
        <title>Vartotojų ataskaita</title>
        <link href="../include/styles.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </head>

    <body>
        <table class="center">
            <tr>
                <td>
                    <img src="../pictures/top.png" />
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                        $_SESSION['path'] = '../';
                        include("../include/meniu.php");
                        //Nuoroda į pradžią
                        ?>
                        <table style="border-width: 2px; border-style: dotted;">
                            <tr>
                                <td>
                                    Atgal į [<a href="../index.php">Pradžia</a>]
                                </td>
                            </tr> Atgal į [<a href="../admin/admin.php">Administratoriaus sąsają</a>]
                            <tr>
                                <td>
                        </table>                      
                        <?php
                        if ($form->num_errors > 0) {
                            echo "<font size=\"4\" color=\"#ff0000\">"
                            . "!*** Error with request, please fix</font><br><br>";
                        }               

if (isset($_POST['Submit1'])) {
	$date=$_POST["vardas"];
	$date2=$_POST["epastas"];
//Paimam visus įrašus iš duombazės
global $database;
$q = "SELECT slapyvardis,vardas,pavarde, el_pastas,prisijungimo_data,adresas,tel_numeris,slaptazodis,id 
      FROM klientas where prisijungimo_data <'$date2' AND prisijungimo_data>'$date'";
$q2 = "SELECT COUNT(id) AS kiek FROM klientas where prisijungimo_data <'$date2' AND prisijungimo_data>'$date'";
$q3 = "SELECT slapyvardis,vardas,pavarde,prisijungimo_data,id 
       FROM darbuotojas where prisijungimo_data <'$date2' AND prisijungimo_data>'$date'";
$q4 = "SELECT COUNT(id) AS kiek FROM darbuotojas where prisijungimo_data <'$date2' AND prisijungimo_data>'$date'";
$records = mysqli_query($database->connection, $q);       
$records2 = mysqli_query($database->connection, $q2);
$records3 = mysqli_query($database->connection, $q3);
$records4 = mysqli_query($database->connection, $q4);
$record2 = mysqli_fetch_assoc($records2);
$record3 = mysqli_fetch_assoc($records4);	
}
else{
	$date=0;
	$date2=0;
	global $database;
	$q = "SELECT slapyvardis,vardas,pavarde, el_pastas,prisijungimo_data,adresas,tel_numeris,slaptazodis,id 
            FROM klientas where prisijungimo_data <'0' AND prisijungimo_data>'0'";
	$q2 = "SELECT COUNT(id) AS kiek FROM klientas where prisijungimo_data <'0' AND prisijungimo_data>'0'";
	$q3 = "SELECT slapyvardis,vardas,pavarde,prisijungimo_data,id 
            FROM darbuotojas where prisijungimo_data <'0' AND prisijungimo_data>'0'";
	$q4 = "SELECT COUNT(id) AS kiek FROM darbuotojas where prisijungimo_data <'0' AND prisijungimo_data>'0'";
$records = mysqli_query($database->connection, $q);   
$records2 = mysqli_query($database->connection, $q2);
$records3 = mysqli_query($database->connection, $q3);
$records4 = mysqli_query($database->connection, $q4);
$record2 = mysqli_fetch_assoc($records2);	
$record3 = mysqli_fetch_assoc($records4);	
}
?>

                            <!doctype html>
                            <html>

                            <head>
                                <meta charset="utf-8">
                                <p style="text-align:center;">
                                    <h3>Klientai</h3>
                                </p>
                                <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
                                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
                                <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
                            </head>

                            <body>
                                
                                <div class="container">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Slapyvardis</th>
                                                <th>Vardas</th>
                                                <th>Pavardė</th>
                                                <th>Adresas</th>
                                                <th>Kada prisijungė</th>
                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php //Grąžinami įrašai kol jų yra
				
										while($record = mysqli_fetch_assoc($records)): ?>
                                            <tr>
                                                <td>
                                                    <?php echo iconv("windows-1257", "utf-8", $record["slapyvardis"]);?>
                                                </td>
                                                <td>
                                                    <?php echo iconv("windows-1257", "utf-8", $record["vardas"]);?>
                                                </td>
                                                <td>
                                                    <?php echo iconv("windows-1257", "utf-8", $record["pavarde"]);?>
                                                </td>
                                                <td>
                                                    <?php echo iconv("windows-1257", "utf-8", $record["adresas"]);?>
                                                </td>
                                                <td>
                                                    <?php echo iconv("windows-1257", "utf-8", $record["prisijungimo_data"]);?>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                            <th>Prisiregistravusių iš viso</th>
                                            <td>
                                                <?php echo iconv("windows-1257", "utf-8", $record2["kiek"]);?>
                                            </td>
                                        </tbody>
                                    </table>
                                </div>

                                <body>
                                    <hr>
                                    <p style="text-align:center;">
                                        <h3>Darbuotojai</h3>
                                    </p>
                                    <div class="container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Slapyvardis</th>
                                                    <th>Vardas</th>
                                                    <th>Pavardė</th>
                                                    <th>Kada prisijungė</th>
                                                    <th>Uždirbta alga</th>
                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php //Grąžinami įrašai kol jų yra
				   
													while($record = mysqli_fetch_assoc($records3)): 
													$id=$record["id"];
														$q5=" SELECT SUM(kiekis) AS suma FROM algolapis where fk_Darbuotojasid_Darbuotojas = $id AND data <'2017-01-01'";
														$recordd = mysqli_query($database->connection, $q5);
														$alga = mysqli_fetch_assoc($recordd);	
												?>
                                                <tr>
                                                    <td>
                                                        <?php echo iconv("windows-1257", "utf-8", $record["slapyvardis"]);?>
                                                    </td>
                                                    <td>
                                                        <?php echo iconv("windows-1257", "utf-8", $record["vardas"]);?>
                                                    </td>
                                                    <td>
                                                        <?php echo iconv("windows-1257", "utf-8", $record["pavarde"]);?>
                                                    </td>
                                                    <td>
                                                        <?php echo iconv("windows-1257", "utf-8", $record["prisijungimo_data"]);?>
                                                    </td>
                                                    <td>
                                                        <?php echo iconv("windows-1257", "utf-8", $alga["suma"]);?>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                                <th>Prisiregistravusių iš viso</th>
                                                <td>
                                                    <?php echo iconv("windows-1257", "utf-8", $record3["kiek"]);?>
                                                </td>
                                            </tbody>
                                        </table>
                                    </div>
									<hr>  
                                        <h3>Užsakymai</h3>                               
                                    <div class="container">
                                        <table class="table table-hover">
                                            <thead>
                                                <div class="container">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Užsakymo nr.</th>
                                                                <th>Užsakymo data</th>
                                                                <th>Kas Užsakė</th>
                                                                <th>Užsakymo suma</th>
                                                                <th>Užsakymo būsena</th>
                                                            </tr>
                                                        </thead>

                                                            <?php
																displayPrekes($date,$date2);
															?>
                                            </thead>
													</table>
                                             <table class="table table-hover">
                                                <h3>Nustatyti laikotarpį, kurio duomenis pateikti</h3>
                                                <div class="container">
                                                    <form method='post'>
                                                        <div class="form-group col-lg-6">
                                                            <label for="vardas" class="control-label">Nuo</label>
                                                            <input name='vardas' id='vardas' type='date' class="form-control input-sm" required="true">
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <label for="epastas" class="control-label">Iki</label>
                                                            <input name='epastas' id="epastas" type='date' class="form-control input-sm" required="true">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type='submit' name='Submit1' value='siųsti' class="btn btn-default">
                                                        </div>
                                                    </form>
                                                </div>
                                            </table>
                                </body>

                            </html>
                            </td>
                            </tr>
<?php
 echo "<tr><td>";
 include("../include/footer.php");
 echo "</td></tr>";
?>
                                </table>
							</div>
                        </body>

</html>

    <?php
}
?>