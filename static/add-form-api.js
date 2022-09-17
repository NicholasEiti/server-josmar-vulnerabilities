class FormAddBlockElement extends HTMLElement {
    LOADING_MSG = 'Carregando...'


    constructor(...args) { super(...args); }

    connectedCallback() {
        this.tag = this.getAttribute('tag');
        // let listElement = this.generateForm(this.tag);
        // this.appendChild(listElement);
    }
}

window.customElements.define('form-add-block', FormAddBlockElement)
