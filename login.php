<?php

$pageTitle = "Log In";

require_once 'includes/bootstrap.php';
include_once('includes/header.php');
?>


<div class="container">
  <div align="center">
    <div class="well col-sm-6 col-sm-offset-3">
        <form class="form-signin" method="post" action="procedures/doLogin.php">
            <h2 class="form-signin-heading">Please sign in</h2>
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
            <br>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
            <br>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        </form>
    </div>
  </div>
</div>

<?php include_once('includes/footer.php'); ?>
