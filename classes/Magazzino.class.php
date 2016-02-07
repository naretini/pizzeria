<?php

require_once 'DB.class.php';


class Magazzino{

public static function getIngredient($user=NULL){

		$db = DB::getInstance();
		$dbconn = $db::getConnection();

		$query = "SELECT 
					  ingredienti.ingredient_id, 
					  ingredienti.nome, 
					  ingredienti.quantita
					FROM 
					  ingredienti order by ingredient_id ASC; ";
		$stmt=FALSE;
		try{
			$stmt=$dbconn->query($query);
			return $stmt->fetchAll();
		}catch(PDOException  $e ){
			echo "Error: ".$e;
		}
		
		if (!$stmt) {
		  echo "An error occurred.\n";
		  exit;
		}
	}

	public static function getIngredientList($search=null){
		//var_dump(self::getClienti($search, NULL));
		print('<table class="table">');
		printf('<tr> <th>#</th> <th>Nome</th><th>Quantità</th> </tr>');
		foreach (self::getIngredient($search, NULL) as $key => $value) {
		printf('<tr> <th scope="row">%s</th> <td>%s</td> <td>%s </td></tr>',
				$value['ingredient_id'],
				$value['nome'],
				$value['quantita']
			);
			
		}
		print('</table>');
	}

}