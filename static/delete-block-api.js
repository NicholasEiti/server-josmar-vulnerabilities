class DeleteBLockElement extends HTMLElement {
    LOADING_MSG = 'Carregando...'

    GET_TITLE_FN = {
        drawer: (drawer) => `Remover armário ${drawer.name}`,
        key: (key) => `Remover chave ${key.name}`
    }

    URL_TAGS = {
        drawer: {
            get: 'drawer_get',
            delete: 'drawer_remove',
            list: "/drawers/",
            back: (drawer) => "/drawers/" + drawer.id,
        },
        key: {
            get: 'key_get',
            delete: 'key_remove',
            list: "/keys/",
            back: (key) => "/keys/" + key.id,
        }
    }

    constructor(...args) { super(...args); }

    connectedCallback() {
        this.tag = this.getAttribute('tag');
        this.id = this.getAttribute('id');

        let removeBlockElement = this.generateDeleteBlock(this.tag, this.id);
        this.appendChild(removeBlockElement);
    }

    generateDeleteBlock(tag, id) {
        let containerElement = document.createElement('div');
        containerElement.classList.add('container-block');
        containerElement.innerHTML = this.LOADING_MSG;
    
        let token = getToken();

        var urlTag = this.URL_TAGS[tag].get;

        requestAPI(urlTag, { token, id }, function(response) {
            containerElement.innerHTML = '';
    
            let titleElement = this.generateTitle(tag, response[tag]);
            containerElement.appendChild(titleElement);
    
            let submitElement = this.generateSubmit(tag, response.drawer);
            containerElement.appendChild(submitElement);
        }.bind(this));
    
        return containerElement;
    }

    generateTitle(tag, element) {
        let titleElement = document.createElement('div');
        titleElement.classList.add('block-title');

        let getTitleFn = this.GET_TITLE_FN[tag]

        let titleElementText = document.createElement('span');
        titleElementText.textContent = getTitleFn(element);
        titleElement.appendChild(titleElementText);

        return titleElement;
    }

    generateSubmit(tag, element) {
        let submitElement = document.createElement('div');
        submitElement.classList.add('delete-block-submit');

        let cancelButton = document.createElement('input');
        cancelButton.setAttribute('type', 'button');
        cancelButton.setAttribute('value', 'Cancelar');
        cancelButton.classList.add('delete-block-submit-cancel')

        let back_url_fn = this.URL_TAGS[tag].back

        cancelButton.addEventListener('click', function () {
            window.location.href = back_url_fn(element);
        });
        submitElement.appendChild(cancelButton);

        let addButton = document.createElement('input');
        addButton.setAttribute('type', 'submit');
        addButton.setAttribute('value', 'Deletar');
        addButton.classList.add('delete-block-submit-delete');
        addButton.addEventListener('click', this.requestRemoveElement.bind(this));
        submitElement.appendChild(addButton);

        return submitElement;
    }

    requestRemoveElement() {
        let params = { token: getToken(), id: this.id };
        var urlTag = this.URL_TAGS[this.tag];

        requestAPI(urlTag.delete, params, function (response) {
            window.location.href = urlTag.list;
        })
    }
}

window.customElements.define('delete-block', DeleteBLockElement)
