class ListBlockElement extends HTMLElement {
    LOADING_MSG = 'Carregando...'

    GENERATE_ITEM_FUNCS = {
        drawer: generateDrawerItem,
        key: generateKeyItem,
        request: generateRequestItem,
        user: generateUserItem
    }

    URL_TAGS = {
        drawer: 'drawer_list',
        key: 'key_list',
        request: 'request_list',
        user: 'user_list'
    }

    NO_RESULT_MSG = {
        drawer: 'Não há armários cadastrados',
        key: 'Não há chaves cadastrados',
        request: 'Não há pedidos cadastrados',
        user:  'Não há usuários cadastrados'
    }

    GET_TITLE_FN = {
        drawer: (count) => `Foram encontrados ${count} armário` + (count != 1 ? 's' : ''),
        key: (count) => `Foram encontrados ${count} chave` + (count != 1 ? 's' : ''),
        request: (count) => `Foram encontrados ${count} pedido` + (count != 1 ? 's' : ''),
        user: (count) => `Foram encontrados ${count} usuários` + (count != 1 ? 's' : '')
    }

    TITLE_ICONS = {
        drawer: [{
            link: "/drawers/add",
            icon: "add_box"
        }],
        key: [{
            link: "/keys/add",
            icon: "add"
        }],
        request: [{
            link: "/requests/add",
            icon: "bookmark_add"
        }],
        user: [{
            link: "/users/add",
            icon: "person_add_alt"
        }]
    };

    DEFAULT_QUANT = 10
    DEFAULT_PAGE = 1

    constructor(...args) { super(...args); }

    generateList(tag) {
        let containerElement = document.createElement('div');
        containerElement.classList.add('container-block');

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

            let titleElement = this.generateTitle(tag, response.count);
            containerElement.appendChild(titleElement);

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

            containerElement.appendChild(listElement)
        }.bind(this));

        return containerElement;
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

    generateTitle(tag, count) {
        let titleElement = document.createElement('div');
        titleElement.classList.add('block-title');

        let getTitle = this.GET_TITLE_FN[tag]

        let titleElementText = document.createElement('span');
        titleElementText.textContent = getTitle(count);
        titleElement.appendChild(titleElementText);

        let titleElementIcons = document.createElement('div');

        let iconsInfos = this.TITLE_ICONS[tag];

        iconsInfos.forEach((iconsInfo) => {
            let iconLink = document.createElement('a');
            iconLink.setAttribute("href", iconsInfo.link);
            iconLink.appendChild(generateIcon(iconsInfo.icon, 'item-list-icon'));
            titleElementIcons.appendChild(iconLink);
        })

        titleElement.appendChild(titleElementIcons);

        return titleElement;
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
    request.date_expected_start = new Date(request.date_expected_start).toLocaleString("pt-br")
    request.date_expected_end = new Date(request.date_expected_end).toLocaleString("pt-br")

    let item = document.createElement('div');
    item.classList.add('item-list');

    let itemText = document.createElement('div');
    itemText.classList.add('item-list-text');

    let itemText1 = document.createElement('span');
    let itemText2 = document.createElement('span');
    let itemText3 = document.createElement('span');

    itemText1.classList.add('item-list-text-title');

    itemText1.textContent = `Pedido da chave ${request.key_name} de ${request.user_name}`;
    itemText2.textContent = `Entre ${request.date_expected_start} e ${request.date_expected_end}`;
    itemText3.textContent = `Situação: ${API_REQUEST_STATUS[request.status]}`;

    itemText.append(itemText1, itemText2, itemText3);
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

function generateUserItem(user) {
    console.log(user);
    let item = document.createElement('div');
    item.classList.add('item-list');

    let itemText = document.createElement('p');
    itemText.classList.add('item-list-text');
    itemText.textContent = user.name;

    item.appendChild(itemText);

    let itemIcons = document.createElement('div');
    itemIcons.classList.add('item-list-icons');

    let editLink = document.createElement('a');
    editLink.setAttribute("href", "/users/" + user.id + "/edit");
    editLink.appendChild(generateIcon('edit', 'item-list-icon'));
    itemIcons.appendChild(editLink);

    let deleteLink = document.createElement('a');
    deleteLink.setAttribute("href", "/users/" + user.id + "/delete");
    deleteLink.appendChild(generateIcon('delete', 'item-list-icon'));
    itemIcons.appendChild(deleteLink);

    item.appendChild(itemIcons);

    return item;
}

window.customElements.define('list-block', ListBlockElement)
