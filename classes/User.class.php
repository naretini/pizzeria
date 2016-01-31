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
					'{$arrData['nome']}', 
					'{$arrData['cognome']}',
					'{$arrData['telefono']}',
					'{$arrData['indirizzo']}',
					'{$arrData['login']}',
					'{$arrData['password']}'
				);";

			$result = pg_query($dbconn, $query);
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
			$query = "select user_id,nome FROM  utenti 
				WHERE 
				login='{$arrData['login']}' AND
				password='{$arrData['password']}'
				;";

			$result = pg_query($dbconn, $query);


			if (!$result) {
			  return FALSE;
			}

			if ($row = pg_fetch_row($result)) {
				$_SESSION['authUserId'] = $row[0];
				$_SESSION['authUser'] = $row[1];
				return TRUE;
			}

		}

	}



	public function loggedName(){
		return $_SESSION['authUser'];
	}

	public function loggedId(){
		return $_SESSION['authUserId'];
	}
	
	public function isLogged(){
		return !empty($_SESSION['authUser']);
	}

	public function logout(){
		unset($_SESSION['authUser']);
		unset($_SESSION['authUserId']);
	}

	public function anonymousPage(){
		if(self::isLogged()){
			header("Location: index.php");
			exit;
		}
	}

	/**
		A page that requires login
	*/
	public function authPage(){
		if(!self::isLogged()){
			header("Location: login.php?required");
			exit;
		}
	}



}