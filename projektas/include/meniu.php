<?php
//Formuojamas meniu.
if (isset($session) && $session->logged_in) {
    $path = "";
    if (isset($_SESSION['path'])) {
        $path = $_SESSION['path'];
        unset($_SESSION['path']);
    }
    ?>


    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="<?php echo $path; ?>index2.php">Drym tym shop</a>
        </div>
        <?php
        echo "<ul class='nav navbar-nav'>";
        if ($session->isClient()) {
            echo "<li>
            <a href=\"" . $path . "uzsakymu_istorija.php\">Užsakymų istorija</a>
            </li>";
			echo "<li class='dropdown'>
			<a class='dropdown-toggle' data-toggle='dropdown'>Tiekimas<span class='caret'></span></a>";
			echo "<ul class='dropdown-menu'>";
			echo "<li><a href=\"" . $path . "tiekejo_sandelio_administravimas.php\">Sandėlis</a></li>";
			echo "<li><a href=\"" . $path . "tiekimo_sutartis_administravimas.php\">Sutartis</a></li>";
			echo "<li><a href=\"" . $path . "uzsakymo_administravimas.php\">Prekių užsakymas</a></li>";
			echo "<li><a href=\"" . $path . "tiekejo_administravimas.php\">Tiekėjas</a></li>";
			echo "</ul>";
			echo "</li>";
        }
		
		if (!$session->isAdmin())
			echo "<li><a href=\"" . $path . "items.php\">Prekės</a></li>";
        //Trečia operacija rodoma vadovui
        if ($session->isEmployee()) {
			echo "<li class='dropdown'>
			<a class='dropdown-toggle' data-toggle='dropdown'>Tiekimas<span class='caret'></span></a>";
			echo "<ul class='dropdown-menu'>";
			echo "<li><a href=\"" . $path . "tiekimo_sutartis_administravimas.php\">Sutartis</a></li>";
			echo "<li><a href=\"" . $path . "uzsakymo_administravimas.php\">Prekių užsakymas</a></li>";
			echo "</ul>";
			echo "</li>";
            echo "<li class='dropdown'>
            <a class='dropdown-toggle' data-toggle='dropdown'>Sandėlių administravimas<span class='caret'></span></a>";
            echo "<ul class='dropdown-menu'>";
            echo "<li><a href=\"" . $path . "warehouses.php\">Sandėliai</a></li>";
            echo "<li><a href=\"" . $path . "types.php\">Tipai</a></li>";
            echo "</ul>";
            echo "</li>";
            
        if($session->isSandelininkas()){
             echo "<li><a href=\"" . $path . "operacija1\">Sandelininko operacija</a></li>";}
         if($session->isKurjeris()){
              echo "<li><a href=\"" . $path . "operacija5.php\">Kurjerio operacija</a></li>";}
         if($session->isPardaveja()){
             echo "<li><a href=\"" . $path . "operacija5.php\">Pardavėjos operacija</a></li>";}
			 echo "<li><a href=\"" . $path . "uzsakymu_istorijos_apskaita.php\">Užsakymų istorija</a></li>" ;
             if($session->isImonesVadovas()){
             echo "<li><a href=\"" . $path . "operacija6.php\">Imonės vadovo operacija</a></li>";}
        }
        //Administratoriaus sąsaja rodoma tik administratoriui
        if ($session->isAdmin()) {
            echo "<li><a href=\"" . $path . "admin/admin.php\">Administratoriaus sąsaja</a></li>";
        }
        echo "<li><a style='color:#fff'>Prisijungęs vartotojas: <b>$session->username</b></a></li>";
        echo "</ul>";
        echo "<a href=\"" . $path . "process.php\" style='float:right'  class='btn btn-danger navbar-btn'>Atsijungti</a>";
		if (!$session->isAdmin())
			echo "<a id='cart' style='float:right;background-color:green;border-color:green;right:10px;' class='btn btn-danger navbar-btn'>Krepšelis (0)</a>";	
        ?>
      </div>
	  <div><div id='cartContent' hidden></div></div>
    </nav>
	
	<link rel="stylesheet" type="text/css" href="include/krepselis.css">
	<script type="text/javascript">
		function getSelectedItems() {
			return JSON.parse('<?php
				if (isset($_SESSION['selectedItems@'.$_SESSION['username']]))
					echo json_encode($_SESSION['selectedItems@'.$_SESSION['username']]);
			?>');
		}
	</script>
	<script src="scripts/krepselis.js" charset="UTF-8"></script>
	
<?php
}//Meniu baigtas
?>
