# Progetto per l'esame di Progettazione web

## Idea
Ethlib vuole essere una web application utile per la condivisione di libri in formato pdf ed epub. E' stato scritto per le seguenti tecnologie e versioni:

- HTML 5
- CSS 3
- Javascript
- PHP 5.5.15
- MySQL 5.6.20

In particolare è pensato per funziona con il pacchetto "Web all in one" fornito durante il corso.

## Note
Prevede un database che può essere creato usando il file [generate.sql](generate.sql).

E' necessario abilitare `php_fileinfo.dll` in `php.ini` affinché la parte di upload funzioni adeguatamente.

La cartella per l'upload dei file è `upload` tuttavia è sconsigliato caricare file in una cartella raggiungibile tramite web server. Dato che per questo progetto bisognava rimanere nei limiti della singola cartella, senza poterne creare altre fuori dall'ambiente di apache, si è deciso di aggiungere un `.htaccess` che vietasse l'accesso diretto alla cartella degli upload.