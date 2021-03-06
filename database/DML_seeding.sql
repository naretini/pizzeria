TRUNCATE amministratori;
TRUNCATE utenti cascade;
TRUNCATE pizze CASCADE;
TRUNCATE ordini CASCADE;
TRUNCATE ingredienti CASCADE ;
TRUNCATE ordini_has_pizze;
TRUNCATE pizze_has_ingredienti;




-- UTENTI
INSERT INTO utenti 
(
	user_id, 
	nome, 
	cognome, 
	telefono, 
	indirizzo, 
	login, 
	password,
	is_admin
)
VALUES
('1', 'Nicola', 'Aretini', '0422123456', 'via roma 15 quarto Altino', 'nicola', 'nicola',0),
('2', 'Pippo', 'Disney', '02123456', 'via zu 23 Topolinia', 'pippo', 'pippo',0),
('3', 'Pluto', 'Disney', '08456213', 'via ro 54 Topolinia', 'pluto', 'pluto',0),
('4', 'Nicola', 'Aretini', '0422123456', 'via roma 15 quarto Altino', 'admin', 'admin',1);

-- updates serial count for subsequent inserts
SELECT setval('utenti_user_id_seq', (SELECT MAX(user_id) FROM utenti));


-- INGREDIENTI
INSERT INTO ingredienti 
(
  ingredient_id, 
  nome, 
  quantita
)
VALUES
	(1,  'Farina 500 gr', '300' ),
	(2,  'Pomodoro salsa 50gr', '350' ),
	(3,  'Prosciutto cotto 30gr', '450' ),
	(4,  'Mozzarella 60gr', '700' ),
	(5,  'Rucola 20gr', '220' ),
	(6,  'Alici 10gr', '110' ),
	(7,  'Olive nere 35gr', '210' ),
	(8,  'Salame  45gr', '210' ),
	(9,  'Grana  45gr', '310' ),
	(10, 'Bresaola  45gr', '255' ),
	(11, 'Peperoni  45gr', '280' ),
	(12, 'Melanzane  45gr', '290' );

-- updates serial count for subsequent inserts
SELECT setval('ingredienti_ingredient_id_seq', (SELECT MAX(ingredient_id) FROM ingredienti));


-- PIZZE
INSERT INTO pizze 
(
	pizza_id, 
	nome, 
	prezzo
)
VALUES
	(1, 'Margherita', 4.5),
	(2, 'Romana', 5.0),
	(3, 'Siciliana', 4.5),
	(4, 'Diavola', 5.5),
	(5, 'Valtellina', 7.5),
	(6, 'Verdure', 6.5);

SELECT setval('pizze_pizza_id_seq', (SELECT MAX(pizza_id) FROM pizze));

-- PIZZE_HAS_INGREDIENTI
INSERT INTO  pizze_has_ingredienti
(
	pizza_id, 
  	ingredient_id
)
VALUES
	(1, 1),	(1, 2),	(1, 4),
	(2, 1),	(2, 2),	(2, 4),	(2, 7),
	(3, 1),	(3, 2),	(3, 4),	(3, 6),
	(4, 1),	(4, 2),	(4, 4),	(4, 8),
	(5, 1),	(5, 2),	(5, 4),	(5, 10), (5, 5), (5, 9),
	(6, 1),	(6, 2),	(6, 4),	(6, 11), (6, 12);



-- ORDINI
INSERT INTO ordini 
(
	order_id, 
	user_id, 
	consegna, 
	ora, 
	indirizzo,
	status
)
VALUES
	(1, 1, '2016-01-25', '21:00', 'via foppa 8', 'pending'),
	(2, 3, '2016-01-26', '20:00', 'via augusta 3', 'delivered'),
	(3, 2, '2016-01-27', '19:30', 'via umberto I 7', 'pending'),
	(4, 1, '2016-01-27', '20:20', 'via foppa 8', 'delivered');

SELECT setval('ordini_order_id_seq', (SELECT MAX(order_id) FROM ordini));

-- ORDINI_HAS_PIZZE
INSERT INTO ordini_has_pizze
(
	pizza_id, 
	order_id, 
	tipo, 
	quantita
)
VALUES
	(1, 1, 'calzone', '1'),
	(3, 1, 'normale', '1'),
	(2, 2, 'calzone', '1'),
	(3, 2, 'normale', '3'),
	(5, 2, 'normale', '1'),
	(1, 3, 'calzone', '1'),
	(5, 3, 'calzone', '2'),
	(6, 4, 'normale', '1'),
	(4, 4, 'normale', '2');



