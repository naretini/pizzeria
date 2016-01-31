<?php

require_once 'DB.class.php';

class Order{

	public static function Insert($arrData){

		$result = true;

		$db = DB::getInstance();
		$dbconn = $db::getConnection();

		$query_ordine = "INSERT INTO ordini (	 	user_id, 	consegna, 	ora, 	indirizzo) VALUES 	
		({$arrData['cliente']}, '{$arrData['data']}', '{$arrData['ora']}', '{$arrData['indirizzo']}');";

echo $query_ordine;

		$res = pg_query($query_ordine);
		$lastInsertId = pg_last_oid($res);

		$result = $result & $res;

		$pizze = json_decode($arrData['pizze'], TRUE);


		foreach($pizze as $pizza){
			$query_pizza_rel = "INSERT INTO ordini_has_pizze (	pizza_id, 	order_id, 	tipo, 	quantita)
					VALUES
					({$pizza['id']}, $lastInsertId, '{$pizza['tipo']}', '{$pizza['qta']}');"; 
			$res = pg_query($query_ordine);
			$result = $result & $res;
		}

		return $result;
	}



}