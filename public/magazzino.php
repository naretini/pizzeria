<?php 	require_once '../classes/require.inc.php';
	User::adminPage();

?><html lang="en"><head>
    <title>Pizzeria</title>
    <?php include_once 'assets/tmpl/headers.inc.php' ?>


  </head>

  <body>
    <!-- Fixed navbar -->
    <?php include_once 'assets/tmpl/navbar.inc.php' ?>
    
    <div class="container">
    	
  
	<h1 class="well">Magazzino ingredienti</h1>
	<?php
		Magazzino::getIngredientList();
	?>

	</div> <!-- /container -->
    <?php include_once 'assets/tmpl/footer.inc.php' ?>

</body></html>





