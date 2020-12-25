function Modal(modal, onCloseHandler = null){
    this.modal = modal

    let self = this;
    let closeHandler = function(){
        self.hide();
        if(onCloseHandler != null){
            onCloseHandler();
        }
    };

    //Add onClick event to all the buttons
    let btns = modal.getElementsByClassName('modal-close');
    for(let j = 0; j < btns.length; j++)
        btns[j].onclick = closeHandler;

    btns = modal.getElementsByClassName('modal-button');
    for(let j = 0; j < btns.length; j++)
        btns[j].onclick = closeHandler;

    this.setTitle = function(title){
        let titleElement = this.modal.querySelector('header p');
        titleElement.innerText = title;
    };

    this.setContent = function(content){
        let contentElement = this.modal.querySelector('.modal-text');
        contentElement.innerText = content;
    }

    this.show = function(){
        this.modal.classList.add('show')
    }

    this.hide = function(){
        this.modal.classList.remove('show')
    }
}

