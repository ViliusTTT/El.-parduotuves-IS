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
	 function getDarbuotojai() {
		global $database;
		$query = "  SELECT *
					FROM " . TBL_DARBUOTOJAI . " ORDER BY ID";
		$data = mysqli_query($database->connection,$query);
		return $data;
	 }
function displayEmployees() {
    global $database;
    $q = "SELECT slapyvardis,vardas,pavarde, prisijungimo_data,id "
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
    echo "<tr><td><b>Id</b></td><td><b>Slapyvardis</b></td><td><b>Vardas</b></td><td><b>Pavardė</b></td></tr>\n";
    for ($i = 0; $i < $num_rows; $i++) 
	{
		$uname4 = mysqli_result($result, $i, "id");
        $uname = mysqli_result($result, $i, "slapyvardis");
        $uname2 = mysqli_result($result, $i, "vardas");
		$uname3 = mysqli_result($result, $i, "pavarde");
        echo "<tr><td>$uname4</td><td>$uname</td><td>$uname2</td><td>$uname3</td>
		</tr>\n";
    }
    echo "</table><br>\n";
}
function displayWages() {
    global $database;
    $q = "SELECT kiekis,data,id, fk_Darbuotojasid_Darbuotojas "
            . "FROM " . TBL_ALGOLAPIAI . " ORDER BY id";
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
    echo "<tr><td><b>Suma</b></td><td><b>Data</b></td><td><b>Darbuotojo id</b></td></tr>\n";
    for ($i = 0; $i < $num_rows; $i++) {
        $uname = mysqli_result($result, $i, "kiekis");
        $uname2 = mysqli_result($result, $i, "data");
		$uname3 = mysqli_result($result, $i, "fk_Darbuotojasid_Darbuotojas");
        echo "<tr><td>$uname</td><td>$uname2</td><td>$uname3</td>
		</tr>\n";
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
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8" />
        <title>Algolapių administravimas</title>
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
                        <br>
                        <?php
                        if ($form->num_errors > 0) {
                            echo "<font size=\"4\" color=\"#ff0000\">"
                            . "!*** Error with request, please fix</font><br><br>";
                        }
                        ?>
                            <table style=" text-align:left;" border="0" cellspacing="5" cellpadding="5">
                                <tr>
                                    <td>
                                            <h3>Darbuotojai:</h3>
                                            <?php
											displayEmployees();
											?>

                                    </td>
                                </tr>


                                </td>
                            </tr>



                                <tr>
                                    <td>
                                        <h3>Paskirti algolapiai:</h3>
                                        <?php
										displayWages();
										?>
                                    </td>
                                </tr>


                            </table>
                            <h3>Pervesti algolapį:</h3>
                            <form class="login" action="addWage.php" method="get">
                                <div class="form-group col-lg-6">
                                    <label for="suma" class="control-label">Pinigų suma:</label>
                                    <input name='suma' id='suma' type='number_format' class="form-control input-sm" required="true">
                                </div>
                                <p style="text-align:left;">Darbuotojas:
                                    <select id="brand1" name="darbuotojas">
					<option value="-1">Pasirinkite darbuotoją</option>
					<?php
						// išrenkame visas markes
						$brands = getDarbuotojai();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['darbuotojas']) && $fields['darbuotojas'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['pavarde']}</option>";
							$dId=$val['id'];
						}
						

					?>
									</select>

                                    <input type="submit" value="Pridėti algolapį" />
                            </form </td>
            </tr>

<?php
echo "<tr><td>";
include("../include/footer.php");
echo "</td></tr>";?>
        </table>
    </body>
</html>
    <?php
}
?>