const modal = new Modal(document.getElementsByClassName('modal')[0]);
const errorBanner = document.getElementsByClassName("error-banner")[0];
const showError = function(msg) {
    errorBanner.innerText = msg;
}

document.getElementById('submit').onclick = function(){

    let book_id = document.getElementById('book_id').value;
    let edit = document.getElementById('edit').value;
    let title = document.getElementById('title').value.trim();
    let content = document.getElementById('content').value.trim();
    let checkedStar = Array.from(
        document.getElementsByClassName('star-radio')
    ).filter((x) => x.checked);

    if(title.length == 0 || content.length == 0 || checkedStar.length == 0){
        showError('Specificare titolo, descrizione e voto!');
        return false;
    }

    let data = new FormData();
    data.append('book_id', book_id);
    data.append('title', title);
    data.append('content', content);
    data.append('rating', checkedStar[0].value);

    let apiEndpoint = 'api/v1/review.php';
    if(edit)
        apiEndpoint += '?edit';

    fetch(apiEndpoint,{
        method : 'POST',
        body: data
    }).then((res) => {
        if(res.status == 201){
            modal.setTitle("Recensione completata");
            if(edit)
                modal.setContent("Recensione modificata correttamente!");
            else
                modal.setContent("Recensione aggiunta correttamente!");
            document.getElementById('edit').value = 1;
        }
        else{
            modal.setTitle("Errore");
            modal.setContent("Errore durante l'aggiunta della recensione!");
        }
        modal.show();
    });

    showError('');
}