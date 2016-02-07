# pizzeria


Si vuole realizzare un’applicazione web per una pizzeria. Verranno venduti diversi tipi di pizze con ingredienti reperibili a magazzino. Le pizze avranno un tipo, nome e un prezzo. 
Il magazzino conterrà le quantità degli ingredienti e quindi la loro disponibilità.
Gli utenti potranno accedere all’applicazione (in via anonima) per consultare il catalogo pizze, visualizzare le pizze e per ricerca.
Gli utenti registrandosi (con nome e cognome, indirizzo, telefono, login e password) e quindi autenticandosi potranno effettuare ordini, visualizzare gli ordini ed eventualmente cancellarli se non ancora consegnati. Gli ordini avranno due stati pending (ancora cancellabili) e delivered, non più modificabili.
Gli ordini dell’utente, conterranno informazioni su tipo e numero di pizze, giorno di consegna, ora di consegna preferita, indirizzo di consegna.
L’applicazione inoltre prevede un accesso amministratore per la gestione degli utenti e degli ordini: modifica dei dati degli utenti e degli ordini.
Si possono individuare le seguenti  entità che partecipano ai casi d’uso nella specifica del progetto:

pizze
ingredienti
clienti
ordini
amministratori
Le relazioni individuate saranno :
le pizze sono  composte da ingredienti (espressi a DB come unità scalari per semplicità)
gli ordini contengono lavorazioni 
Note:
L’entità magazzino non viene rappresentata concettualmente ma non considerata nell’implementazione E.R. perché unica e assimilabile al concetto di ingredienti e quantità. 

Per tipologie di pizze si assume che tutte le pizze possano essere realizzate con forme differenti: (pizza piana circolare, o pizza arrotolata tipo calzone)
Normale e calzone verranno quindi rappresentate concettualmente come sottoclassi di copertura di Pizze. Nel diagramma relazionale invece verranno rappresentate come “lavorazioni” di prodotti e come relazione n...n tra pizze e ordini.



Install instructions:

execute on postgres :
database/DDL_pizzeria.sql
database/DML_seeding.sql