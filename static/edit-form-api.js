class FormEditBlockElement extends HTMLElement {
    LOADING_MSG = 'Carregando...'

    GET_TITLE_FN = {
        drawer: (drawer) => `Editar armário ${drawer.name}`,
        user: (user) => `Editar usuário ${user.name}`,
        key: (key) => `Editar chave ${key.name}`,
        request: (request) => `Editar situação do pedido de chave de ${request.user_name}`
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

                if (value == '') return error('Nome vazio. Escolha um nome e tente novamente.');
                if (value.length <= 5) return error('Nome muito pequeno, o nome deve tem pelo menos 5 caracteres.');
                if (value.length > 50) return error('Nome muito longo, o nome deve tem menos de 50 caracteres.');

                set('name', value);
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
                if (value.length > 20) return error('Nome muito longo, o nome deve tem menos de 20 caracteres.');

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

                if (value == '') return;
                if (value.length <= 5) return error('Senha muito pequeno.');
                if (value.length > 20) return error('Senha muito longa.');

                set('password', value);
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
        }, {
            id: 'expiretime',
            label: 'Tempo de duração do login do usuário (em minutos):',
            type: 'number',
            min_value: 5,
            max_value: 10080, // 7 * 24 * 60 - 7 dias
            get: function (set, error) {
                let value = this.querySelector('#expiretime').value;

                if (value == '') return set('expiretime', 0);

                try {
                    value = Number(value);
                } catch {
                    return error('Tempo de duração do login em formato inesperado. Tente novamente.');
                }

                if (value <= 5 || value > 10080)
                    return error('Tempo de duração do login em invalido, valor deve ser entre 5 minutos e 1 semana.');

                set('expiretime', value);
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
                if (value.length > 10) return error('Nome muito longo, o nome deve tem menos de 10 caracteres.');

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
        },  {
            id: 'position',
            label: 'Posição da chave:',
            type: 'number',
            min_value: 1,
            max_value: 72,
            get: function (set, error) {
                let value = this.querySelector('#position').value;

                if (value != '') {
                    try {
                        value = Number(value);
                    } catch {
                        error('Valor da posição em formato inesperado. Tente novamente.');
                    }

                    set('position', value);
                }
            }
        }],
        request: [{
            id: 'status',
            label: 'Situação do pedido:',
            type: 'select',
            list: (value) => {
                let available_paths = {
                    'not_started': {
                        'not_started': '-',
                        'canceled': 'Cancelar pedido',
                        'start_request': 'Pedir chave '
                    },
                    'start_request': {
                        'start_request': '-',
                        'canceled': 'Cancelar pedido',
                        'started': 'Pegar chave'
                    },
                    'started': {
                        'started': '-',
                        'end_request': 'Pedir entrega da chave'
                    },
                    'end_request': {
                        'end_request': '-',
                        'started': 'Ficar a chave',
                        'ended': 'Devolver chave'
                    }
                };

                return available_paths[value];
            },
            get: function (set, error) {
                let selectElement = this.querySelector('#status');
                let value = selectElement.options[selectElement.selectedIndex].value;
                
                if (value == '') return error('Situação do pedido não selecionado. Tente novamente.');

                set('status', value);
            }
        }]
    }

    URL_TAGS = {
        drawer: {
            edit: 'drawer_edit',
            get: 'drawer_get',
            back_url: (id) => '/drawers/' + id
        },
        user: {
            edit: 'user_edit',
            get: 'user_get',
            back_url: () => '/users/'
        },
        key: {
            edit: 'key_edit',
            get: 'key_get',
            back_url: () => '/keys/'
        },
        request: {
            edit: 'request_update_status',
            get: 'request_get',
            back_url: (id) => '/requests/' + id
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

        requestAPI(urlTag.get, { token, id }, (response) => {
            let element = response[tag];
            containerElement.innerHTML = '';

            let titleElement = this.generateTitle(tag, element);
            containerElement.appendChild(titleElement);

            let contentElement = this.generateContent(tag, element);
            containerElement.appendChild(contentElement);

            let submitElement = this.generateSubmit(tag, id);
            containerElement.appendChild(submitElement);
        });

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

        inputsInfo.forEach((inputInfo) => {
            let inputElement = this.generateInput(inputInfo, element[inputInfo.id]);

            this.get_input_value_fns.push(inputInfo.get.bind(inputElement));

            contentElement.appendChild(inputElement);
        });

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
            window.location.href = urlTag.back_url(id);
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

        if (inputInfo.type == 'string' || inputInfo.type == 'email' || inputInfo.type == 'password' || inputInfo.type == 'number') {
            let inputElement = document.createElement('input');
            inputElement.classList.add('input-block-input');

            if (inputInfo.type == 'email' || inputInfo.type == 'password') {
                inputElement.setAttribute('type', inputInfo.type)
            }
 
            if (inputInfo.type == 'number') {
                inputElement.setAttribute('type', inputInfo.type)

                if (inputInfo.min_value !== null) {
                    inputElement.setAttribute('min', inputInfo.min_value)
                }

                if (inputInfo.max_value !== null) {
                    inputElement.setAttribute('max', inputInfo.max_value)
                }
            } else {
                if (inputInfo.min_length !== null) {
                    inputElement.setAttribute('minlength', inputInfo.min_length)
                }
    
                if (inputInfo.max_length !== null) {
                    inputElement.setAttribute('maxlength', inputInfo.max_length)
                }
            }


            inputElement.setAttribute('id', inputInfo.id);

            if (inputInfo.label !== null) {
                inputElement.setAttribute('name', inputInfo.id);
            }

            inputElement.addEventListener('keydown', (e) => {
                if (e.key == 'Enter') {
                    this.requestEditElement()
                }
            })

            if (inputInfo.type != 'password') {
                inputElement.value = value;
            }

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

            selectElement.value = value;

            contentInputElement.appendChild(selectElement);
        } else if (inputInfo.type == 'select') {
            let selectElement = document.createElement('select');
            selectElement.classList.add('input-block-select');

            selectElement.setAttribute('id', inputInfo.id);

            if(typeof inputInfo.list === "function")
                inputInfo.list = inputInfo.list(value);

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

            selectElement.value = value;

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

            requestAPI(urlTag.edit, params, () => {
                window.location.href = urlTag.back_url(this.id);
            });
        }
    }

    static get observedAttributes() { return ['tag', 'id']; }
}

window.customElements.define('form-edit-block', FormEditBlockElement)
