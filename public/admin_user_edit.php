<?php 	require_once '../classes/require.inc.php';

		User::adminPage();

		$idUser = (INT)$_GET['id']>0?(INT)$_GET['id']:NULL;

		if(is_null($idUser)){
			header("Location: admin_users.php");
			exit;
		}

		$userData = User::getClienti(null, $idUser);


		// var_dump($userData);
		
		
?><html lang="en"><head>
    <title>Pizzeria</title>
    <?php include_once 'assets/tmpl/headers.inc.php' ?>

		<script>
			//controlla la compilazione di tutti i campi e che le password combacino
			function register(){
				var validate = true;
				$('#general-err').hide();
				$('#pwd-err').hide();

				$('#form_registration input').each(function(){
			
				if($(this).val() == '' )
				    validate = false;
				});
				if(!validate){
					$('#general-err').slideDown();
					return false;
				}
				else { 
					if(	$('#pwd1').val()==$('#pwd2').val()	){
						$('#form_registration').submit();
					}else{
						$('#pwd-err').slideDown();
					}
				}
			}
		</script>

  </head>

  <body>
    <!-- Fixed navbar -->
    <?php include_once 'assets/tmpl/navbar.inc.php' ?>
    

    <div class="container">
    	<?php
    		if(
    			!empty($_REQUEST['user_id'])
    		):
    			$updtResult = User::ClienteUpdate($_REQUEST);
    			if($updtResult===TRUE):
    	?>
    				<h1>Aggiornamento effettuato per l'utente <?php echo $_POST['login'];?></h1>
    	<?php
    			else:
    	?>
    			<div class="alert alert-danger" role="alert">
    				Sono stati riscontrati dei problemi nel salvataggio  <a href="admin_user.php?id=<?php echo $_REQUEST['user_id'];?>" class="alert-link">Riprova qui</a>
				</div>
    	<?php
			endif;
    	else:
    	?>
    	<h1 class="well">Aggiorna dati cliente</h1>
		<div class="col-lg-12 well">
			<div class="row">
				<form id="form_registration" method="post">
					<input type="hidden" name="user_id"  value="<?php echo $userData['user_id'];?>">
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-6 form-group">
								<label>First Name</label>
								<input type="text" placeholder="Enter First Name Here.." class="form-control" name="nome" value="<?php echo $userData['nome'];?>">
							</div>
							<div class="col-sm-6 form-group">
								<label>Last Name</label>
								<input type="text" placeholder="Enter Last Name Here.." class="form-control" name="cognome"  value="<?php echo $userData['cognome'];?>">
							</div>
						</div>					
						<div class="form-group">
							<label>Address</label>
							<textarea placeholder="Enter Full Address Here.." rows="3" class="form-control" name="indirizzo"><?php echo $userData['indirizzo'];?></textarea>
						</div>	
						
										
					<div class="form-group">
						<label>Phone Number</label>
						<input type="text" placeholder="Enter Phone Number Here.." class="form-control" name="telefono"  value="<?php echo $userData['telefono'];?>">
					</div>		
					<div class="form-group">
						<label>Login</label>
						<input type="text" placeholder="Choose a login name.." class="form-control" name="login"  value="<?php echo $userData['login'];?>">
					</div>	
					

					<div id="general-err"  class="alert alert-danger" role="alert" style="display:none">
						<label class="error">Compilare tutti i campi</label>
					</div>

					<button type="button" class="btn btn-lg btn-info" onclick="register()">Update</button>					
					</div>
				</form> 
			</div>
		</div>

    	<?php
    	endif;
    	?>
	</div> <!-- /container -->

    <?php include_once 'assets/tmpl/footer.inc.php' ?>

</body></html>





		