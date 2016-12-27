<?php
ini_set('display_errors', 1);
include("include/session.php");
include("include/prekiuAdm.php");
if ($session->logged_in) {
	global $database;
    $helper = new BrazzersHelper();
	if(!isset($_POST['randcheck'])) {$_POST['randcheck'] = 0; $_SESSION['rand'] = 1;}
	if($_GET["id"] != -1)
	{
		$type = $helper->getType($_GET["id"]);
	}
	if(!isset($_POST['randcheck'])) {$_POST['randcheck'] = 0; $_SESSION['rand'] = 1;}
	if(isset($_POST['ok'])&& $_POST['randcheck']==$_SESSION['rand'])
	{
		$helper->editType($_GET["id"],$_POST);
		header("Location: types.php");
	}
	?>
    <html>
        <head>
            <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8"/>
			<title>Drym Tym elektroninė parduotuvė</title>
			<link href="include/styles.css" rel="stylesheet" type="text/css" />
			<link rel="stylesheet"
					href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
			<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
			<script type="text/javascript" src="scripts/jquery-1.12.0.min.js"></script>
			<script type="text/javascript" src="scripts/datetimepicker/jquery.datetimepicker.full.min.js"></script>
			<script type="text/javascript" src="scripts/main.js"></script>
        </head >
		<style>
		table {
			border-collapse: collapse;
		}
		th{
			text-align: center;
		}
		</style>
        <body style="background-color:orange;">
            <table class="center" style="width:80%;"><tr><td>
				<center><img src="pictures/top.png"/></center>
				</td></tr>
				<tr><td>
                        <?php
                        include("include/meniu.php");
                        ?>
                        <table style="border-width: 2px; border-style: dotted;"><tr><td>
                                    Atgal į [<a href="index2.php">Pradžia</a>]
                                </td></tr></table>
						<div class="container" style="text-align: center;color:green">
							<form method="POST" class="login">
								<?php
								$rand=rand();
								$_SESSION['rand']=$rand;
								?>
								<input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />
								<div class="form-group">
									<label class='control-label'>Pavadinimas</label>
									<input name='pav' id='pav' type='textbox-20' class="form-control input-sm" required="true" value="<?php if(isset($type))echo $type['Pavadinimas'];?>">
								</div>
								<div>
									<input type='submit' name='ok' value='Patvirtinti' class="btn btn-default">
								</div>
							</form>
						</div>
						<br>
                        <?php
                        include("include/footer.php");
                        ?>
                </td></tr>
            </table>
        </body>
    </html>
    <?php
    //Jei vartotojas neprisijungęs, užkraunamas pradinis puslapis
} else {
    header("Location: index2.php");
}
?>
