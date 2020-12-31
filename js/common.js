//Create a book row with name, isbn and redirect button
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

    let bookButton = document.createElement('div');
    bookButton.classList.add('book-button', 'background-red');

    let anchor = document.createElement('a');
    anchor.href = `book.php?book_id=${book['book_id']}`;
    anchor.innerText = '>';
    bookButton.appendChild(anchor);

    bookButton.appendChild(anchor);
    bookRow.appendChild(bookButton);

    return bookRow;
}

//Delete all children of el node
const clearChildren = function(el){
    while(el.firstChild)
        el.removeChild(el.lastChild)
}

//Put data into the clipboard
//copied from https://stackoverflow.com/questions/400212/how-do-i-copy-to-the-clipboard-in-javascript
//because in the provided firefox version there is no support for
//navigator.clipboard
function fallbackCopyTextToClipboard(text) {
    var textArea = document.createElement("textarea");
    textArea.value = text;

    // Avoid scrolling to bottom
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";

    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        var successful = document.execCommand('copy');
        var msg = successful ? 'successful' : 'unsuccessful';
    } catch (err) {
    }

    document.body.removeChild(textArea);
}

function copyTextToClipboard(text) {
    if (!navigator.clipboard) {
        fallbackCopyTextToClipboard(text);
        return;
    }
    navigator.clipboard.writeText(text);
}