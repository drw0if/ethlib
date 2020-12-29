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