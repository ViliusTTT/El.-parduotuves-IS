<?php
//Formuojamas meniu.
if (isset($session) && $session->logged_in) {
    $path = "";
    if (isset($_SESSION['path'])) {
        $path = $_SESSION['path'];
        unset($_SESSION['path']);
    }
    ?>
    <table width=100% border="0" cellspacing="1" cellpadding="3" class="meniu">
        <?php
        echo "<tr><td>";
		 if ($session->isClient()) {
            echo "[<a href=\"" . $path . "tiekejo_sandelio_administravimas.php\">Sandėlys</a>] &nbsp;&nbsp;" ;
			echo "[<a href=\"" . $path . "tiekimo_sutartis_administravimas.php\">Sutartis</a>] &nbsp;&nbsp;";
			echo "[<a href=\"" . $path . "uzsakymo_administravimas.php\">Prekių užsakymas</a>] &nbsp;&nbsp;";
        }
        //Trečia operacija rodoma vadovui
        if ($session->isEmployee()) {
			echo "[<a href=\"" . $path . "tiekimo_sutartis_administravimas.php\">Sutartis</a>] &nbsp;&nbsp;";
			echo "[<a href=\"" . $path . "uzsakymo_administravimas.php\">Prekių užsakymas</a>] &nbsp;&nbsp;";
        }
        echo "</td></tr>";
        ?>
    </table>
    <?php
}//Meniu baigtas
?>

