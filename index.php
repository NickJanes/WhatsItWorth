<?php

$pageTitle = "Home";

$filter = $s = '';

if(!empty($_GET['filter'])) {
	$filter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_STRING);
}

$filterArray = explode(":", $filter);

if(!empty($_GET['s'])) {
	$s = filter_input(INPUT_GET, 's', FILTER_SANITIZE_STRING);
}

require_once 'includes/bootstrap.php';
include_once("includes/header.php");
?>

<div class="container">

	<div class="search m-1">
		<form method="get" action="index.php">
			<div class="d-flex">
		    <input class="ml-auto" oninput="updateResult(this.value)" style="border: 0px; border-bottom: 2px solid #ccc;" type="text" name="s" id="s" placeholder="Search Games" />
				<select style="border: 0px; border-bottom: 2px solid #ccc;" name="filter" id="filter" />
					<option value="">Select One</option>
					<optgroup label="Price">
						<option value="price:high" <?php if($filterArray[0]=="price" && $filterArray[1]=="high") {echo("selected");}?>>Highest Price</option>
						<option value="price:low" <?php if($filterArray[0]=="price" && $filterArray[1]=="low") {echo("selected");}?>>Lowest Price</option>
					</optgroup>
					<optgroup label="Value">
						<option value="value:high" <?php if($filterArray[0]=="value" && $filterArray[1]=="high") {echo("selected");}?>>Highest Value</option>
						<option value="value:low" <?php if($filterArray[0]=="value" && $filterArray[1]=="low") {echo("selected");}?>>Lowest Value</option>
					</optgroup>
					<optgroup label="# Of Submissions">
						<option value="submissions:high" <?php if($filterArray[0]=="submissions" && $filterArray[1]=="high") {echo("selected");}?>>Most Submissions</option>
						<option value="submissions:low" <?php if($filterArray[0]=="submissions" && $filterArray[1]=="low") {echo("selected");}?>>Fewest Submissions</option>
					</optgroup>
				</select>
			</div>
			<div class="d-flex">
			</div>
		</form>
  </div>

	<div id="resultsinfo" class="m-4" style="text-align: center;">
		<?php if($s) {echo("Showing results for: <h3>" . $s . "</h3><a href=\"index.php\">Click here to clear filter.</a> ");} ?>
	</div>

	<div id="gamedata">
	</div>


	<script type="text/javascript">
		var gameCount = 0;
		function refreshSearch() {

		}

    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() >= $(document).height()) {
						loadMoreData(gameCount);
        }
    });

    function loadMoreData(){
			var xhttp;
		  xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
		      document.getElementById("gamedata").innerHTML += this.responseText;
		    }
		  };
		  xhttp.open("GET", "gamehtml.php?q="+gameCount+"&s=<?php echo($s);?>&filter=<?php echo($filter); ?>", true);
		  xhttp.send();
			gameCount += 12;
    }
	</script>


<?php echo("<script type='text/javascript'>loadMoreData();</script>"); ?>



</div>

<?php include("includes/footer.php");?>
