<?php
    session_start();
?>

<?php require_once 'template/header.php'; ?>

<div class="row">
    <h2>Libri Pubblici</h2>
</div>

<div class="col" id="bookList"></div>

<div class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <header>
                <p>Manuale utente</p>
                <button class="modal-close">
                    X
                </button>
            </header>
            <div class="modal-main">
                <p class="modal-text">
                    Ethlib è una libreria etica pensata per la condivisione di ebook in
                    formato pdf o epub. </br></br>

                    Nella pagina principale abbiamo la lista di libri pubblici, nella parte superiore
                    è presente una casella di testo che permettere la ricerca, questa è locata in
                    qualsiasi pagina per migliorare l'esperienza utente. Il caricamento della
                    lista dei libri è dinamica quindi andando in basso si avranno sempre più
                    proposte. Alcuni libri hanno a disposizione anche il codice ISBN. Per visionare
                    la scheda di ogni libro cliccare sul corrispondente bottone <b>></b>.</br></br>

                    Per caricare nuovi libri è necessario iscriversi tramite l'apposita
                    pagina raggiungibile premendo sul tasto <b>sign up</b>, verranno chiesti un
                    username, una email ed una password. La password deve essere lunga almeno
                    8 caratteri e contenere lettere maiuscole, minuscole, numeri e caratteri
                    speciali. </br></br>

                    Se sei già utente puoi effettuale il login sulla pagina <b>sig in</b>. </br></br>

                    Una volta iscritti per caricare un libro si può andare nella sezione <b>upload</b>:
                    selezionare il file corretto, compilare con il nome che si vuole dare al libro,
                    se si vuole si può aggiungere un ISBN e si può scegliere di renderlo pubblico o meno.
                    In fine si preme il pulsante per caricarlo e si avrà l'avanzamento della barra in basso. </br></br>

                    Nel caso in cui si specifichi un ISBN valido nella scheda del libro saranno
                    visualizzate informazioni aggiuntive prese dalle API di OpenLibrary.</br></br>

                    Nella scheda dei libri pubblici è possibile aggiungere delle recensioni in modalità testuale,
                    e tramite un voto in stelle. La scheda del libro ha anche la media dei voti per fornire
                    una linea di massima generale sulla qualità del libro.</br></br>

                    Ogni utente registrato ha una sezione <b>Libreria</b> in cui vengono elencati i proprio upload.
                    In questa sezione è altresì possibile eliminare e condividere i propri libri. </br></br>

                    L'amministratore ha a disposizione due pagine per gestire utenti e libri.
                </p>
            </div>
            <footer>
                <button class="modal-button background-red text-white">
                    OK
                </button>
            </footer>
        </div>
    </div>
</div>

<div id="clip">?</div>

<script src="js/modal.js"></script>
<script src="js/list.js"></script>
<?php require_once 'template/footer.php'; ?>