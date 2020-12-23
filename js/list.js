let bookList = document.getElementById('bookList');
const queryString = document.location.search;
const getParams = new URLSearchParams(queryString);

let offset = 0;
let query = getParams.get('query');

const makeBookRow = (book) => {
    let bookText = document.createElement('div');
    bookText.classList.add('book-text');

    let p = document.createElement('p');
    p.innerText = book['name'];
    bookText.appendChild(p);

    p = document.createElement('p');
    p.innerText = book['isbn'];
    bookText.appendChild(p);

    let bookButton = document.createElement('div');
    bookButton.classList.add('book-button', 'background-red');

    let anchor = document.createElement('a');
    anchor.href = `book.php?book_id=${book['book_id']}`;
    anchor.innerText = '>';
    bookButton.appendChild(anchor);

    let bookRow = document.createElement('div');
    bookRow.classList.add('book-row');
    bookRow.appendChild(bookText);
    bookRow.appendChild(bookButton);

    return bookRow;
}

const update = ()=>{
    let endpoint = `api/v1/list.php?offset=${offset}`;
    endpoint += (query != null) ? `&query=${query}` : '';

    fetch(endpoint)
    .then(res => {
        if(res.status != 200)
            return;

        res.json()
        .then(json => {
            offset += json.length;

            json.forEach(x => {
                bookList.appendChild(makeBookRow(x));
            });

            if(offset == 0){
                let errorRow = document.createElement('div');
                errorRow.classList.add('row');

                let errorText = document.createElement('h2');
                errorText.innerText = 'Nessun risultato';

                errorRow.appendChild(errorText);
                bookList.append(errorRow);
                return;
            }

            document.onscroll = (json.length == 0) ? null : (() => {
                if (bookList.scrollTop + bookList.offsetHeight + 100 > bookList.offsetHeight) {
                    document.onscroll = null;
                    update();
                }
            });
        });
    });
}

update();