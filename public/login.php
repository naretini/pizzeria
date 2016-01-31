<?php
require_once '../classes/User.class.php';
//send user to HP if already logged
User::anonymousPage();
?><html lang="en"><head>
    <title>Pizzeria</title>
    <?php include_once 'assets/tmpl/headers.inc.php' ?>

<script>
    //controlla la compilazione di tutti i campi e che le password combacino
function form_login(){
    var validate= false;
    $('#form_login input').each(function(){
    if($(this).val() != '' || $(this).attr('checked'))
        validate = true;
    });
    if(!validate){
        $('#general-err').show();
        return false;
    }
    else { 
        $('#form_login').submit();
        
    }
}

</script>

  </head>

  <body>

    <!-- Fixed navbar -->
    <?php include_once 'assets/tmpl/navbar.inc.php' ?>
    
    <?php
        if(isset($_GET['required'])){            
    ?>
        <div class="alert alert-danger" role="alert">
            Per accedere al contenuto richiesto è necessario autenticarsi.
            Oppure <a href="register.php" class="alert-link">Registrati qui</a>
        </div>
    <?php
        }
    ?>

    <div class="container">
        <?php
            if(
                !empty($_POST['login']) &&
                !empty($_POST['password'])  

            ):
                $regResult = User::Login($_POST);
                if($regResult===TRUE):
        ?>
                    <h1>Login effettuato per l'utente <?php echo $_POST['login'];?></h1>
                    <script>setTimeout("location='index.php'", 1000);</script>
        <?php
                else:
        ?>
            <div class="alert alert-danger" role="alert">
            Sono stati riscontrati dei problemi nel login  <a href="login.php" class="alert-link">Riprova qui</a>
            </div>
        <?php
            endif;
        else:
        ?>
        <h1 class="well">Login Form</h1>
        <div class="col-lg-12 well">
            <div class="row">
                <form id="form_login" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" placeholder="" class="form-control" id="username" placeholder="Your username" name="login" />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" placeholder="" class="form-control" id="password" placeholder="Your password"  name="password" />
                </div>
                <div id="general-err" class="form-group error" style="display:none">
                    <label class="error">Compilare tutti i campi</label>
                </div>
                <button type="button" class="btn btn-lg btn-info" onclick="form_login()">Login</button>                  
                </form> 
            </div>
        </div>

        <?php
        endif;
        ?>
    </div> <!-- /container -->


    
  
    <?php include_once 'assets/tmpl/footer.inc.php' ?>

</body></html>





