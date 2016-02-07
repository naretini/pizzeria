/* cancellazioni preventive per caricamenti multipli in caso di errore */
DROP TABLE if exists utenti CASCADE;
DROP TABLE if exists ordini CASCADE;
DROP TABLE if exists pizze CASCADE;
DROP TABLE if exists ingredienti CASCADE;
DROP TABLE if exists ordini_has_pizze;
DROP TABLE if exists pizze_has_ingredienti;

-- preprare enumerative type for ordini_has_pizze
DROP TYPE  IF EXISTS tipo_pizza;
CREATE TYPE tipo_pizza AS ENUM ('normale', 'calzone');


DROP TYPE  IF EXISTS order_status;
CREATE TYPE order_status AS ENUM ('pending', 'delivered');


CREATE TABLE utenti   ( 
    user_id serial not null,
    nome text not null,
    cognome text not null,
    indirizzo text not null,
    telefono text not null,
    login text not null,
    password text not null,
    is_admin SMALLINT not null default 0,
    primary key(user_id));

CREATE unique INDEX login_idx ON utenti (login);
CREATE INDEX password_idx ON utenti (password);
CREATE INDEX nome_idx ON utenti (nome);
CREATE INDEX cognome_idx ON utenti (cognome);
CREATE INDEX indirizzo_idx ON utenti (indirizzo);

DROP VIEW IF EXISTS clienti;
CREATE VIEW clienti AS SELECT *  FROM utenti  WHERE is_admin = 0;
DROP VIEW IF EXISTS admin;
CREATE VIEW  admin   AS SELECT *  FROM utenti  WHERE is_admin = 1;

-- RESTRICT prevents deletion of a referenced row
-- CASCADE specifies that when a referenced row is deleted, row(s) referencing it 
-- should be automatically deleted as well.
CREATE TABLE ordini   ( 
     order_id serial not null,
     user_id integer REFERENCES utenti,
     consegna date not null,
     ora time not null ,
     indirizzo text,
     status order_status not null default 'pending',
     primary key(order_id));

CREATE unique INDEX order_idx ON ordini (user_id, consegna, ora);
CREATE INDEX user_id_idx ON ordini (user_id);
CREATE INDEX dataconsegna_idx ON ordini (consegna);
CREATE INDEX oraconsegna_idx ON ordini (ora);
CREATE INDEX indirizzo_ordine_idx ON ordini (indirizzo);


CREATE TABLE pizze   ( 
    pizza_id serial not null,
    nome text not null,
    prezzo numeric,
    primary key(pizza_id));

CREATE INDEX nomepizza_idx ON pizze (lower(nome));



CREATE TABLE ordini_has_pizze   (
    job_id serial not null,
    pizza_id integer REFERENCES pizze ON DELETE RESTRICT,
    order_id integer REFERENCES ordini ON DELETE CASCADE,
    quantita int,
    tipo tipo_pizza not null,
     primary key(job_id)
);
CREATE INDEX pizza_idx ON ordini_has_pizze (pizza_id);
CREATE INDEX ordine_idx ON ordini_has_pizze (order_id);


CREATE TABLE ingredienti( 
    ingredient_id serial not null,
    nome text not null,
    quantita int,
    primary key(ingredient_id));

CREATE INDEX nomeingrediente_idx ON ingredienti (lower(nome));
CREATE INDEX qta_idx ON ingredienti (quantita);


CREATE TABLE pizze_has_ingredienti ( 
    pizza_id integer REFERENCES pizze ON DELETE CASCADE,
    ingredient_id integer REFERENCES ingredienti ON DELETE RESTRICT,
    primary key(pizza_id, ingredient_id)
);
CREATE INDEX pizza2_idx ON pizze_has_ingredienti (pizza_id);
CREATE INDEX ingredient_idx ON pizze_has_ingredienti (ingredient_id);



-- FUNCTIONS / TRIGGERS

DROP FUNCTION IF EXISTS func_order_total (numeric);
CREATE FUNCTION func_order_total (numeric) RETURNS numeric AS $$
    SELECT 
SUM(ordini_has_pizze.quantita * pizze.prezzo)  
FROM 
  ordini, 
  ordini_has_pizze, 
  pizze
WHERE 
  ordini.order_id = ordini_has_pizze.order_id AND
  pizze.pizza_id = ordini_has_pizze.pizza_id  AND
  ordini.order_id = $1

  group by ordini.order_id
$$ LANGUAGE SQL;



CREATE OR REPLACE FUNCTION updateStorage() 
RETURNS TRIGGER AS $trigger$
    BEGIN
    
      UPDATE
       ingredienti 
      SET
       quantita=quantita-NEW.quantita

     where ingredienti.ingredient_id IN (
        SELECT pizze_has_ingredienti.ingredient_id FROM pizze_has_ingredienti 
        WHERE pizze_has_ingredienti.pizza_id=NEW.pizza_id
      );
     return NEW;
        END;
$trigger$ language plpgsql;


CREATE OR REPLACE FUNCTION updateStorageDel() 
RETURNS TRIGGER AS $triggerDel$
    BEGIN
    
      UPDATE
       ingredienti 
      SET
       quantita=quantita+OLD.quantita

     where ingredienti.ingredient_id IN (
        SELECT pizze_has_ingredienti.ingredient_id FROM pizze_has_ingredienti 
        WHERE pizze_has_ingredienti.pizza_id=OLD.pizza_id
      );
     return NEW;
        END;
$triggerDel$ language plpgsql;


DROP TRIGGER  IF EXISTS trigger on ordini_has_pizze;
CREATE TRIGGER trigger
    AFTER INSERT ON ordini_has_pizze
    FOR EACH ROW
    EXECUTE PROCEDURE updateStorage();

DROP TRIGGER  IF EXISTS triggerDel on ordini_has_pizze;    
CREATE TRIGGER triggerDel
    AFTER DELETE ON ordini_has_pizze
    FOR EACH ROW
    EXECUTE PROCEDURE updateStorageDel();






