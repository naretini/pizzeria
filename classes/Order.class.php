<?php

require_once 'DB.class.php';

class Order{

	public static function Insert($arrData){
		$result = true;
		$db = DB::getInstance();
		$dbconn = $db::getConnection();
		$stmt = $dbconn->prepare('INSERT INTO ordini (user_id, 	consegna, 	ora, 	indirizzo) 
			VALUES  (
				:user_id, 
				:consegna, 
				:ora, 
				:indirizzo
			) 
			RETURNING order_id;');
		// Inserisce la prima band
		$res = $stmt->execute(array(
		    ':user_id' => $arrData['cliente'],
		    ':consegna' => $arrData['data'],
		    ':ora' => $arrData['ora'],
		    ':indirizzo' => $arrData['indirizzo']
		));
		
		//LAST INSERT ID		
		$row = $stmt->fetch(); 
		$lastInsertId = $row['order_id'];
	
		$result = $result & $res;

		$pizze = json_decode($arrData['pizze'], TRUE);
		foreach($pizze as $pizza){
			$stmt2 = $dbconn->prepare("INSERT INTO ordini_has_pizze ( 
								pizza_id, 	
								order_id, 	
								tipo, 	
								quantita
							)
					VALUES
					(
						:pizza_id,
						:order_id,
						:pizza_tipo,
						:pizza_qta

					);"); 
			$res2 = $stmt2->execute(array(
			    ':pizza_id'	 	=> $pizza['id'], 
				':order_id'	 	=> $lastInsertId, 
				':pizza_tipo'	=> $pizza['tipo'], 
				':pizza_qta'	=> $pizza['qta']
			));
			
			$result = $result & $res2;
		}

		return $result;
	}



	public static function getOrders($user=NULL){

		$db = DB::getInstance();
		$dbconn = $db::getConnection();

		$query = "SELECT 
					  ordini.order_id, 
					  ordini.user_id, 
					  ordini.consegna, 
					  ordini.ora, 
					  ordini.indirizzo order_addr, 
					  array_to_json(array_agg(ordini_has_pizze.tipo)) pizze_tipi,
					  array_to_json(array_agg(ordini_has_pizze.quantita)) pizze_qta,
					  utenti.nome, 
					  utenti.cognome, 
					  utenti.indirizzo user_addr, 
					  utenti.telefono, 
					  func_order_total (ordini.order_id) order_total,
					  array_to_json(array_agg(pizze.nome)) pizze_nomi,
					  array_to_json(array_agg(pizze.prezzo)) pizze_prezzi
					FROM 
					  public.ordini, 
					  public.ordini_has_pizze, 
					  public.utenti, 
					  public.pizze
					WHERE 
					  ordini.order_id = ordini_has_pizze.order_id AND
					  utenti.user_id = ordini.user_id AND
					  pizze.pizza_id = ordini_has_pizze.pizza_id
					  ";

		if(!empty($user)){
			$query .= " AND utenti.user_id = '$user' ";
		}

		$query .= "GROUP BY  utenti.user_id, ordini.order_id ";
		$query .= "ORDER BY consegna desc, ora desc;";

		//var_dump($query);

		$stmt=FALSE;

		try{
			$stmt=$dbconn->query($query);
			$data = array();
			while ($row=$stmt->fetch(PDO::FETCH_OBJ)) {
				$data[]= array(
					'order_id' 					=> $row->order_id,
					'order_consegna' 			=> $row->consegna.' '.$row->ora.' '.$row->order_addr,
					'user_id' 					=> $row->user_id,
					'user_indirizzo' 			=> $row->user_addr,
					'telefono' 					=> $row->telefono,

					'order_total'				=> $row->order_total,
					'pizze_tipi' 				=> json_decode($row->pizze_tipi),					
					'pizze_qta' 				=> json_decode($row->pizze_qta),					
					'pizze_nomi' 				=> json_decode($row->pizze_nomi),					
				);

			}
			return $data;
		}catch(PDOException  $e ){
			echo "Error: ".$e;
		}
			

		if (!$stmt) {
		  echo "An error occurred.\n";
		  exit;
		}

		
	}

	public static function getOrdersList($user_id=NULL){

		//var_dump("userid ".$user_id);

		$data = self::getOrders($user_id);

		if(!empty($data)){
			print('<table class="table">');
			printf('<tr> <th>#</th> <th>Consegna</th> <th>Totale</th> <th>View</th><th>Action</th> </tr>');
			foreach ($data as $key => $value) {
			printf('<tr> <th scope="row">%s</th> <td>%s</td> <td>%s &euro;</td> <td><a class="btn  btn-primary" role="button" onclick="viewDetails($(this))">Details </a></td><td><a href="ordini_cancella.php?id='.$value['order_id'].'" class="btn  btn-warning" role="button" >Disdici</a></td></tr>',
					$value['order_id'],
					$value['order_consegna'],
					$value['order_total']
				);
				$obj_pizze_nomi = ($value['pizze_nomi']);
				$obj_pizze_tipi = ($value['pizze_tipi']);
				$obj_pizze_qta = ($value['pizze_qta']);

				$pizze = "<h5>Dettagli ordine</h5><ul>";
				foreach($obj_pizze_nomi as $k=>$v){
					$pizze .= sprintf("<li>%s</li>", $obj_pizze_qta[$k].' '. $obj_pizze_tipi[$k].' '. $obj_pizze_nomi[$k]);
				}
				$pizze .= "</ul>";

				printf('<tr style="display:none"><td colspan="5" style="background-color:#EEE;font-size:.7em;">%s</td></tr>', $pizze);
			}
			print('</table>');
		}else{
			print('
				<div id="general-err"  class="alert alert-danger" role="alert" >
						<label class="error">Non ci sono ordini</label>
					</div>
				');
		}
	}



	public static function deleteOrder($idOrder){
		try{			
			$db = DB::getInstance();
			$dbconn = $db::getConnection();

			$query = "DELETE FROM ordini WHERE order_id=:order_id;";
			$stmt = $dbconn->prepare($query);

			$res = $stmt->execute(array(
			    ':order_id' => $idOrder
			));

			return $res;
		}catch(PDOException  $e ){
			echo "Error: ".$e;
			return FALSE;
		}
		

	}

}