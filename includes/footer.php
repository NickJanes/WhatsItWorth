</div>
<nav class="footer navbar navbar-expand-lg navbar-dark">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
		<div class="navbar-nav">
			<a class="nav-item nav-link<?php if ($pageTitle == 'Home') { echo ' active'; } ?>" href="index.php">Home</a>
			<a class="nav-item nav-link<?php if ($pageTitle == 'Add A Game') { echo ' active'; } ?>" href="add-game.php">Add Game</a>
		</div>
		<div class="navbar-nav ml-auto">
			<p class="nav-item nav-link">&copy; Nick Janes <?php print date("Y");?></p>
		</div>
	</div>
</nav>
</div>
</body>
</html>
