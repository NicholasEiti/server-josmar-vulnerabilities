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
        key: 'Não há chaves cadastradas',
        request: 'Não há pedidos cadastrados',
        user:  'Não há usuários cadastrados'
    }

    GET_TITLE_FN = {
        drawer: (count) => `Foram encontrados ${count} armário` + (count != 1 ? 's' : ''),
        key: (count, params) => {
            let title = `Foram encontrados ${count} chave` + (count != 1 ? 's' : '')

            if (params.drawer != undefined) {
                title += ' para este armário'
            }

            return title
        },
        request: (count) => `Foram encontrados ${count} pedido` + (count != 1 ? 's' : ''),
        user: (count) => `Foram encontrados ${count} usuário` + (count != 1 ? 's' : '')
    }

    URL_PARAMS = {
        drawer: [],
        key: ['drawer'],
        request: [],
        user: [],
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
        let url_params = this.URL_PARAMS[tag];

        let params = {
            quant: this.quant,
            offset: (this.page - 1) * this.quant
        };

        url_params.forEach(param => {
            let value = this.getAttribute(param)

            if (value != null) {
                params[param] = value
            }
        });

        requestAPI(urlTag, { token, ...params }, (response) => {
            listElement.innerHTML = '';

            let titleElement = this.generateTitle(tag, response.count, params);
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

            containerElement.appendChild(listElement);

            let pageSelectorElement = this.generatePageSelectorElement(response.count);
            containerElement.appendChild(pageSelectorElement);
        });

        return containerElement;
    }

    generateNoResult(tag) {
        let item = document.createElement('div');
    
        item.classList.add('item-list');

        let noResultMsg = this.NO_RESULT_MSG[tag];

        let itemText = document.createElement('p');
        itemText.classList.add('item-list-text');
        itemText.textContent = noResultMsg;
        item.appendChild(itemText);
    
        return item;
    }

    generateTitle(tag, count, params) {
        let titleElement = document.createElement('div');
        titleElement.classList.add('block-title');

        let getTitle = this.GET_TITLE_FN[tag]

        let titleElementText = document.createElement('span');
        titleElementText.textContent = getTitle(count, params);
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
    
    generatePageSelectorElement(count) {
        let pageSelectorElement = document.createElement('div');
        pageSelectorElement.classList.add('block-footer');

        let numPages = Math.floor(count / this.quant) + 1

        for (let i = 1; i <= numPages; i++) {
            let pageElement = document.createElement('div');
            pageElement.classList.add('selector-page');

            if (i == this.page)
                pageElement.classList.add('selected-page')

            pageElement.textContent = i;
            
            pageElement.addEventListener('click', () => {
                window.history.pushState({page: i}, document.title, "?page=" + i);
                this.setAttribute('page', i);
            });

            pageSelectorElement.appendChild(pageElement);
        }

        return pageSelectorElement;
    }

    generate() {
        this.tag = this.getAttribute('tag');
        this.quant = Number(this.getAttribute('quant')) || this.DEFAULT_QUANT;
        this.page = Number(this.getAttribute('page')) || this.DEFAULT_PAGE;

        let listElement = this.generateList(this.tag, this.quant, this.page);

        this.innerHTML = '';
        this.appendChild(listElement);
    }

    connectedCallback() { this.generate(); }
    attributeChangedCallback() { this.generate(); }

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

    item.classList.add('item-list-clickable');
    item.addEventListener('click', function () {
        window.location.href = "/drawers/" + drawer.id
    });

    return item;
}


function generateKeyItem(key) {
    let item = document.createElement('div');
    item.classList.add('item-list');

    let itemText = document.createElement('span');
    itemText.classList.add('item-list-text');
    itemText.textContent = key.name;
    item.appendChild(itemText);

    let itemKV = document.createElement('div');
    itemKV.classList.add('item-list-key-value');

    let itemKey = document.createElement('span');
    itemKey.classList.add('item-list-key');

    itemKey.textContent = 'Armário:'
    itemKV.appendChild(itemKey);
    
    let itemValue = document.createElement('span');
    itemValue.classList.add('item-list-value');

    itemValue.textContent = key.drawer_name;
    itemKV.appendChild(itemValue);

    item.appendChild(itemKV);

    let itemIcons = document.createElement('div');
    itemIcons.classList.add('item-list-icons');

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

    item.appendChild(itemIcons);

    item.classList.add('item-list-clickable');
    item.addEventListener('click', function () {
        window.location.href = "/requests/" + request.id
    });

    return item;
}

function generateUserItem(user) {
    let item = document.createElement('div');
    item.classList.add('item-list');

    let itemText = document.createElement('span');
    itemText.classList.add('item-list-text');
    itemText.textContent = user.name;

    item.appendChild(itemText);

    let itemIcons = document.createElement('div');
    itemIcons.classList.add('item-list-icons');

    let editLink = document.createElement('a');
    editLink.setAttribute("href", "/users/" + user.id + "/edit");
    editLink.appendChild(generateIcon('edit', 'item-list-icon'));
    itemIcons.appendChild(editLink);

    item.appendChild(itemIcons);

    return item;
}

window.customElements.define('list-block', ListBlockElement)
