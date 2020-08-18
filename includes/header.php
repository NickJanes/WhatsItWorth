<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pageTitle; ?></title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
<body>
	<div id="page-container">
		<nav class="navbar navbar-expand-lg navbar-dark">
			<div class="container">
			  <a class="navbar-brand" href="index.php"><h2>Worth</h2></a>
			  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			    <span class="navbar-toggler-icon"></span>
			  </button>
			  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
			    <div class="navbar-nav">
			      <a class="nav-item nav-link<?php if ($pageTitle == 'Home') { echo ' active'; } ?>" href="index.php">Home</a>
						<?php if(isAdmin()) :?>
			      <a class="nav-item nav-link<?php if ($pageTitle == 'Add A Game') { echo ' active'; } ?>" href="add-game.php">Add Game</a>
						<?php endif; ?>
			    </div>
					<div class="navbar-nav ml-auto">
						<?php if (!isAuthenticated()) : ?>
							<a class="nav-item nav-link <?php if ($pageTitle == 'Log In') { echo ' active'; } ?>" href="login.php">Log In</a>
							<a class="nav-item nav-link<?php if ($pageTitle == 'Register') { echo ' active'; } ?>" href="register.php">Register</a>
						<?php else: ?>
							<?php if(isAdmin()) :?>
								<a class="nav-item nav-link" href="admin.php">Admin</a>
							<?php endif; ?>
							<a class="nav-item nav-link" href="account.php">My Account</a>
							<a class="nav-item nav-link" href="procedures/doLogout.php">Log Out</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</nav>
		<div id="content">

<?php
echo(display_errors());
echo(display_success());
?>
