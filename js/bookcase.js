let bookList = document.getElementById('bookList');
let shareUrl = location.href.replace('bookcase.php', 'shared.php?file=');
let toast = document.getElementById('toast');

//Create a share button element for the book x
const makeShareButton = (x) => {
    let shareButton = document.createElement('div');
    shareButton.classList.add('book-button', 'background-red', 'm-10');

    shareButton.onclick = () => {
        copyTextToClipboard(shareUrl + x.local_name)
        toast.children[0].innerText = 'Link condivisibile copiato negli appunti';
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        },2000);
    }

    let p = document.createElement('p');
    p.innerText = 'Condividi';

    shareButton.appendChild(p);
    return shareButton;
}

//Create a delete button element for the book x
const makeDeleteButton = (x) => {
    let deleteButton = document.createElement('div');
    deleteButton.classList.add('book-button', 'background-red', 'm-10');

    //Add a delete button
    deleteButton.onclick = () => {
        fetch(`api/v1/book.php?book_id=${x.book_id}`,{
            method : 'DELETE'
        })
        .then(res => {
            toast.children[0].innerText =
                (res.status == 200) ? 'Elemento cancellato con successo' :
                                    'Errore durante la cancellazione';

            toast.style.display = 'block';
            update();
            setTimeout(() => {
                toast.style.display = 'none';
            },2000);

        })
    }

    let p = document.createElement('p');
    p.innerText = 'Cancella';

    deleteButton.appendChild(p);
    return deleteButton;
}

const update = () => {
    let endpoint = 'api/v1/bookcase.php';

    fetch(endpoint)
    .then(res => {
        if(res.status != 200)
            return;

        res.json()
        .then(json => {
            clearChildren(bookList);

            json.forEach(x => {
                let bookRow = makeBookRow(x);

                //If book is private add a share button
                if(x.private == true){
                    bookRow.appendChild(makeShareButton(x));
                }

                bookRow.appendChild(makeDeleteButton(x));

                bookList.appendChild(bookRow);
            })
        })
    })
}

update();