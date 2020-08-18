<?php
require_once 'includes/bootstrap.php';

$filter = $s = "";
$q = 0;

if(!empty($_GET['q'])) {
  $q = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_NUMBER_INT);
}


if(!empty($_GET['filter'])) {
	$filter = explode(':', filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_STRING));
}

if(!empty($_GET['s'])) {
	$s = filter_input(INPUT_GET, 's', FILTER_SANITIZE_STRING);
}


foreach(get_games($q, $filter, $s) as $obj) {
  $information ="
    <div class='Game d-flex flex-row'>
      <a class='d-flex mr-auto' href=game.php?gameid=" . $obj["steam_id"] . ">
        <div class=''><img src='img/header" . $obj["steam_id"] . ".jpg'></div>
        <div class='p-2' style='font-size: 1.5em; text-overflow:ellipsis;'>" . $obj['name'] . "</div>
      </a>
      <div>
        <div class='m-2 d-flex pbtn'>
          <div class='p-2'>$". (($obj['price'] > 0) ? substr_replace($obj['price'], ".", strlen($obj['price']) - 2, 0) : "0.00") . "</div>
          <div class='p-1 m-1 purchase'>Purchase</div>
        </div>
        <div class='m-2 p-2 value'>$". (($obj['v'] > 0) ? (number_format((float)$obj['v'], 2, '.', '')) : "0.00") . " (" . $obj['submissions'] . ")</div>
      </div>
    </div>";
  echo $information;
}
?>
