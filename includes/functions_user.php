<?php

function decodeJwt($prop = null) {
  \Firebase\JWT\JWT::$leeway = 1;
  $jwt = \Firebase\JWT\JWT::decode(
    request()->cookies->get('access_token'),
    getenv('SECRET_KEY'),
    ['HS256']
  );

  if($prop == null) {
    return $jwt;
  }

  return $jwt->{$prop};
}

function isAuthenticated() {
  if (!request()->cookies->has('access_token')) {
      return false;
  }

  try {
    decodeJwt();
    return true;
  } catch(\Exception $e) {
    return false;
  }
}

function requireAuth() {
  if(!isAuthenticated()) {
    $accessToken = new Symfony\Component\HttpFoundation\Cookie('access_token', "Expired", time()-3600, '/', getenv('COOKIE_DOMAIN'));
    redirect('login.php', ['cookies' => [$accessToken]]);
  }
}

function requireAdmin() {
  global $session;

  if(!isAuthenticated()) {
    $accessToken = new Symfony\Component\HttpFoundation\Cookie('access_token', "Expired", time()-3600, '/', getenv('COOKIE_DOMAIN'));
    redirect('login.php', ['cookies' => [$accessToken]]);
  }
  try {
    if(!decodeJwt('is_admin')) {
      $session->getFlashBag()->add('error', 'Not Authorized');
      redirect('index.php');
    }
  } catch (\Exception $e) {
    $accessToken = new Symfony\Component\HttpFoundation\Cookie('access_token', "Expired", time()-3600, '/', getenv('COOKIE_DOMAIN'));
    redirect('login.php', ['cookies' => [$accessToken]]);
  }
}

function isAdmin() {
  if (!isAuthenticated()) {
      return false;
  }

  try {
    $isAdmin = decodeJwt('is_admin');
  } catch(\Exception $e) {
    return false;
  }

  return (boolean)$isAdmin;
}

/*
 * Functions to interface with `user` table
 */
 function getAllUsers() {
   include("connection.php");

   try {
     $result = $conn->prepare("SELECT * FROM users;");
     $result->execute();
   } catch (Exception $e) {
       echo('Invalid query: ' . $conn->error);
   }
   return $result->get_result();
 }

 function findUserByUsername($username) {
   include("connection.php");

   try {
     $result = $conn->prepare("SELECT * FROM users WHERE username = ?;");
     $result->bind_param("s", $username);
     $result->execute();
   }
   catch(Exception $e) {
     echo("Bad Query");
   }
   return $result->get_result()->fetch_assoc ();
 }

 function findUserByEmail($email) {
   include("connection.php");

   try {
     $result = $conn->prepare("SELECT * FROM users WHERE email = ?;");
     $result->bind_param("s", $email);
     $result->execute();
   }
   catch(Exception $e) {
     echo("Bad Query");
   }
   return $result->get_result()->fetch_assoc ();
 }

 function findUserByAccessToken() {
   include("connection.php");
   try {
     $userId = decodeJwt('sub');
   } catch (\Exception $e) {
     throw $e;
   }

   try {
     $result = $conn->prepare("SELECT * FROM users WHERE id = ?;");
     $result->bind_param("i", $userId);
     $result->execute();
   }
   catch(Exception $e) {
     echo("Bad Query");
   }
   return $result->get_result()->fetch_assoc ();
 }

 function createUser($username, $email, $password) {
   include("connection.php");

   try {
     $time = time();
     $result = $conn->prepare("INSERT INTO users (username, email, password, datecreated, role_id) VALUES (?, ?, ? , ?, 2)");
     $result->bind_param("sssi", $username, $email, $password, $time);
     $result->execute();
     return findUserByUsername($username);
   }
   catch(Exception $e) {
     echo("Bad Query");
   }
 }

function updatePassword($password, $userId) {
  include("connection.php");

  try {
    $result = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $result->bind_param("si", $password, $userId);
    $result->execute();
  } catch(\Exception $e) {
    return false;
  }

  return true;
}

function isOwner($ownerId) {
    if (!isAuthenticated()) {
        return false;
    }

    try {
        $userId = decodeJwt('sub');
    } catch (\Exception $e) {
        return false;
    }

    return $ownerId == $userId;
}

function promote($userId) {
  include("connection.php");

  try {
    $result = $conn->prepare("UPDATE users SET role_id = 1 WHERE id = ?");
    $result->bind_param("i", $userId);
    $result->execute();
  } catch(\Exception $e) {
    throw $e;
  }
}

function demote($userId) {
  include("connection.php");

  try {
    $result = $conn->prepare("UPDATE users SET role_id = 2 WHERE id = ?");
    $result->bind_param("i", $userId);
    $result->execute();
  } catch(\Exception $e) {
    throw $e;
  }
}

?>
