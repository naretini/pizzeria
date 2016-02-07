<?php 	require_once '../classes/require.inc.php';

		User::adminPage();
		
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
			<h3>Elenco utenti registrati</h3>
			
	    
		    <div class="row">  
			    <form id="users_search" class="navbar-form navbar-left" role="search" action="admin_users.php" method="post">
					<div class="form-group">
					<input type="text" name="search" class="form-control" placeholder="Search">
					</div>

					<button type="submit" class="btn btn-default" onclick="$('#users_search').submit()">Submit</button>
				</form>
			</div>
			<?php
				$search = isset($_POST['search'])?strtolower($_POST['search']):NULL;

				if(!empty($search)){
					printf("<div class=\"row\">Risultati della ricerca per <strong>%s</strong></div>", $search);
				}

				User::getClientiList($search);
			?>
			
	    </div> <!-- /container -->

	    <?php include_once 'assets/tmpl/footer.inc.php' ?>
	</body>
</html>