</div>
<nav class="footer navbar navbar-expand-lg navbar-dark">
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
			<p class="nav-item nav-link">&copy; Nick Janes <?php print date("Y");?></p>
		</div>
	</div>
</nav>
</div>
</body>
</html>
