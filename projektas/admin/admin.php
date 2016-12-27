<?php
include("../include/session.php");
if (!$session->isAdmin()) {
    header("Location: ../index.php");
} else { //Jei administratorius
    ?>
 <html>

<body style="background-color:orange;">

    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8" />
        <title>Administratoriaus sąsajos langas</title>
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
						</tr>
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
            <?php
                                    /**
                                     * Display Users Table
                                     */
                                    ?>
                <a href="../admin/vartotojuInfoSalinimas.php">
                    <h3>Sistemos vartotojų informacija</h3>
                </a>


        </td>
    </tr>

    </td>
    </tr>

    <tr>
        <td>

            <a href="../admin/klientuInfoSalinimas.php">
                <h3>Klientų informacija</h3>
            </a>

        </td>
    </tr>
    <tr>
        <td>
            <a href="../admin/darbuotojuInfoSalinimas.php">
                <h3>Darbuotojų informacija</h3>
            </a>

            <tr>
                <td>
                    <a href="../admin/algolapiuAdministravimas.php">
                        <h3>Algolapių administravimas</h3>
                    </a>
                    <tr>
                        <td>
                            <a href="../admin/ataskaita.php">
                                <h3>Vartotojų ir personalo ataskaita</h3>
                            </a>
	<tr>
        <td>

            <a href="../uzsakymu_istorijos_apskaita.php">
                <h3>Klientų užsakymų istorijos</h3>
            </a>

        </td>
    </tr>

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