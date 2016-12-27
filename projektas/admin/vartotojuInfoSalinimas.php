<?php
include("../include/session.php");

//Iš pradžių aprašomos funkcijos, po to jos naudojamos.

/**
 * displayUsers - Displays the users database table in
 * a nicely formatted html table.
 */
function displayUsers() {
    global $database;
    $q = "SELECT username,userlevel,email,timestamp "
            . "FROM " . TBL_USERS . " ORDER BY userlevel DESC,username";
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
    echo "<tr><td><b>Vartotojo vardas</b></td><td><b>Lygis</b></td><td><b>E-paštas</b></td><td><b>Paskutinį kartą aktyvus</b></td><td><b>Veiksmai</b></td></tr>\n";
    for ($i = 0; $i < $num_rows; $i++) {
        $uid =
        $uname = mysqli_result($result, $i, "username");
        $ulevel = mysqli_result($result, $i, "userlevel");
        $ulevelname = '';
        switch ($ulevel)
        {
            case ADMIN_LEVEL:
                $ulevelname = ADMIN_NAME;
                break;
            case MANAGER_LEVEL:
                $ulevelname = MANAGER_NAME;
                break;
            case USER_LEVEL:
                $ulevelname = USER_NAME;
                break;
				 case EMPLOYEE_LEVEL:
                $ulevelname = EMPLOYEE_NAME;
                break;
            default :
                $ulevelname = 'Neegzistuojantis tipas';
        }
        
        $email = mysqli_result($result, $i, "email");
        $time = date("Y-m-d G:i", mysqli_result($result, $i, "timestamp"));
        echo "<tr><td>$uname</td><td>$ulevelname</td><td>$email</td><td>$time</td><td> <a href='AdminProcess.php?d=1&deluser=$uname' onclick='return confirm(\"Ar tikrai norite trinti?\");'>Trinti</a></td></tr>\n";
    }
    echo "</table><br>\n";
}

function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow[$field]; 
} 
function ViewActiveUsers() {
    global $database;
    if (!defined('TBL_ACTIVE_USERS')) {
        die("");
    }
    $q = "SELECT username FROM " . TBL_ACTIVE_USERS
            . " ORDER BY timestamp DESC,username";
    $result = $database->query($q);
    /* Error occurred, return given name by default */
    $num_rows = mysqli_num_rows($result);
    if (!$result || ($num_rows < 0)) {
        echo "Error displaying info";
    } else if ($num_rows > 0) {
        /* Display active users, with link to their info */
        echo "<br><table border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
        echo "<tr><td><b>Vartotojų vardai</b></td></tr>";
        echo "<tr><td><font size=\"2\">\n";
        for ($i = 0; $i < $num_rows; $i++) {
            $uname = mysqli_result($result, $i, "username");
            if ($i > 0)
                echo ", ";
            echo "<a href=\"../userinfo.php?user=$uname\">$uname</a>";
        }
        echo ".";
        echo "</font></td></tr></table>";
    }
}

if (!$session->isAdmin()) {
    header("Location: ../index.php");
} else { //Jei administratorius
    ?>
    <html>

<body style="background-color:orange;">

    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8" />
        <title>Sistemos vartotojų informacija</title>
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
                                        <?php
                                    /**
                                     * Display Users Table
                                     */
                                    ?>
                                            <h3>Sistemos vartotojai:</h3>
                                            <?php
                                    displayUsers();
                                    ?>

                                    </td>
                                </tr>


                                </td>
                                </tr>


                </td>
            </tr>


            </td>
            </tr>
            <tr>
                <td>
                    <hr>
                </td>
            </tr>
            </td>
            </tr>

            <tr>
                <td>
                    <hr>
                </td>
            </tr>
            </td>
            </tr>
            <tr>
                <td>
                    <hr>
                </td>
            </tr>
            </td>
            </tr>
            <tr>
                <td>
                    <h3>Šiuo metu prisijungę vartotojai:</h3>
                    <?php
                        ViewActiveUsers();
                        ?>
                        <tr>
                            <td>
                                <hr>
                            </td>
                        </tr>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                /**
                 * Delete Inactive Users
                 */
                ?>
                        <h3>Šalinti neaktyvius vartotojus</h3>
                        <table>
                            <form action="adminprocess.php" method="POST">
                                <tr>
                                    <td>
                                        Neaktyvumo dienos:<br>
                                        <select name="inactdays">
                                    <option value="3">3
                                    <option value="7">7
                                    <option value="14">14
                                    <option value="30">30
                                    <option value="100">100
                                    <option value="365">365
                                </select>
                                    </td>
                                    <td>
                                        <br>
                                        <input type="hidden" name="subdelinact" value="1">
                                        <input type="submit" value="Šalinti">
                                    </td>
                            </form>
                        </table>
                </td>
                </tr>

                </table>
                </td>
            </tr>
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