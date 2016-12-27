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

                  
                    <tr>
                        <td>
                            [<a href="index2.php">Prisijungti</a>]
                        </td>
                    </tr>
                    <tr>
                        <td>
                            [<a href="kliento_registracija.php">Naujų klientų registracija</a>]
                        </td>
                    </tr>
                    <tr>
                        <td>
                            [<a href="darbuotojo_registracija.php">Darbuotojų registracija</a>]
                        </td>
                    </tr>
        
		
    </body>


			<?php
            echo "<tr><td>";
            include("include/footer.php");
            echo "</td></tr>";
            ?>
			
</table>
</body>
</html>