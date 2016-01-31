<?php
require_once 'DB.class.php';
/**
Implements Singleton 
*/
class Pizzeria{

	private static  $pizzeria = NULL;
	
	private function __construct(){	}

	private static function getInstance(){
		if(is_null(self::$pizzeria)){			
			//connect to a database 
			self::$pizzeria = new Pizzeria();
		}
		return self::$pizzeria;
	}



	public static function getPizza($search=NULL){

		$db = DB::getInstance();
		$dbconn = $db::getConnection();

		$query = "SELECT 
				  pizze.pizza_id, 
				  pizze.nome, 
				  pizze.prezzo, 
				  array_agg(ingredienti.nome)
				FROM 
				  public.pizze, 
				  public.pizze_has_ingredienti, 
				  public.ingredienti
				WHERE 
				  pizze.pizza_id = pizze_has_ingredienti.pizza_id AND
				  pizze_has_ingredienti.pizza_id = ingredienti.ingredient_id
				";

		if(!empty($search)){
			$query .= " AND pizze.nome ILIKE '%$search%' ";
		}

		$query .= "GROUP BY pizze.pizza_id;";

		$result = pg_query($dbconn, $query);
		if (!$result) {
		  echo "An error occurred.\n";
		  exit;
		}

		$data = array();

		while ($row = pg_fetch_row($result)) {

			$data[]= array(
				'pizza_id' 		=> $row[0],
				'nome' 			=> $row[1],
				'prezzo' 		=> $row[2],
				'ingredienti' 	=> $row[3],
				

			);

		}
		return $data;
	}


	public static function getPizzaList($search=NULL){
		print('<table class="table">');
		printf('<tr> <th>#</th> <th>Nome</th> <th>Prezzo</th> </tr>');
		foreach (self::getPizza($search) as $key => $value) {
		printf('<tr> <th scope="row">%s</th> <td>%s</td> <td>%s &euro;</td> </tr>',
				$value['pizza_id'],
				$value['nome'],
				$value['prezzo']
			);
		}
		print('</table>');
	}




}