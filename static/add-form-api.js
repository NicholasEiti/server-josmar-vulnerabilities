class FormAddBlockElement extends HTMLElement {
    LOADING_MSG = 'Carregando...'

    GET_TITLE = {
        drawer: 'Criar novo armário',
        key: 'Criar nova chave',
        user: 'Criar novo usuário',
        request: 'Criar novo pedido de chave'
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

                if (inputElement.value == '') return error('Nome vazio. Escolha um nome e tente novamente.');
                if (value.length <= 5) return error('Nome muito pequeno, o nome deve tem pelo menos 5 caracteres.');
                if (value.length >= 50) return error('Nome muito longo, o nome deve tem menos de 50 caracteres.');

                set('name', inputElement.value)
            }
        }],
        key: [{
            id: 'name',
            label: 'Nome:',
            type: 'string',
            min_length: 5,
            max_length: 10,
            get: function (set, error) {
                let value = this.querySelector('#name').value;

                if (value == '') return error('Nome vazio. Escolha um nome e tente novamente.');
                if (value.length <= 5) return error('Nome muito pequeno, o nome deve tem pelo menos 5 caracteres.');
                if (value.length >= 10) return error('Nome muito longo, o nome deve tem menos de 10 caracteres.');

                set('name', value)
            }
        },  {
            id: 'drawer',
            label: 'Armário:',
            type: 'instance_select',
            get: function (set, error) {
                let selectElement = this.querySelector('#drawer');
                let value = Number(selectElement.options[selectElement.selectedIndex].value);

                if (value == '') return error('Armário não selecionado. Tente novamente.');

                set('drawer', value);
            },
            get_list(return_list) {
                requestAPI('drawer_list', { token: getToken() }, function (response) {
                    return_list(response.list.map(function (element) {
                        return { id: element.id, value: element.name };
                    }));
                });
            }
        }],
        user: [{
            id: 'name',
            label: 'Nome:',
            type: 'string',
            min_length: 5,
            max_length: 20,
            get: function (set, error) {
                let value = this.querySelector('#name').value;

                if (value == '') return error('Nome vazio. Escolha um nome e tente novamente.');
                if (value.length <= 5) return error('Nome muito pequeno, o nome deve tem pelo menos 5 caracteres.');
                if (value.length >= 20) return error('Nome muito longo, o nome deve tem menos de 20 caracteres.');

                set('name', value)
            }
        }, {
            id: 'email',
            label: 'Email de cadastro:',
            type: 'email',
            get: function (set, error) {
                let value = this.querySelector('#email').value;

                if (value == '') return error('Email vazio. Escolha um email e tente novamente.');
                if (!value.match(EMAIL_PATTERN)) return error('Formatação invalida de email. Tente novamente.');

                set('email', value)
            }
        }, {
            id: 'password',
            label: 'Senha:',
            type: 'password',
            min_length: 5,
            max_length: 20,
            get: function (set, error) {
                let value = this.querySelector('#password').value;

                if (value == '') return error('Senha vazia. Escolha um senha e tente novamente.');
                if (value.length <= 5) return error('Senha muito pequeno.');
                if (value.length >= 20) return error('Senha muito longa.');

                set('password', value)
            }
        }, {
            id: 'level',
            label: 'Nível na hierarquia:',
            type: 'select',
            list: {inactive: 'Inativo', collaborator: 'Colaborador', admin: 'Administrador'},
            get: function (set, error) {
                let selectElement = this.querySelector('#level');
                let value = selectElement.options[selectElement.selectedIndex].value;

                if (value == '') return error('Nível na hierarquia não selecionado. Tente novamente.');

                set('level', value);
            }
        }],
        request: [{
            id: 'key',
            label: 'Chave:',
            type: 'instance_select',
            get: function (set, error) {
                let selectElement = this.querySelector('#key');
                let value = Number(selectElement.options[selectElement.selectedIndex].value);

                if (value == '') return error('Chave não selecionado. Tente novamente.');

                set('key', value);
            },
            get_list(return_list) {
                requestAPI('key_list', { token: getToken() }, function (response) {
                    return_list(response.list.map(function (element) {
                        return { id: element.id, value: element.name };
                    }));
                });
            }
        }, {
            id: 'date_start',
            label: 'Data inicial:',
            type: 'date_time',
            get: function(set, error) {
                let value = this.querySelector('#date_start').value;

                if (value == '') return error('Data inicial vazia. Escolha um data inicial e tente novamente.');

                var date = Date.parse(value);

                if (isNaN(date)) return error('Data inicial com formatação inesperada. Tente novamente.');

                date = new Date(date);

                let date_start = date.getFullYear() + "-" +
                    ("00" + (date.getMonth() + 1)).slice(-2) + "-" +
                    ("00" + date.getDate()).slice(-2) + " " +
                    ("00" + date.getHours()).slice(-2) + ":" +
                    ("00" + date.getMinutes()).slice(-2) + ":" +
                    ("00" + date.getSeconds()).slice(-2);

                return set('date_start', date_start)
            }
        }, {
            id: 'date_end',
            label: 'Data final:',
            type: 'date_time',
            get: function(set, error) {
                let value = this.querySelector('#date_end').value;

                if (value == '') return error('Data final vazia. Escolha um data final e tente novamente.');

                var date = Date.parse(value);

                if (isNaN(date)) return error('Data final com formatação inesperada. Tente novamente.');

                date = new Date(date);

                let date_end = date.getFullYear() + "-" +
                    ("00" + (date.getMonth() + 1)).slice(-2) + "-" +
                    ("00" + date.getDate()).slice(-2) + " " +
                    ("00" + date.getHours()).slice(-2) + ":" +
                    ("00" + date.getMinutes()).slice(-2) + ":" +
                    ("00" + date.getSeconds()).slice(-2);

                return set('date_end', date_end)
            }
        }]
    }

    URL_TAGS = {
        drawer: {
            add: 'drawer_add',
            list_url: '/drawers/',
            get_url: (response) => '/drawers/' + response.id
        },
        key: {
            add: 'key_add',
            list_url: '/keys/',
            get_url: () => '/keys/'
        },
        user: {
            add: 'user_add',
            list_url: '/users/',
            get_url: () => '/users/'
        },
        request: {
            add: 'request_add',
            list_url: '/requests/',
            get_url: (response) => '/requests/' + response.id
        }
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

    generateSubmit(tag) {
        let submitElement = document.createElement('div');
        submitElement.classList.add('add-form-block-submit');

        let cancelButton = document.createElement('input');
        cancelButton.setAttribute('type', 'button');
        cancelButton.setAttribute('value', 'Cancelar');
        cancelButton.classList.add('add-form-block-submit-cancel')

        var urlTag = this.URL_TAGS[tag];

        cancelButton.addEventListener('click', function () {
            window.location.href = urlTag.list_url;
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

        if (inputInfo.label !== undefined) {
            let labelElement = document.createElement('label');
            labelElement.classList.add('input-block-label');
            labelElement.textContent = inputInfo.label;
            labelElement.setAttribute('for', inputInfo.id);
            contentInputElement.appendChild(labelElement);
        }

        if (inputInfo.type == 'string' || inputInfo.type == 'email' || inputInfo.type == 'password' || inputInfo.type == 'date_time') {
            let inputElement = document.createElement('input');
            inputElement.classList.add('input-block-input');

            if (inputInfo.type == 'email' || inputInfo.type == 'password')
                inputElement.setAttribute('type', inputInfo.type)
            else if (inputInfo.type == 'date_time') {
                inputElement.setAttribute('type', 'datetime-local')
            }

            if (inputInfo.min_length !== undefined) {
                inputElement.setAttribute('minlength', inputInfo.min_length)
            }

            if (inputInfo.max_length !== undefined) {
                inputElement.setAttribute('minlength', inputInfo.max_length)
            }

            inputElement.setAttribute('id', inputInfo.id);

            if (inputInfo.label !== undefined) {
                inputElement.setAttribute('name', inputInfo.id);
            }

            inputElement.addEventListener('keydown', function (e) {
                if (e.key == 'Enter') {
                    this.requestAddElement()
                }
            }.bind(this))

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
        } else if (inputInfo.type == 'select') {
            let selectElement = document.createElement('select');
            selectElement.classList.add('input-block-select');

            selectElement.setAttribute('id', inputInfo.id);

            Object.entries(inputInfo.list).forEach(element => {
                let [id, value] = element;
                  
                let optionElement = document.createElement("option");

                optionElement.value = id;
                optionElement.text = value;

                selectElement.add(optionElement);
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

        for (const fn of this.get_input_value_fns) {
            fn(setFn, errorFn);

            if (hasError) break;
        }

        if (!hasError) {
            var urlTag = this.URL_TAGS[this.tag];

            requestAPI(urlTag.add, params, function (response) {
                window.location.href = urlTag.get_url(response);
            })
        }
    }
}

window.customElements.define('form-add-block', FormAddBlockElement)
