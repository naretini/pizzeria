<?php 	require_once '../classes/require.inc.php';

User::authPage();

?><html lang="en"><head>
    <title>Pizzeria</title>
    <?php include_once 'assets/tmpl/headers.inc.php' ?>
<script>
	function viewDetails(el){
		el.closest('tr').next('tr').slideToggle()
	}

	//controlla la compilazione di tutti i campi e che le password combacino
	function ordina(){
		var validate= true;
		$('#general-err').hide();
				
		$('#form_ordini input').each(function(){
			console.log($(this).val(), $(this).val()!='');
		if($(this).val() == '' )
		    validate = false;
		});
		if(!validate){
			$('#general-err').slideDown();
			return false;
		}
		else { 
			$('#form_ordini').submit();			
		}
	}


	var pizzeArray = new Array();

	function aggiungi(){
		var pizza = {};

	var selected_pizza = $(":selected", $('#pizza_nome'))[0];

		pizza.id = $('#pizza_nome').val();	
		pizza.nome = $(selected_pizza).attr('data-nome');
		pizza.prezzo = $(selected_pizza).attr('data-prezzo');
		pizza.qta = $('#pizza_qta').val();
		pizza.tipo = $('#pizza_tipo').val();
		
		if(pizza.qta<=0){
			alert('inserire la quantità');
			return false;
		}

		pizzeArray.push(pizza);

		report();
		console.log(pizzeArray);
		$('#ordine_pizze').val(JSON.stringify(pizzeArray));
	}



	function report(){
		var display = $('#report')
		display.html('');
		var totale = 0;
		$.each(pizzeArray, function(e,v){
			totale += v.prezzo*v.qta;
			display.append('<div class="row">'+v.qta+' '+v.nome+' '+v.prezzo+'€</div>');
		});
			display.append('<div class="row">Totale: '+totale+'€</div>');
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
					<a class="btn btn-lg btn-primary" href="ordini.php" role="button">Fai un nuovo ordine »</a>  
    				
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
    	<h1 class="well">Ordina le pizze</h1>
		<div class="col-lg-12 well">
			<div class="row">
				<div class="col-sm-12">
					<div class="row">
						<div class="col-sm-4 form-group">
							<label>Pizza</label>
							<select id="pizza_nome" class="form-control">
							  <?php
							  $pizze= Pizzeria::getPizza();
							  foreach ($pizze as $key => $value) {
							  	printf('<option value="%s" data-prezzo="%s" data-nome="%s" >%s</option>', $value['pizza_id'], $value['prezzo'], $value['nome'], $value['nome']);
							  }

							  ?>
							</select>
						</div>
						<div class="col-sm-4 form-group">
							<label>Tipo</label>
							<select id="pizza_tipo"  class="form-control">								  
							  <option value="normale">Rotonda</option>
							  <option value="calzone">Calzone</option>								  
							</select>
						</div>
						<div class="col-sm-2 form-group">
							<label>Quantità</label>
							<input id="pizza_qta" type="number" placeholder="numero pizze" class="form-control" value="1">
						</div>
						<div class="col-sm-2 form-group">							
							<button type="button" class="btn btn btn-info" onclick="aggiungi()">Aggiungi</button>	
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="report" class="col-lg-12 well">
			Nessuna pizza aggiunta
		</div>

		<div class="col-lg-12 well">
			
				<form id="form_ordini" method="post">
				<div class="row">					
					<input type="hidden"  name="cliente" value="<?php  echo User::loggedId(); ?>">
					<input type="hidden"  name="pizze" value="" id="ordine_pizze">
					<div class="form-group">
						<label>Indirizzo di consegna</label>
						<input type="text"  placeholder="Enter Full Address Here.."  class="form-control" name="indirizzo" value="<?php echo User::getLoggedUserAddress(); ?>"> 
					</div>	

					<div class="form-group">
						<label>Data consegna</label>
						<input type="date" placeholder="Enter Phone Number Here.." class="form-control" name="data" value="<?php echo date("Y-m-d");?>">
					</div>		
					<div class="form-group">
						<label>Ora</label>
						<input type="time" placeholder="Choose a login name.." class="form-control" name="ora" value="<?php echo date("H:i");?>">
					</div>	


					<div id="general-err"  class="alert alert-danger" role="alert" style="display:none">
						<label class="error">Compilare tutti i campi</label>
					</div>

					<button type="button" class="btn btn-lg btn-info" onclick="ordina()">Submit</button>					
					</div>
				</form>
		</div>
    	<?php
    	endif;
    	?>
	  <a name="list"></a>
    	<div>
	<h1 class="well">Ordini inoltrati</h1>
	<?php
		Order::getOrdersList(User::loggedId());
	?>
	</div>
</div> <!-- /container -->
    <?php include_once 'assets/tmpl/footer.inc.php' ?>

</body></html>





