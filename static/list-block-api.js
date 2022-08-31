class ListBlockElement extends HTMLElement {
    LOADING_MSG = 'Carregando...'

    GENERATE_ITEM_FUNCS = {
        drawer: generateDrawerItem,
        key: generateKeyItem,
        request: generateRequestItem
    }

    URL_TAGS = {
        drawer: 'drawer_list',
        key: 'key_list',
        request: 'request_list'
    }

    NO_RESULT_MSG = {
        drawer: 'Não há armários cadastrados',
        key: 'Não há chaves cadastrados',
        request: 'Não há pedidos cadastrados'
    }

    DEFAULT_QUANT = 10
    DEFAULT_PAGE = 1

    constructor(...args) { super(...args); }

    generateList(tag) {
        let listElement = document.createElement('div')
        listElement.classList.add('list-block');
        let token = getToken();

        listElement.innerHTML = this.LOADING_MSG

        var generateItem = this.GENERATE_ITEM_FUNCS[tag];
        var urlTag = this.URL_TAGS[tag];

        requestAPI(urlTag, {
            token,
            page: this.page,
            quant: this.quant
        }, function(response) {
            listElement.innerHTML = '';

            if (response.list == false) {
                let item = this.generateNoResult(tag);

                if (item !== undefined) {
                    listElement.appendChild(item);
                }
            } else {
                for (const row of response.list) {
                    let item = generateItem(row);
    
                    if (item !== undefined) {
                        listElement.appendChild(item)
                    }
                }
            }
        });

        return listElement;
    }

    generateNoResult(tag) {
        let item = document.createElement('div');
    
        item.classList.add('item-list');

        let noResultMsg = NO_RESULT_MSG[tag];

        let itemText = document.createElement('p');
        itemText.classList.add('item-list-text');
        itemText.textContent = noResultMsg;
        item.appendChild(itemText);
    
        return item;
    }

    connectedCallback() {
        this.tag = this.getAttribute('tag');
        this.quant = Number(this.getAttribute('quant')) || this.DEFAULT_QUANT;
        this.page = Number(this.getAttribute('page')) || this.DEFAULT_PAGE;

        let listElement = this.generateList(this.tag, this.quant, this.page);

        this.appendChild(listElement);
    }

    // attributeChangedCallback(name, oldValue, newValue) {}

    static get observedAttributes() { return ['tag', 'quant', 'page']; }
}


function generateDrawerItem(drawer) {
    let item = document.createElement('div');
    item.classList.add('item-list');

    let itemText = document.createElement('p');
    itemText.classList.add('item-list-text');
    itemText.textContent = drawer.name;
    item.appendChild(itemText);

    let itemIcons = document.createElement('div')
    itemIcons.classList.add('item-list-icons')

    let editLink = document.createElement('a');
    editLink.setAttribute("href", "/drawers/" + drawer.id + "/edit");
    editLink.appendChild(generateIcon('edit', 'item-list-icon'))
    itemIcons.appendChild(editLink);

    let deleteLink = document.createElement('a');
    deleteLink.setAttribute("href", "/drawers/" + drawer.id + "/delete");
    deleteLink.appendChild(generateIcon('delete', 'item-list-icon'));
    itemIcons.appendChild(deleteLink);

    item.appendChild(itemIcons);

    return item;
}


function generateKeyItem(key) {
    let item = document.createElement('div');
    item.classList.add('item-list');

    let itemText = document.createElement('p');
    itemText.classList.add('item-list-text');
    itemText.textContent = key.name;
    item.appendChild(itemText);

    let itemIcons = document.createElement('div');
    itemIcons.classList.add('item-list-icons');

    let editLink = document.createElement('a');
    editLink.setAttribute("href", "/keys/" + key.id + "/edit");
    editLink.appendChild(generateIcon('edit', 'item-list-icon'));
    itemIcons.appendChild(editLink);

    let deleteLink = document.createElement('a');
    deleteLink.setAttribute("href", "/keys/" + key.id + "/delete");
    deleteLink.appendChild(generateIcon('delete', 'item-list-icon'));
    itemIcons.appendChild(deleteLink);

    item.appendChild(itemIcons);

    return item;
}

function generateRequestItem(request) {
    let item = document.createElement('div');
    item.classList.add('item-list');

    let itemText = document.createElement('p');
    itemText.classList.add('item-list-text');
    itemText.textContent = `User id: ${request.user}, Key id: ${request.key}, Status: ${request.status}, Date expected start: ${request.date_expected_start}, Date expected end: ${request.date_expected_end} `;
    item.appendChild(itemText);

    let itemIcons = document.createElement('div')
    itemIcons.classList.add('item-list-icons')

    let editLink = document.createElement('a');
    editLink.setAttribute("href", "/requests/" + request.id + "/edit");
    editLink.appendChild(generateIcon('edit', 'item-list-icon'))
    itemIcons.appendChild(editLink);

    let deleteLink = document.createElement('a');
    deleteLink.setAttribute("href", "/requests/" + request.id + "/delete");
    deleteLink.appendChild(generateIcon('delete', 'item-list-icon'));
    itemIcons.appendChild(deleteLink);

    item.appendChild(itemIcons);

    return item;
}


window.customElements.define('list-block', ListBlockElement)
