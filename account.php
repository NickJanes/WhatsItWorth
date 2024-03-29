<?php

$pageTitle = "Account";

require_once 'includes/bootstrap.php';
include_once('includes/header.php');


?>

<div class="container">
  <div align="center">
    <div class="well col-sm-6 col-sm-offset-3">
      <form class="form-signin" method="post" action="procedures/changePassword.php">
        <h2 class="form-signin-heading">My Account</h2>
        <h4>Change Password</h4>
        <label for="inputCurrentPassword" class="sr-only">Current Password</label>
        <input type="password" id="inputCurrentPassword" name="current_password" class="form-control" placeholder="Current Password" required autofocus>
        <br>
        <label for="inputPassword" class="sr-only">New Password</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="New Password" required>
        <br>
        <label for="inputPassword" class="sr-only">Confirm New Password</label>
        <input type="password" id="inputPassword" name="confirm_password" class="form-control" placeholder="Confirm New Password" required>
        <br>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Change Password</button>
      </form>
    </div>
  </div>
</div>

<?php include_once('includes/footer.php'); ?>
