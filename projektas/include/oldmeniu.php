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
        echo "Prisijungęs vartotojas: <b>$session->username</b> <br>";
        echo "</td></tr><tr><td>";
		 if ($session->isClient()) {
            echo "[<a href=\"" . $path . "uzsakymu_istorija.php\">Užsakymų istorija</a>] &nbsp;&nbsp;" ;
        }
		echo "[<a href=\"" . $path . "indexDonatas.php\">Tiekimas</a>] &nbsp;&nbsp;";
        echo "[<a href=\"" . $path . "items.php\">Prekes</a>] &nbsp;&nbsp;";

        //Trečia operacija rodoma vadovui
        if ($session->isEmployee()) {
            echo "[<a href=\"" . $path . "warehouses.php\">Sandeliai</a>] &nbsp;&nbsp;";
            echo "[<a href=\"" . $path . "types.php\">Tipai</a>] &nbsp;&nbsp;";
			if($session->isSandelioVadovas()){
            echo "[<a href=\"" . $path . "operacija3.php\">Sandelio vadovo operacia</a>] &nbsp;&nbsp;";}
		if($session->isSandelininkas()){
			 echo "[<a href=\"" . $path . "operacija1\">Sandelininko operacija</a>] &nbsp;&nbsp;";}
		 if($session->isKurjeris()){
			  echo "[<a href=\"" . $path . "operacija5.php\">Kurjerio operacija</a>] &nbsp;&nbsp;";}
		 if($session->isPardaveja()){
			 echo "[<a href=\"" . $path . "operacija5.php\">Pardavejos operacija</a>] &nbsp;&nbsp;";}
			 if($session->isImonesVadovas()){
			 echo "[<a href=\"" . $path . "operacija6.php\">Imones vadovo operacija</a>] &nbsp;&nbsp;";}
        }
        //Administratoriaus sąsaja rodoma tik administratoriui
        if ($session->isAdmin()) {
            echo "[<a href=\"" . $path . "admin/admin.php\">Administratoriaus sąsaja</a>] &nbsp;&nbsp;";
        }

        echo "[<a href=\"" . $path . "process.php\">Atsijungti</a>]";
        echo "</td></tr>";
        ?>
    </table>
    <?php
}//Meniu baigtas
?>
