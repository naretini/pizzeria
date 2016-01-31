<?php
require_once '../classes/User.class.php';
//send user to HP if already logged
User::anonymousPage();
?><html lang="en"><head>
    <title>Pizzeria</title>
    <?php include_once 'assets/tmpl/headers.inc.php' ?>

<script>
	//controlla la compilazione di tutti i campi e che le password combacino
function register(){
	var validate= false;
	$('#form_registration input').each(function(){
	if($(this).val() != '' || $(this).attr('checked'))
	    validate = true;
	});
	if(!validate){
		$('#general-err').show();
		return false;
	}
	else { 
		if(	$('#pwd1').val()==$('#pwd2').val()	){
			$('#form_registration').submit();
		}else{
			$('#pwd-err').show();
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
    			!empty($_POST['nome']) &&
    			!empty($_POST['cognome']) &&
    			!empty($_POST['indirizzo']) &&
    			!empty($_POST['telefono']) &&
    			!empty($_POST['login']) &&
    			!empty($_POST['password'])  

    		):
    			$regResult = User::Register($_POST);
    			if($regResult===TRUE):
    	?>
    				<h1>Registrazione effettuata per l'utente <?php echo $_POST['login'];?></h1>
    				<a class="btn btn-lg btn-primary" href="login.php" role="button">Login now »</a>  
    	<?php
    			else:
    	?>
    <div class="alert alert-danger" role="alert">
    Sono stati riscontrati dei problemi nella registrazione  <a href="register.php" class="alert-link">Riprova qui</a>
</div>
    	<?php
			endif;
    	else:
    	?>
    	<h1 class="well">Registration Form</h1>
		<div class="col-lg-12 well">
			<div class="row">
				<form id="form_registration" method="post">
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-6 form-group">
								<label>First Name</label>
								<input type="text" placeholder="Enter First Name Here.." class="form-control" name="nome">
							</div>
							<div class="col-sm-6 form-group">
								<label>Last Name</label>
								<input type="text" placeholder="Enter Last Name Here.." class="form-control" name="cognome">
							</div>
						</div>					
						<div class="form-group">
							<label>Address</label>
							<textarea placeholder="Enter Full Address Here.." rows="3" class="form-control" name="indirizzo"></textarea>
						</div>	
						
										
					<div class="form-group">
						<label>Phone Number</label>
						<input type="text" placeholder="Enter Phone Number Here.." class="form-control" name="telefono">
					</div>		
					<div class="form-group">
						<label>Login</label>
						<input type="text" placeholder="Choose a login name.." class="form-control" name="login">
					</div>	
					<div class="form-group">
						<label>Password</label>
						<input type="password" placeholder="Choose a password.." class="form-control" id="pwd1" name="password">
					</div>
					<div class="form-group">
						<label>Password confirm</label>
						<input type="password" placeholder="Confirm a password.." class="form-control" id="pwd2">
					</div>
					<div id="pwd-err" class="form-group error" style="display:none">
						<label class="error">Le password non coincidono</label>
					</div>

					<div id="general-err" class="form-group error" style="display:none">
						<label class="error">Compilare tutti i campi</label>
					</div>

					<button type="button" class="btn btn-lg btn-info" onclick="register()">Submit</button>					
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





