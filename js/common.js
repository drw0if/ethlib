const makeBookRow = (book) => {
    let bookText = document.createElement('div');
    bookText.classList.add('book-text');

    let p = document.createElement('p');
    p.innerText = book['name'];
    bookText.appendChild(p);

    p = document.createElement('p');
    p.innerText = book['isbn'];
    bookText.appendChild(p);

    let bookRow = document.createElement('div');
    bookRow.classList.add('book-row');
    bookRow.appendChild(bookText);

    return bookRow;
}

const clearChildren = function(el){
    while(el.firstChild)
        el.removeChild(el.lastChild)
}