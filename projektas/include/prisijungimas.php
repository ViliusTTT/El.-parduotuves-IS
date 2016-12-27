<?php
if (isset($form) && isset($session) && !$session->logged_in) {
    ?>  <body style="background-color:orange;">
	<link rel="stylesheet"
			href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <form action="process.php" method="POST" class="login">              
        <center style="font-size:18pt;"><b>Prisijungimas prie parduotuvės</b></label></center>
        <p style="text-align:left;">Vartotojo slapyvardis:<br>
            <input class ="s1" name="user" type="text" value="<?php echo $form->value("user"); ?>"/><br>
            <?php echo $form->error("user"); ?>
        </p>
	
        <p style="text-align:left;">Slaptažodis:<br>
            <input class ="s1" name="pass" type="password" value="<?php echo $form->value("pass"); ?>"/><br>
            <?php echo $form->error("pass"); ?>
        </p>  
        <p style="text-align:left;">
            <input type="submit" value="Prisijungti"/>
            <input type="checkbox" name="remember" 
            <?php
            if ($form->value("remember") != "") {
                echo "Pažymėtas";
            }
            ?>/>
            Atsiminti   
        </p>
        <input type="hidden" name="sublogin" value="1"/>
        <p>
            
            <a href="kliento_registracija.php">Registracija naujiems klientams</a>
			</p> 
			<p> 
			 <a href="darbuotojo_registracija.php">Registracija darbuotojams</a>
        </p>     
    </form>
    <?php
}
?>