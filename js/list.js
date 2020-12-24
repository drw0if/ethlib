let bookList = document.getElementById('bookList');
const queryString = document.location.search;
const getParams = new URLSearchParams(queryString);

let offset = 0;
let query = getParams.get('query');

const update = () => {
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
                let bookRow = makeBookRow(x);

                let bookButton = document.createElement('div');
                bookButton.classList.add('book-button', 'background-red');

                let anchor = document.createElement('a');
                anchor.href = `book.php?book_id=${x['book_id']}`;
                anchor.innerText = '>';
                bookButton.appendChild(anchor);

                bookButton.appendChild(anchor);
                bookRow.appendChild(bookButton);

                bookList.appendChild(bookRow);
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