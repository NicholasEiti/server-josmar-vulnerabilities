class ShowBlockElement extends HTMLElement {
    LOADING_MSG = 'Carregando...'

    GENERATE_ITEM_FUNCS = {
        drawer: drawShowDrawer,
        request: drawShowRequest
    }

    URL_TAGS = {
        drawer: {
            get: 'drawer_get',
            edit_url: (drawer) => '/drawers/' + drawer.id + '/edit',
            delete_url: (drawer) => '/drawers/' + drawer.id + '/delete'
        },
        request: {
            get: 'request_get',
            edit_url: (request) => '/requests/' + request.id + '/edit',
            delete_url: (request) => '/requests/' + request.id + '/delete'
        }
    }

    getTitle = {
        drawer: (drawer) => `Armário ${drawer.name}`,
        request: (request) => `Pedido de chave de ${request.user_name}`
    }

    constructor(...args) { super(...args); }

    generateShow(tag, id) {
        let containerElement = document.createElement('div');
        containerElement.classList.add('container-block');
        containerElement.innerHTML = this.LOADING_MSG;

        let token = getToken();

        var generateItem = this.GENERATE_ITEM_FUNCS[tag];
        var urlTag = this.URL_TAGS[tag];

        requestAPI(urlTag.get, { token, id }, function(response) {
            containerElement.innerHTML = '';

            let titleElement = this.generateTitle(tag, response[tag]);
            containerElement.appendChild(titleElement);

            let item = generateItem(response.drawer);
            containerElement.appendChild(item);
        }.bind(this));

        return containerElement;
    }

    generateTitle(tag, element) {
        let getTitle = this.getTitle[tag];
        var urlTag = this.URL_TAGS[tag];

        let titleContent = document.createElement('div');
        titleContent.classList.add('block-title');

        let titleElement = document.createElement('div');
        titleElement.classList.add('block-title-element');
        titleElement.textContent = getTitle(element);
        titleContent.appendChild(titleElement);

        let titleIcons = document.createElement('div');
        titleIcons.classList.add('block-title-icons');
    
        let editLink = document.createElement('a');
        editLink.setAttribute("href", urlTag.edit_url);
        editLink.appendChild(generateIcon('edit', 'show-block-icon'))
        titleIcons.appendChild(editLink);
    
        let deleteLink = document.createElement('a');
        deleteLink.setAttribute("href", urlTag.delete_url);
        deleteLink.appendChild(generateIcon('delete', 'show-block-icon'));
        titleIcons.appendChild(deleteLink);

        titleContent.appendChild(titleIcons);

        return titleContent
    }

    connectedCallback() {
        this.tag = this.getAttribute('tag');
        this.id = this.getAttribute('id');

        let showElement = this.generateShow(this.tag, this.id);

        this.appendChild(showElement);
    }

    static get observedAttributes() { return ['tag', 'id']; }
}

function drawShowDrawer(drawer) {   
    let showElement = document.createElement('div');

    let keyListElement = document.createElement('list-block')
    keyListElement.classList.add('show-block-list')
    keyListElement.setAttribute('tag', 'key');
    keyListElement.setAttribute('drawer', drawer.id);

    showElement.append(keyListElement)

    return showElement
}

function drawShowRequest(request) {   
    let showElement = document.createElement('div');

    showElement.append(keyListElement);

    return showElement
}

window.customElements.define('show-block', ShowBlockElement)
