class FormEditBlockElement extends HTMLElement {
    LOADING_MSG = 'Carregando...'

    GET_TITLE_FN = {
        drawer: (drawer) => `Editar armário ${drawer.name}`,
        key: (key) => `Editar chave ${key.name}`
    }

    INPUTS_INFOS = {
        drawer: [{
            id: 'name',
            label: 'Nome:',
            type: 'string',
            min_length: 5,
            max_length: 50,
            get: function (set) {
                let value = this.querySelector('#name').value;

                if (value == '') error('Nome vazio. Escolha um nome e tente novamente.');
                if (value.length <= 5) error('Nome muito pequeno, o nome deve tem pelo menos 5 caracteres.');
                if (value.length >= 50) error('Nome muito longo, o nome deve tem menos de 50 caracteres.');

                set('name', value);
            }
        }]
    }

    URL_TAGS = {
        drawer: {
            edit: 'drawer_edit',
            get: 'drawer_get',
            back_url: (id) => '/drawers/' + id
        }
    }

    constructor(...args) {
        super(...args);

        this.get_input_value_fns = [];
    }

    connectedCallback() {
        this.tag = this.getAttribute('tag');
        this.id = this.getAttribute('id');

        let formElement = this.generateEditForm(this.tag, this.id);
        this.appendChild(formElement);
    }

    generateEditForm(tag, id) {
        let containerElement = document.createElement('div');
        containerElement.classList.add('container-block');
        containerElement.innerHTML = this.LOADING_MSG;

        let token = getToken();
        var urlTag = this.URL_TAGS[tag];

        requestAPI(urlTag.get, { token, id }, function(response) {
            containerElement.innerHTML = '';

            let titleElement = this.generateTitle(tag, response[tag]);
            containerElement.appendChild(titleElement);

            let contentElement = this.generateContent(tag, response[tag]);
            containerElement.appendChild(contentElement);

            let submitElement = this.generateSubmit(tag, id);
            containerElement.appendChild(submitElement);
        }.bind(this));

        return containerElement;
    }

    generateTitle(tag, element) {
        let titleElement = document.createElement('div');
        titleElement.classList.add('block-title');

        let titleElementText = document.createElement('span');
        titleElementText.textContent = this.GET_TITLE_FN[tag](element);
        titleElement.appendChild(titleElementText);

        return titleElement;
    }

    generateContent(tag, element) {
        let contentElement = document.createElement('div');
        contentElement.classList.add('edit-form-block-content');

        let inputsInfo = this.INPUTS_INFOS[tag];

        inputsInfo.forEach(function (inputInfo) {
            let inputElement = this.generateInput(inputInfo, element[inputInfo.id]);

            this.get_input_value_fns.push(inputInfo.get.bind(inputElement));

            contentElement.appendChild(inputElement);
        }.bind(this));

        return contentElement;
    }

    generateSubmit(tag, id) {
        let submitElement = document.createElement('div');
        submitElement.classList.add('edit-form-block-submit');

        let cancelButton = document.createElement('input');
        cancelButton.setAttribute('type', 'button');
        cancelButton.setAttribute('value', 'Cancelar');
        cancelButton.classList.add('edit-form-block-submit-cancel')

        var urlTag = this.URL_TAGS[tag];

        cancelButton.addEventListener('click', function () {
            window.location.href = urlTag.back(id);
        });
        submitElement.appendChild(cancelButton);

        let editButton = document.createElement('input');
        editButton.setAttribute('type', 'submit');
        editButton.setAttribute('value', 'Alterar');
        editButton.classList.add('edit-form-block-submit-edit');
        editButton.addEventListener('click', this.requestEditElement.bind(this));
        submitElement.appendChild(editButton);
        return submitElement;

    }

    generateInput(inputInfo, value) {
        let contentInputElement = document.createElement('div');
        contentInputElement.classList.add('input-block-element');

        if (inputInfo.label !== null) {
            let labelElement = document.createElement('label');
            labelElement.classList.add('input-block-label');
            labelElement.textContent = inputInfo.label;
            labelElement.setAttribute('for', inputInfo.id);
            contentInputElement.appendChild(labelElement);
        }

        if (inputInfo.type == 'string') {
            let inputElement = document.createElement('input');
            inputElement.classList.add('input-block-input');

            if (inputInfo.min_length !== null) {
                inputElement.setAttribute('minlength', inputInfo.min_length)
            }

            if (inputInfo.max_length !== null) {
                inputElement.setAttribute('minlength', inputInfo.max_length)
            }

            inputElement.setAttribute('id', inputInfo.id);

            if (inputInfo.label !== null) {
                inputElement.setAttribute('name', inputInfo.id);
            }

            inputElement.value = value;

            contentInputElement.appendChild(inputElement);
        } else if (inputInfo.type == 'instance_select') {
            let selectElement = document.createElement('select');
            selectElement.classList.add('input-block-select');

            selectElement.setAttribute('id', inputInfo.id);

            inputInfo.get_list(function(list) {
                list.forEach(function (element) {
                    let optionElement = document.createElement("option");

                    optionElement.value = element.id;
                    optionElement.text = element.value;

                    selectElement.add(optionElement);
                });
            });

            if (inputInfo.label !== null) {
                selectElement.setAttribute('name', inputInfo.id);
            }

            contentInputElement.appendChild(selectElement);
        }

        return contentInputElement;
    }

    requestEditElement() {
        let params = { token: getToken(), id: this.id };

        let setFn = function (key, value) {
            params[key] = value;
        }

        let hasError = false;

        let errorFn = function (msg) {
            hasError = true;
            showNoCodeError(msg);
        };

        this.get_input_value_fns.forEach(function (f) {
            f(setFn, errorFn);
        });

        
        if (!hasError) {
            var urlTag = this.URL_TAGS[this.tag];

            requestAPI(urlTag.edit, params, function () {
                window.location.href = urlTag.back_url(this.id);
            }.bind(this));
        }
    }

    static get observedAttributes() { return ['tag', 'id']; }
}

window.customElements.define('form-edit-block', FormEditBlockElement)
