class ListBlockElement extends HTMLElement {
    LOADING_MSG = 'Carregando...'

    constructor(...args) { super(...args); }

    generateForm(tag) {}

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

    generateTitle(tag, count, params) {
        let titleElement = document.createElement('div');
        titleElement.classList.add('block-title');

        let getTitle = this.GET_TITLE_FN[tag]

        let titleElementText = document.createElement('span');
        titleElementText.textContent = getTitle(count, params);
        titleElement.appendChild(titleElementText);

        return titleElement;
    }

    connectedCallback() {
        this.tag = this.getAttribute('tag');
        let listElement = this.generateForm(this.tag);
        this.appendChild(listElement);
    }
}