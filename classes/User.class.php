<?php

require_once 'DB.class.php';

class User{
	public static function Register($arrData){

		$db = DB::getInstance();
		$dbconn = $db::getConnection();

		if(
			empty($arrData['nome']) 		||
    		empty($arrData['cognome']) 	||
    		empty($arrData['indirizzo']) ||
    		empty($arrData['telefono'])  ||
    		empty($arrData['login']) 	||			
    		empty($arrData['password'])    
		){
			return FALSE;
		}else{
			$query = "INSERT INTO utenti 
				(
					nome, 
					cognome, 
					telefono, 
					indirizzo, 
					login, 
					password
				)
				VALUES
				( 
					:nome, 
					:cognome,
					:telefono,
					:indirizzo,
					:login,
					:password
				);";
					
				$stmt = $dbconn->prepare($query);
				
				
				$result = $stmt->execute(array(
					':nome'  		=> $arrData['nome'], 
					':cognome' 		=> $arrData['cognome'],
					':telefono' 	=> $arrData['telefono'],
					':indirizzo' 	=> $arrData['indirizzo'],
					':login' 		=> $arrData['login'],
					':password' 	=> $arrData['password']

				));

			if (!$result) {
			  return FALSE;
			}

		}

		return TRUE;

	}


	public static function Login($arrData){
		$db = DB::getInstance();
		$dbconn = $db::getConnection();

		if(
			empty($arrData['login']) 	||			
    		empty($arrData['password'])    
		){
			return FALSE;
		}else{

			$stmt=FALSE;
			try{

				$stmt = $dbconn->prepare("SELECT  user_id,nome,is_admin FROM  utenti 	WHERE 	login=? AND password=?");
				
				$stmt->bindParam(1, $arrData['login']);
				$stmt->bindParam(2, $arrData['password']);
				$stmt->execute();
				
				

				if (!$stmt) {
				  return FALSE;
				}

				if($row=$stmt->fetch(PDO::FETCH_OBJ)) {
					$_SESSION['authUserId'] = $row->user_id;
					$_SESSION['authUser'] = $row->nome;
					$_SESSION['isAdmin'] = $row->is_admin;
					return TRUE;
			    }
			}catch(PDOException  $e ){
				echo "Error: ".$e;
			}

		}

	}

	public static function getLoggedUserAddress (){
		if(isset($_SESSION['authUserId'])){
			$db = DB::getInstance();
			$dbconn = $db::getConnection();
			try{

				$stmt = $dbconn->prepare("SELECT  indirizzo FROM  utenti 	WHERE 	user_id=:user_id");
				
				$stmt->bindParam(':user_id', $_SESSION['authUserId']);
				$stmt->execute();
				if (!$stmt) {
				  return FALSE;
				}

				if($row=$stmt->fetch(PDO::FETCH_OBJ)) {
					
					return $row->indirizzo;
			    }
			}catch(PDOException  $e ){
				echo "Error: ".$e;
			}

		}else{return FALSE;}
	}

	public static function loggedName(){
		return isset($_SESSION['authUser']) ?  $_SESSION['authUser']: FALSE;
	}

	public static function loggedId(){
		return isset($_SESSION['authUserId']) ?  $_SESSION['authUserId']:FALSE;
	}
	
	public static function isLogged(){
		return !empty($_SESSION['authUser']);
	}


	public static function isAdmin(){
		return isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']=='1';
	}


	public static function logout(){
		unset($_SESSION['authUser']);
		unset($_SESSION['authUserId']);
		unset($_SESSION['isAdmin']);
	}

	/**
	User can browse this page if hes logged in
	*/
	public static function anonymousPage(){
		if(self::isLogged()){
			header("Location: index.php");
			exit;
		}
	}

	/**
	User can browse this page if hes an Administrator
	*/
	public static function adminPage(){
		if(!self::isAdmin()){
			header("Location: index.php");
			exit;
		}
	}

	/**
	User  this page is for standard users
	*/
	public static function publicPage(){
		if(self::isAdmin()){
			header("Location: admin_users.php");
			exit;
		}
	}
	



	/**
		A page that requires login
	*/
	public static function authPage(){
		if(!self::isLogged()){
			header("Location: login.php?required");
			exit;
		}
	}





	public static function getClienti($search=null, $id=NULL){
		$db = DB::getInstance();
		$dbconn = $db::getConnection();

		$query = "SELECT 
				  clienti.user_id, 
				  clienti.nome, 
				  clienti.cognome, 
				  clienti.indirizzo, 
				  clienti.telefono, 
				  clienti.login
				FROM 
				  clienti ";

		if(!empty($search)){
			$query .= " WHERE nome ILIKE '%$search%' ";
		}

		if(!empty($id)){
			$query .= " WHERE user_id = '$id' ";
		}

	
		$stmt=FALSE;
		
		try{
			
			$stmt=$dbconn->query($query);
			$r = $stmt->fetchAll();
			if(!empty($id)){
				return isset($r[0])?$r[0]:FALSE;
			}
			else{
				return $r;
			}

		}catch(PDOException  $e ){
			echo "Error: ".$e;
		}
			

		if (!$stmt) {
		  echo "An error occurred.\n";
		  exit;
		}


	}



	public static function getClientiList($search=null){
		//var_dump(self::getClienti($search, NULL));
		print('<table class="table">');
		printf('<tr> <th>#</th> <th>Login</th><th>Nome</th> <th>Cognome</th> <th>Edit</th> </tr>');
		foreach (self::getClienti($search, NULL) as $key => $value) {
		printf('<tr> <th scope="row">%s</th> <td>%s</td> <td>%s </td><td>%s </td> <td><a class="btn  btn-primary" role="button" href="admin_user_edit.php?id=%s">Edit </a></td></tr>',
				$value['user_id'],
				$value['login'],
				$value['nome'],
				$value['cognome'],
				$value['user_id']
			);
			
		}
		print('</table>');
	}



	public static function ClienteUpdate($userData){
		$db = DB::getInstance();
		$dbconn = $db::getConnection();

		$query = " UPDATE utenti 
		SET 
			nome = :nome, 
			cognome = :cognome, 
			login   = :login, 
			indirizzo = :indirizzo, 
			telefono = :telefono           
        WHERE 
        	user_id = :user_id";

		$stmt = $dbconn->prepare($query);                                  
		$stmt->bindParam(':nome', $userData['nome'], PDO::PARAM_STR);   
		$stmt->bindParam(':cognome', $userData['cognome'], PDO::PARAM_STR);   
		$stmt->bindParam(':login', $userData['login'], PDO::PARAM_STR);   
		$stmt->bindParam(':indirizzo', $userData['indirizzo'], PDO::PARAM_STR);   
		$stmt->bindParam(':telefono', $userData['telefono'], PDO::PARAM_STR);   
		$stmt->bindParam(':user_id', $userData['user_id'], PDO::PARAM_INT);   
		return $stmt->execute();


	}




}