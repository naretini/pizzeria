<?php 	require_once '../classes/require.inc.php';
	User::adminPage();

?><html lang="en"><head>
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
    <?php include_once 'assets/tmpl/navbar.inc.php' ?>
    
    <div class="container">
    	<?php
    		if(
    			!empty($_POST['cliente'])		&& 
    			!empty($_POST['pizze'])		&& 
    			!empty($_POST['indirizzo']) &&
    			!empty($_POST['data']) 
    		):
    			$pizze = json_decode($_POST['pizze']);

    			$result = Order::Insert($_POST);
    		
    			if($result==TRUE):
    	?>
    				<h1>Ordine inoltrato con successo </h1>
    				
    	<?php
    			else:

    			if(
    			empty($_POST['cliente'])	||
    			empty($_POST['pizze'])		|| 
    			empty($_POST['indirizzo']) ||
    			empty($_POST['data'])
    			) {
    				?>
    				Ci sono campi vuoti
    				<script type="text/javascript">$('#general-err').show()</script>
    				<?php
    			}
    			
    	?>
    <div class="alert alert-danger" role="alert">
    Sono stati riscontrati dei problemi nel salvataggio dell' ordine   <a href="register.php" class="alert-link">Riprova qui</a>
</div>
    	<?php
			endif;
    	else:
    	?>
    	
    	<?php
    	endif;
    	?>
  
	<h1 class="well">Ordini inoltrati</h1>
	<?php
		Order::getOrdersList();
	?>

	</div> <!-- /container -->
    <?php include_once 'assets/tmpl/footer.inc.php' ?>

</body></html>





