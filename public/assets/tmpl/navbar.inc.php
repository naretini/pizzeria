<?php require_once '../classes/User.class.php'; ?>
<?php if(User::isAdmin()): ?>
    <nav class="navbar navbar-default navbar-fixed-top" style="background-color:Fc3;">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Admin</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li ><a href="admin_users.php">Utenti</a></li>
            <li><a href="admin_orders.php">Ordini</a></li>
            <li><a href="magazzino.php">Magazzino</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
          <?php
            if(!User::isLogged()):
          ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Registrati</a></li>            
          <?php else:         ?>
            <li><a href="logout.php">Logout</a></li>            
          <?php endif;         ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
<?php else: ?>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Pizzeria</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class=""><a href="index.php">Home</a></li>
            <li><a href="ordini.php">Ordini</a></li>
            
            <!--
            <li><a href="#contact">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
            -->
          </ul>
          <ul class="nav navbar-nav navbar-right">
          <?php
            if(!User::isLogged()):
          ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Registrati</a></li>            
          <?php else:         ?>
            <li><a href="logout.php">Logout</a></li>            
          <?php endif;         ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <?php if(User::isLogged()){
      ?>
<div class="alert alert-success " style="text-align:right;" role="alert">Benvenuto/a <?php echo  User::loggedName()?></div>
    <?php } 
/*
    ?>
    Session:<?php
    var_dump($_SESSION);
    ?>
    <br/>
    Request:<?php
    var_dump($_REQUEST);
*/
    ?>
<?php endif; ?>