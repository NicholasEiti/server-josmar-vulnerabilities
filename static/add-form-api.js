class FormAddBlockElement extends HTMLElement {
    LOADING_MSG = 'Carregando...'

    GET_TITLE = {
        drawer: 'Criar novo armário'
    }

    INPUTS_INFOS = {
        drawer: [{
            id: 'name',
            label: 'Nome:',
            type: 'string',
            min_length: 5,
            max_length: 50,
            get: function (set) {
                let inputElement = this.querySelector('#name');

                if (inputElement.value == '') return false;

                set('name', inputElement.value)
            }
        }],
        key: [{
            id: 'name',
            label: 'Nome:',
            type: 'string',
            min_length: 5,
            max_length: 50,
            get: function (set) {
                let inputElement = this.querySelector('#name');
                set('name', inputElement.value)
            }
        },  {
            id: 'drawer',
            label: 'Armário:',
            type: 'instance_select',
            get: function (set) {
                let selectElement = this.querySelector('#drawer');
                let value = selectElement.options[selectElement.selectedIndex].value;
                
                if (value == '') return false;

                set('drawer', value);
            },
            get_list(return_list) {
                requestAPI('drawer_list', { token: getToken() }, function (response) {
                    return_list(response.list.map(function (element) {
                        return { id: element.id, value: element.name };
                    }));
                });
            }
        }]
    }

    URL_TAGS = {
        drawer: 'drawer_add',
        key: 'key_add'
    }

    constructor(...args) {
        super(...args);

        this.get_input_value_fns = [];
    }

    connectedCallback() {
        this.tag = this.getAttribute('tag');

        let titleElement = this.generateTitle(this.tag);
        this.appendChild(titleElement);

        let contentElement = this.generateContent(this.tag);
        this.appendChild(contentElement);

        let submitElement = this.generateSubmit(this.tag);
        this.appendChild(submitElement);
    }

    generateTitle(tag) {
        let titleElement = document.createElement('div');
        titleElement.classList.add('block-title');

        let titleElementText = document.createElement('span');
        titleElementText.textContent = this.GET_TITLE[tag];
        titleElement.appendChild(titleElementText);

        return titleElement;
    }

    generateContent() {
        let contentElement = document.createElement('div');
        contentElement.classList.add('add-form-block-content');

        let inputsInfo = this.INPUTS_INFOS[this.tag];

        inputsInfo.forEach(function (inputInfo) {
            let inputElement = this.generateInput(inputInfo);

            this.get_input_value_fns.push(inputInfo.get.bind(inputElement));

            contentElement.appendChild(inputElement);
        }.bind(this));

        return contentElement;
    }

    generateSubmit() {
        let submitElement = document.createElement('div');
        submitElement.classList.add('add-form-block-submit');

        let cancelButton = document.createElement('input');
        cancelButton.setAttribute('type', 'button');
        cancelButton.setAttribute('value', 'Cancelar');
        cancelButton.classList.add('add-form-block-submit-cancel')
        cancelButton.addEventListener('click', function () {
            window.location.href = "/drawers/";
        });
        submitElement.appendChild(cancelButton);

        let addButton = document.createElement('input');
        addButton.setAttribute('type', 'submit');
        addButton.setAttribute('value', 'Adicionar');
        addButton.classList.add('add-form-block-submit-add');
        addButton.addEventListener('click', this.requestAddElement.bind(this));
        submitElement.appendChild(addButton);

        return submitElement;
    }

    generateInput(inputInfo) {
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
            
            inputElement.setAttribute('required', true);

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

            contentInputElement.appendChild(inputElement);
        } else if (inputInfo.type == 'instance_select') {
            let selectElement = document.createElement('select');

            selectElement.setAttribute('required', true);

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

    requestAddElement() {
        let params = { token: getToken() };

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

        var urlTag = this.URL_TAGS[this.tag];

        requestAPI(urlTag, params, function (response) {
            window.location.href = "/drawers/" + response.id;
        })
    }
}

window.customElements.define('form-add-block', FormAddBlockElement)
