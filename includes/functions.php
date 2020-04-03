<?php

function request() {
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
}

function redirect($path, $extra = []) {
    $response = \Symfony\Component\HttpFoundation\Response::create(null, \Symfony\Component\HttpFoundation\Response::HTTP_FOUND, ['Location' => $path]);
    if (key_exists('cookies', $extra)) {
        foreach ($extra['cookies'] as $cookie) {
            $response->headers->setCookie($cookie);
        }
    }
    $response->send();
    exit;
}

function display_errors() {
  global $session;

  if(!$session->getFlashBag()->has('error')) {
    return;
  }

  $messages = $session->getFlashBag()->get('error');

  $response = '<div class="alert alert-danger alert-dismissable">';
	foreach ($messages as $message) {
		$response .= "{$message}<br>";
	}
  $response .= '</div>';

  return $response;
}

function display_success() {
  global $session;

  if(!$session->getFlashBag()->has('success')) {
    return;
  }

  $messages = $session->getFlashBag()->get('success');

  $response = '<div class="alert alert-success alert-dismissable">';
	foreach ($messages as $message) {
		$response .= "{$message}<br>";
	}
  $response .= '</div>';

  return $response;
}





// *** CRUD - GAMES TABLE ***

function add_game($gameID) {
  include("connection.php");
  $url = "https://store.steampowered.com/api/appdetails/?appids=" . $gameID;
  $json = file_get_contents($url);
  $obj = json_decode($json, true);
  $obj = $obj['' . $gameID];
  if($obj['success'] == true) {
    $obj = $obj['data'];

    add_game_image($gameID);

    try {
      $result = $conn->prepare("SELECT * FROM games WHERE steam_id = ?");
      $result->bind_param("i", $gameID);
      $result->execute();
      $result = $result->get_result()->fetch_assoc ();
    } catch (Exception $e) {
      echo('Invalid query: ' . $conn->error);
    }

    if($result) {
      try {
        echo("Update");
        $result = $conn->prepare("UPDATE `games` SET `name`=?,`price`=?,`description`=? WHERE `steam_id`=?");
        $result->bind_param("sisi", $obj['name'], $obj['price_overview']['initial'], $obj['detailed_description'],  $gameID);
        $result->execute();
      } catch (Exception $e) {
        echo('Invalid query: ' . $conn->error);
      }
    } else {
      try {
        echo("Insert");
        $result = $conn->prepare("INSERT INTO games(description, name, price, steam_id) VALUES (?, ?, ?, ?)");
        $result->bind_param("ssii", $obj['detailed_description'], $obj['name'], $obj['price_overview']['initial'], $gameID);
        $result->execute();
      } catch (Exception $e) {
        echo('Invalid query: ' . $conn->error);
      }

      //default add for game creators
    }

    submit_value($gameID, -1, (float)$obj['price_overview']['initial']/100);

    echo("Query Succesful for " . $obj['name']);
    echo("<br>SteamID: <h4>" . $gameID . "</h4>");
    echo("<img class='addimg' src='img/header" . $gameID . ".jpg'>");
  } else {
    echo "Game of game ID " . $gameID . " does not exist";
  }



}

function add_game_image($gameID) {
  $url = "https://steamcdn-a.akamaihd.net/steam/apps/" . $gameID . "/header.jpg";
  $img = 'img/header' . $gameID . '.jpg';
  file_put_contents($img, file_get_contents($url));
}

function get_game($id) {
  include("connection.php");

  try {
    $result = $conn->prepare("SELECT description, name, price, steam_id FROM games WHERE steam_id = ?;");
    $result->bind_param("i", $id);
    $result->execute();
  }
  catch(Exception $e) {
    echo("Bad Query");
  }
  return $result->get_result()->fetch_assoc ();
}


function get_games($offset, $filter=NULL, $s=NULL) {
  include("connection.php");
  $query = "SELECT games.*, AVG(uservalues.value) as v, COUNT(uservalues.value) as submissions, AVG(uservalues.value)*100/price as ratio FROM games JOIN uservalues ON games.steam_id = uservalues.steam_id";

  if($s) {
    $query = $query . " WHERE name LIKE \"%". $s ."%\"";
  }

  $query = $query . " GROUP BY games.steam_id ORDER BY";


  if(is_array($filter)) {
    if($filter[0] == 'price') {
      $query = $query . " price";
    } else if($filter[0] == 'value') {
      $query = $query . " v";
    } else if($filter[0] == 'submissions') {
      $query = $query . " submissions";
    }

    if($filter[1] == 'high') {
      $query = $query . " DESC";
    } elseif($filter[1] == 'low') {
      $query = $query . " ASC";
    }
  } else {
    $query = $query . " ratio DESC";
  }


  $query = $query . " LIMIT 12 OFFSET " . $offset;


  try {
    $result = $conn->prepare($query);
    //$result->bind_param("i", $offset);
    $result->execute();
  } catch (Exception $e) {
      echo('Invalid query: ' . $conn->error);
  }
  return $result->get_result();
}

function get_all_games() {
  include("connection.php");

  try {
    $result = $conn->prepare("SELECT description, name, price, steam_id FROM games ORDER BY steam_id;");
    $result->execute();
  } catch (Exception $e) {
      echo('Invalid query: ' . $conn->error);
  }
  return $result->get_result();
}

// *** CRUD - VALUE TABLE ***

function get_submitted_value($gameID, $userID) {
  include("connection.php");

  try {
    $result = $conn->prepare("SELECT value FROM uservalues WHERE steam_id = ? AND user_id = ?;");
    $result->bind_param("ii", $gameID, $userID);
    $result->execute();
    $result = $result->get_result()->fetch_assoc ();
  }
  catch(Exception $e) {
    echo("Bad Query");
  }

  if($result) {
    return $result['value'];
  } else {
    return 0;
  }
}

function submit_value($gameID, $userID, $value) {
  include("connection.php");

  try {
    $result = $conn->prepare("SELECT value FROM uservalues WHERE steam_id = ? AND user_id = ?;");

    $result->bind_param("ii", $gameID, $userID);
    $result->execute();
    $result = $result->get_result()->fetch_assoc ();
  }
  catch(Exception $e) {
    echo("Bad Query");
  }

  if($result) {
    //Record Exists
    try {
      $result = $conn->prepare("UPDATE uservalues SET value = ?, time = ? WHERE steam_id = ? AND user_id = ?");
      $time = time();
      $result->bind_param("diii", $value, $time, $gameID, $userID);
      $result->execute();
    }
    catch(Exception $e) {
      echo("Bad Query");
    }
  } else {
    //Record Doesn't Exist
    try {
      $result = $conn->prepare("INSERT INTO uservalues (steam_id, user_id, value, time) values (?, ?, ?, ?)");
      $time = time();
      $result->bind_param("diii", $gameID, $userID, $value, $time);
      $result->execute();
    }
    catch(Exception $e) {
      echo("Bad Query");
    }

  }
}

function get_game_value($id) {
  include("connection.php");

  try {
    $result = $conn->prepare("SELECT AVG(value) as v, COUNT(value) as c FROM uservalues WHERE steam_id = ?;");
    $result->bind_param("i", $id);
    $result->execute();
  }
  catch(Exception $e) {
    echo("Bad Query");
  }

  return $result->get_result()->fetch_assoc();
}


?>
