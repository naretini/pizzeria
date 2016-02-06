<?php 	require_once '../classes/require.inc.php';

User::authPage();

?><html lang="en"><head>
    <title>Pizzeria</title>
    <?php include_once 'assets/tmpl/headers.inc.php' ?>

  </head>

  <body>
    <!-- Fixed navbar -->
    <?php include_once 'assets/tmpl/navbar.inc.php' ?>
    
    <div class="container">
    	<?php
    	$res = Order::deleteOrder($_REQUEST['id']);
    		if(
    			$res
    		):
    	?>
    				<h1>Ordine cancellato con successo </h1>
    				
    	<?php
    			else:

    			
    	?>
    <div class="alert alert-danger" role="alert">
    Sono stati riscontrati dei problemi nel salvataggio dell' ordine   <a href="register.php" class="alert-link">Riprova qui</a>
</div>
    	<?php
			
    	endif;
    	?>

<a class="btn btn-lg btn-primary" href="ordini.php" role="button">Fai un nuovo ordine »</a>  

	
	<h1 class="well">Ordini inoltrati</h1>
	<?php
		Order::getOrdersList(User::loggedId());
	?>
</div> <!-- /container -->
  
    <?php include_once 'assets/tmpl/footer.inc.php' ?>

</body></html>





<?php


