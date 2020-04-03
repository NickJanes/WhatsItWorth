<?php
require_once 'includes/bootstrap.php';
requireAdmin();

$pageTitle = "Add A Game";

$gameID = "";

if(!empty($_GET['gameid'])) {
	$gameID = filter_input(INPUT_GET, 'gameid', FILTER_SANITIZE_NUMBER_INT);
}

include_once("includes/header.php");?>

<div class="container">
	<form method="get" action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>">
		Enter Game URL: <input type="text" name="gameid">
		<input type="submit">
	</form>

	<?php


		if(isset($gameID) && $gameID != "") {

			if(substr($gameID, 0, 4) === "http") {
				echo $gameID;
				$gameID = ltrim($gameID, "https://store.steampowered.com/app/");
				$gameID = substr($gameID, 0, strpos($gameID, "/"));
			}

			if(is_numeric($gameID)) {
				add_game($gameID);
			} else {
				echo "Incorrect Game ID entered";
			}
		}


	?>
</div>

<?php include("includes/footer.php");?>
