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