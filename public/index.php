<?php 	require_once '../classes/require.inc.php';

		User::publicPage();
		
?><html lang="en">
	<head>
    	<title>Pizzeria</title>
    	<?php include_once 'assets/tmpl/headers.inc.php' ?>


    	<script>
			function viewDetails(el){
				el.closest('tr').next('tr').slideToggle()
			}
    	</script>
  	</head>

  	<body>
	    <!-- Fixed navbar -->
	    <?php include_once 'assets/tmpl/navbar.inc.php'; ?>

	    <div class="container">
			<h3>Le nostre pizze</h3>
			<h4>Ecco il nostro catalogo pizze nostre pizze </h4>
	    
		    <div class="row">  
			    <form id="pizza_search" class="navbar-form navbar-left" role="search" action="index.php" method="post">
					<div class="form-group">
					<input type="text" name="search" class="form-control" placeholder="Search">
					</div>

					<button type="submit" class="btn btn-default" onclick="$('#pizza_search').submit()">Submit</button>
				</form>
			</div>
			<?php
				$search = isset($_POST['search'])?strtolower($_POST['search']):NULL;

				if(!empty($search)){
					printf("<div class=\"row\">Risultati della ricerca per <strong>%s</strong></div>", $search);
				}

				Pizzeria::getPizzaList($search);
			?>
			<a class="btn btn-lg btn-primary" href="ordini.php" role="button">Ordina le pizze »</a>  
	    </div> <!-- /container -->

	    <?php include_once 'assets/tmpl/footer.inc.php' ?>
	</body>
</html>