<?php
include("include/session.php");
?>
<html>

<body style="background-color:orange;">

    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8" />
        <title>Drym Tym elektroninė parduotuvė</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </head>

    <body>

        <table class="center">
            <tr>
                <td>
                    <center><img src="pictures/top.png" /></center>
                </td>
            </tr>
            <tr>
                <td>
            <?php
			
            //Jei vartotojas prisijungęs
            if ($session->logged_in) {
                include("include/meniu.php");
                ?>
<div style="text-align: center;color:green">
    <br><br>
    <h1>Sveiki prisijungę prie parduotuvės</h1>
    <center><img src="pictures/pic.jpg" /></center>
</div><br>
                <?php
                //Jei vartotojas neprisijungęs, rodoma prisijungimo forma
                //Jei atsiranda klaidų, rodomi pranešimai.
            } else {
                echo "<div align=\"center\">";
                if ($form->num_errors > 0) {
                    echo "<font size=\"3\" color=\"#ff0000\">Klaidų: " . $form->num_errors . "</font>";
                }
                echo "<table class=\"center\"><tr><td>";
                include("include/prisijungimas.php");
                echo "</td></tr></table></div><br></td></tr>";
				
            }?>
			<?php
            echo "<tr><td>";
            include("include/footer.php");
            echo "</td></tr>";
            ?>
			
		</table>
</body>
</html>