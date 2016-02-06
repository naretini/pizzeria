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
				  array_to_json(array_agg(ingredienti.nome)) ingredienti
				FROM 
				  public.pizze, 
				  public.pizze_has_ingredienti, 
				  public.ingredienti
				WHERE 
				  pizze.pizza_id = pizze_has_ingredienti.pizza_id AND
				  pizze_has_ingredienti.ingredient_id = ingredienti.ingredient_id
				";

		if(!empty($search)){
			$query .= " AND pizze.nome ILIKE '%$search%' ";
		}

		$query .= "GROUP BY pizze.pizza_id;";


		$stmt=FALSE;
		
		try{
			$stmt=$dbconn->query($query);
			$data = array();
			while ($row=$stmt->fetch(PDO::FETCH_OBJ)) {
				$data[]= array(
					'pizza_id' 		=> $row->pizza_id,
					'nome' 			=> $row->nome,
					'prezzo' 		=> $row->prezzo,
					'ingredienti' 	=> $row->ingredienti,					
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


	public static function getPizzaList($search=NULL){
		print('<table class="table">');
		printf('<tr> <th>#</th> <th>Nome</th> <th>Prezzo</th> <th>View</th> </tr>');
		foreach (self::getPizza($search) as $key => $value) {
		printf('<tr> <th scope="row">%s</th> <td>%s</td> <td>%s &euro;</td> <td><a class="btn  btn-primary" role="button" onclick="viewDetails($(this))">Details </a></td></tr>',
				$value['pizza_id'],
				$value['nome'],
				$value['prezzo']
			);
			$obj_ingredienti = json_decode($value['ingredienti']);
			$ingredienti = "<h5>ingredienti</h5><ul>";
			foreach($obj_ingredienti as $ingrediente){
				$ingredienti .= sprintf("<li>%s</li>", $ingrediente);
			}
			$ingredienti .= "</ul>";

			printf('<tr style="display:none"><td colspan="4" >%s</td></tr>', $ingredienti);
		}
		print('</table>');
	}




}