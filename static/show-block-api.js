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

        requestAPI(urlTag.get, { token, id }, (response) => {
            let element = response[tag];

            containerElement.innerHTML = '';

            let titleElement = this.generateTitle(tag, element);
            containerElement.appendChild(titleElement);

            let item = generateItem(element);
            containerElement.appendChild(item);
        });

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
        editLink.setAttribute("href", urlTag.edit_url(element));
        editLink.appendChild(generateIcon('edit', 'show-block-icon'))
        titleIcons.appendChild(editLink);

        if (typeof urlTag.delete_url === 'undefined') {
            let deleteLink = document.createElement('a');
            deleteLink.setAttribute("href", urlTag.delete_url(element));
            deleteLink.appendChild(generateIcon('delete', 'show-block-icon'));
            titleIcons.appendChild(deleteLink);
        }
    

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
    request.date_expected_start = new Date(request.date_expected_start).toLocaleString("pt-br")
    request.date_expected_end = new Date(request.date_expected_end).toLocaleString("pt-br")

    let showElement = document.createElement('div');
    showElement.classList.add('show-block');

    let showElementText = document.createElement('div');
    showElementText.classList.add('show-block-text');

    let showElementText1 = document.createElement('span');
    showElementText1.classList.add('show-block-text-title');
    showElementText1.textContent = `Pedido da chave ${request.key_name} de ${request.user_name}`;
    showElementText.appendChild(showElementText1);

    let showElementText2 = document.createElement('span');
    showElementText2.textContent = `Pedido foi programado para ${request.date_expected_start} e ${request.date_expected_end}`;
    showElementText.appendChild(showElementText2);

    if (request.date_start !== null) {
        request.date_start = new Date(request.date_start).toLocaleString("pt-br")

        let showElementText3 = document.createElement('span');
        let textContent = `Usuário pegou a chave ás ${request.date_start}`;
        
        if (request.date_end !== null) {
            request.date_end = new Date(request.date_end).toLocaleString("pt-br")

            textContent += ` e devolveu ás ${request.date_end}`
        }

        showElementText3.textContent = textContent;
        showElementText.appendChild(showElementText3);
    }

    let showElementText4 = document.createElement('span');
    showElementText4.textContent = `Situação do pedido: ${API_REQUEST_STATUS[request.status]}`;
    showElementText.appendChild(showElementText4);

    showElement.appendChild(showElementText); 

    return showElement;
}

window.customElements.define('show-block', ShowBlockElement)
