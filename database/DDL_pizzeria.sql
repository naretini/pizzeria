/* cancellazioni preventive per caricamenti multipli in caso di errore */
DROP TABLE if exists utenti CASCADE;
DROP TABLE if exists ordini CASCADE;
DROP TABLE if exists pizze CASCADE;
DROP TABLE if exists ingredienti CASCADE;
DROP TABLE if exists amministratori CASCADE;


CREATE TABLE amministratori   ( 
    superuser_id serial not null,
    login text not null,
    password text not null,
    primary key(superuser_id));


CREATE TABLE utenti   ( 
    user_id serial not null,
    nome text not null,
    cognome text not null,
    indirizzo text not null,
    telefono text not null,
    login text not null,
    password text not null,
    primary key(user_id));

CREATE INDEX login_idx ON utenti (login);
CREATE INDEX password_idx ON utenti (password);

CREATE TABLE ordini   ( order_id serial not null,
     user_id integer REFERENCES utenti,
     consegna date not null,
     ora time not null ,
     indirizzo text,
     primary key(order_id));

CREATE INDEX dataconsegna_idx ON ordini (consegna);
CREATE INDEX oraconsegna_idx ON ordini (ora);


CREATE TABLE pizze   ( 
    pizza_id serial not null,
    nome text not null,
    prezzo numeric,
    primary key(pizza_id));

CREATE INDEX nomepizza_idx ON pizze (lower(nome));


CREATE TYPE tipo_pizza AS ENUM ('normale', 'calzone');
CREATE TABLE ordini_has_pizze   (
    pizza_id integer REFERENCES pizze ON DELETE RESTRICT,
    order_id integer REFERENCES ordini ON DELETE CASCADE,
    tipo tipo_pizza not null,
     quantita int,
     primary key(pizza_id, order_id)
);


CREATE TABLE ingredienti( 
    ingredient_id serial not null,
    nome text not null,
    quantita int,
    primary key(ingredient_id));

CREATE INDEX nomeingrediente_idx ON ingredienti (lower(nome));


CREATE TABLE pizze_has_ingredienti ( 
    pizza_id integer REFERENCES pizze ON DELETE CASCADE,
    ingredient_id integer REFERENCES ingredienti ON DELETE RESTRICT,
    primary key(pizza_id, ingredient_id)
);





