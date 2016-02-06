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



	public static function loggedName(){
		return isset($_SESSION['authUser']) &&  $_SESSION['authUser'];
	}

	public static function loggedId(){
		return isset($_SESSION['authUserId']) &&  $_SESSION['authUserId'];
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
	}

	public static function anonymousPage(){
		if(self::isLogged()){
			header("Location: index.php");
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



}