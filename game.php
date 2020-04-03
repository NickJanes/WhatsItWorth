<?php
require_once 'includes/bootstrap.php';

$userID = $gameID = $value = 0;

if(!empty($_GET['gameid'])) {
	$gameID = filter_input(INPUT_GET, 'gameid', FILTER_SANITIZE_NUMBER_INT);
}

if(!empty($_POST['value'])) {
	$value = filter_input(INPUT_POST, 'value', FILTER_VALIDATE_FLOAT);
	if(isAuthenticated()) {
		submit_value($gameID, decodeJwt('sub'), $value);
	}
}

$obj = get_game($gameID);
$value = get_game_value($obj['steam_id']);

$pageTitle = $obj['name'];

include_once("includes/header.php");?>


<div class="container">
  <div class="row">
    <div class="col-md-12"><h1> <?php echo $obj['name']; ?> </h1></div>
  </div>


  <div class="row">
    <div class="col-md-6">
      <div class="d-flex flex-column">
        <div><img src="img/header<?php echo $obj['steam_id']?>.jpg"></div>
        <div>
          <?php echo "Steam: $" . (($obj['price'] > 0) ? substr_replace($obj['price'], ".", strlen($obj['price']) - 2, 0) : "0.00"); ?>
          <?php echo (" Value: $" . (($value['v'] > 0) ? number_format((float)$value['v'], 2, '.', '') : "0.00") . " (" . $value['c'] . ")"); ?>
        </div>
          <form method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF'] . '?gameid=' . $_GET['gameid']);?>">
        		<input type="text" name="value" placeholder='$<?php echo(number_format((float)get_submitted_value($gameID, decodeJwt('sub')), 2, '.', ''))?>'>
        		<input type="submit"></input>
        	</form>
      </div>
    </div>
    <div class="col-md-6" style="text-align: justify;"><?php echo $obj['description']; ?></div>
  </div>
</div>

<?php include("includes/footer.php");?>
