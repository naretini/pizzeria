CREATE FUNCTION func_order_total (numeric) RETURNS numeric AS $$
    SELECT 
SUM(ordini_has_pizze.quantita * pizze.prezzo)  
FROM 
  public.ordini, 
  public.ordini_has_pizze, 
  public.pizze
WHERE 
  ordini.order_id = ordini_has_pizze.order_id AND
  pizze.pizza_id = ordini_has_pizze.pizza_id  AND
  ordini.order_id = $1

  group by ordini.order_id
$$ LANGUAGE SQL;