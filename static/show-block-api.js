class ShowBlockElement extends HTMLElement {
    LOADING_MSG = 'Carregando...'

    GENERATE_ITEM_FUNCS = {
        drawer: drawShowDrawer
    }

    URL_TAGS = {
        drawer: 'drawer_get'
    }

    getTitle = {
        drawer: (drawer) => `Armário ${drawer.name}`,
    }

    constructor(...args) { super(...args); }

    generateShow(tag, id) {
        let containerElement = document.createElement('div');
        containerElement.classList.add('container-block');
        containerElement.innerHTML = this.LOADING_MSG;

        let token = getToken();

        var generateItem = this.GENERATE_ITEM_FUNCS[tag];
        var urlTag = this.URL_TAGS[tag];
        let getTitle = this.getTitle[tag]

        requestAPI(urlTag, {
            token,
            id: id
        }, function(response) {
            containerElement.innerHTML = '';

            let titleElement = this.generateTitle(getTitle, response.drawer);
            containerElement.appendChild(titleElement);

            let item = generateItem(response.drawer);
            containerElement.appendChild(item);
        }.bind(this));

        return containerElement;
    }

    generateTitle(getTitle, drawer) {
        let titleContent = document.createElement('div');
        titleContent.classList.add('block-title');

        let titleElement = document.createElement('div');
        titleElement.classList.add('block-title-element');
        titleElement.textContent = getTitle(drawer);
        titleContent.appendChild(titleElement);

        let titleIcons = document.createElement('div');
        titleIcons.classList.add('block-title-icons');
    
        let editLink = document.createElement('a');
        editLink.setAttribute("href", "/drawers/" + drawer.id + "/edit");
        editLink.appendChild(generateIcon('edit', 'show-block-icon'))
        titleIcons.appendChild(editLink);
    
        let deleteLink = document.createElement('a');
        deleteLink.setAttribute("href", "/drawers/" + drawer.id + "/delete");
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

    // attributeChangedCallback(name, oldValue, newValue) {}

    static get observedAttributes() { return ['tag', 'quant', 'page']; }
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

window.customElements.define('show-block', ShowBlockElement)
