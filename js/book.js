const modal = new Modal(document.getElementsByClassName('modal')[0]);

let book_id = null;

const clearChildren = function(el){
    while(el.firstChild)
        el.removeChild(el.lastChild)
}

const addInfo = function(name, value){
    let bookInfo = document.getElementById('book-info');

    let row = document.createElement('div');
    row.classList.add('row');
    row.classList.add('space-between');

    let infoLabel = document.createElement('div');
    infoLabel.classList.add('col');
    infoLabel.classList.add('p-10');
    infoLabel.classList.add('description-name');
    infoLabel.innerText = name + ':';

    let infoValue = document.createElement('div');
    infoValue.classList.add('col');
    infoValue.classList.add('p-10');
    infoValue.classList.add('description-value');
    infoValue.innerText = value;

    row.append(infoLabel, infoValue);
    bookInfo.append(row);
}

const setCoverImage = function(src, bookName){
    let bookCover = document.getElementById('book-cover');

    bookCover.style.border = 'none';
    let img = document.createElement('img');
    img.src = src;
    img.alt = bookName;
    bookCover.appendChild(img);
}

const addRateRow = function(el, rating){
    clearChildren(el)

    let i = 0;

    const makeStar = (active) => {
        let tmp = document.createElement('div');
        tmp.classList.add('star');
        if(active)
            tmp.classList.add('active');
        return tmp;
    }

    for(; i < rating; i++)
        el.appendChild(makeStar(true));

    for(; i < 5; i++)
        el.appendChild(makeStar(false));
}

const addDownloadLink = function(){
    let downloadLink = document.getElementById('download-button');
    downloadLink.href = `download.php?book_id=${book_id}`;
}

const openLibraryCall = function(isbn, bookName){
    let isbnEndpoint = `https://openlibrary.org/isbn/${isbn}.json`;

    fetch(isbnEndpoint)
    .then((res)=>{
        if(res.status != 200){
            document.getElementById('book-cover').innerText = bookName;
            return
        }

        res.json()
        .then(data => {
            setCoverImage(`http://covers.openlibrary.org/b/isbn/${isbn}-M.jpg`, bookName);
            addInfo('Titolo originale', data.title);
            addInfo('Editore', data.publishers);
        })
    })
}

const showReviews = function(){
    let reviewDiv = document.getElementById('reviews');

    fetch(`api/v1/review.php?book_id=${book_id}`).then(res => {
        if(res.status != 200)
            return;

        res.json().then(data => {
            data.forEach(x => {
                let reviewTitleDiv = document.createElement('div');
                reviewTitleDiv.classList.add('review-title');
                reviewTitleDiv.innerText = x.title;

                let reviewRating = document.createElement('div');
                reviewRating.classList.add('rating-row');
                addRateRow(reviewRating, x.rating);

                let reviewTopDiv = document.createElement('div');
                reviewTopDiv.classList.add('row');
                reviewTopDiv.classList.add('review-top');

                reviewTopDiv.append(reviewTitleDiv, reviewRating)

                let reviewTextDiv = document.createElement('div');
                reviewTextDiv.classList.add('review-text');
                reviewTextDiv.innerText = x.content;


                let col = document.createElement('div');
                col.classList.add('col');
                col.classList.add('w-100');
                col.append(reviewTopDiv, reviewTextDiv);

                let reviewRow = document.createElement('div');
                reviewRow.classList.add('row');
                reviewRow.classList.add('start')

                reviewRow.append(col);

                reviewDiv.append(reviewRow);
            });
        })

    })

}

/* Render function */
const show = function(){
    fetch('api/v1/book.php?book_id=' + book_id)
    .then(res => {
        if(res.status != 200){
            modal.setTitle('Errore');
            modal.setContent('Libro non esistente');
            modal.show();
            return;
        }

        res.json()
        .then(data => {
            if(data.isbn != null){
                addInfo('ISBN', data.isbn);
                openLibraryCall(data.isbn, data.name);
            }
            else{
                document.getElementById('book-cover').innerText = data.name;
            }

            document.getElementById('bookName').innerText = data.name;
            document.getElementById('ownerName').innerText = data.ownerName;

            addRateRow(document.getElementById('rating-row'), data.rating);
            addDownloadLink();

        })

        showReviews();
    })
}

/* Main */
const queryString = document.location.search;
const getParams = new URLSearchParams(queryString);
if(getParams.has('book_id')){
    book_id = getParams.get('book_id');
    show();
}
else{
    modal.setContent('Nessun libro specificato');
    modal.setTitle('Errore');
    modal.show();
}

/* Form handler */
const errorBanner = document.getElementsByClassName("error-banner")[0];
const showFormError = function(msg) {
    errorBanner.innerText = msg;
}

const submitButton = document.getElementById('submit');
if(submitButton != null){
    submitButton.onclick = function(){

        let title = document.getElementById('title').value.trim();
        let content = document.getElementById('content').value.trim();
        let checkedStar = Array.from(
            document.getElementsByClassName('star-radio')
        ).filter((x) => x.checked);

        if(title.length == 0 || content.length == 0 || checkedStar.length == 0){
            showFormError('Specificare titolo, descrizione e voto!');
            return false;
        }

        let data = new FormData();
        data.append('book_id', book_id);
        data.append('title', title);
        data.append('content', content);
        data.append('rating', checkedStar[0].value);

        let apiEndpoint = 'api/v1/review.php';

        fetch(apiEndpoint,{
            method : 'POST',
            body: data
        }).then((res) => {
            if(res.status == 201){
                modal.setTitle("Recensione completata");
                modal.setContent("Recensione aggiunta correttamente!");
            }
            else if(res.status == 202){
                modal.setTitle("Recensione modificata");
                modal.setContent("Recensione modificata correttamente");
            }
            else{
                modal.setTitle("Errore");
                modal.setContent("Errore durante l'aggiunta della recensione!");
            }
            modal.show();
        });

        showFormError('');
    }
}