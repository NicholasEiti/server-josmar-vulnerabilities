class FormBlockElement extends HTMLElement {
    LOADING_MSG = 'Carregando...'

    GET_ADD_TITLE = {
        drawer: 'Criar novo armário',
        user: 'Criar novo usuário',
        key: 'Criar nova chave',
        request: 'Criar novo pedido de chave'
    }

    GET_EDIT_TITLE_FN = {
        drawer: (drawer) => `Editar armário ${drawer.name}`,
        user: (user) => `Editar usuário ${user.name}`,
        key: (key) => `Editar chave ${key.name}`,
        request: (request) => `Editar situação do pedido de chave de ${request.user_name}`,
    }

    INPUTS_INFOS = {
        drawer: [{
            id: 'name',
            label: 'Nome:',
            type: 'string',
            min_length: 5,
            max_length: 30,
            get: function (set, error) {
                let value = this.querySelector('#name').value;

                if (value == '') return error('Nome vazio. Escolha um nome e tente novamente.');
                if (value.length <= 5) return error('Nome muito pequeno, o nome deve tem pelo menos 5 caracteres.');
                if (value.length > 20) return error('Nome muito longo, o nome deve tem menos de 20 caracteres.');

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

                if (value == '') return error('Senha vazia. Escolha um senha e tente novamente.');
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
            min_value: 6,
            max_value: 10080, // 7 * 24 * 60 - 7 dias
            get: function (set, error) {
                let value = this.querySelector('#expiretime').value;

                if (value == '') return set('expire_time', 0);

                try {
                    value = Number(value);
                } catch {
                    return error('Tempo de duração do login em formato inesperado. Tente novamente.');
                }

                if (value <= 5 || value > 10080)
                    return error('Tempo de duração do login em invalido, valor deve ser entre 5 minutos e 1 semana.');

                set('expire_time', value);
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
                        return error('Valor da posição em formato inesperado. Tente novamente.');
                    }

                    set('position', value);
                }
            }
        }],
        request: [{
            id: 'status',
            label: 'Situação do pedido:',
            type: 'select',
            only_if_mode_is: 'edit',
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

                if (available_paths[value] !== undefined) {
                    return available_paths[value];
                }

                return {};
            },
            get: function (set, error) {
                let selectElement = this.querySelector('#status');
                let value = selectElement.options[selectElement.selectedIndex].value;

                if (value == '') return error('Situação do pedido não selecionado. Tente novamente.');

                set('status', value);
            }
        }, {
            id: 'key',
            label: 'Chave:',
            type: 'instance_select',
            only_if_mode_is: 'add',
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
            only_if_mode_is: 'add',
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
            only_if_mode_is: 'add',
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
            edit: 'drawer_edit',
            add: 'drawer_add',
            get: 'drawer_get',
            list_url: '/drawers/',
            back_url: (id) => '/drawers/' + id
        },
        user: {
            edit: 'user_edit',
            add: 'key_add',
            get: 'user_get',
            list_url: '/keys/',
            back_url: () => '/users/'
        },
        key: {
            edit: 'key_edit',
            add: 'user_add',
            get: 'key_get',
            list_url: '/users/',
            back_url: () => '/keys/'
        },
        request: {
            edit: 'request_update_status',
            add: 'request_add',
            get: 'request_get',
            list_url: '/requests/',
            back_url: (id) => '/requests/' + id
        }
    }

    constructor(...args) { super(...args); }

    connectedCallback() {
        this.get_input_value_fns = [];

        this.tag = this.getAttribute('tag');
        this.id = this.getAttribute('id');

        let formElement = this.generateForm(this.tag, this.id);
        this.appendChild(formElement);
    }

    generateForm(tag, id=null) {
        let containerElement = document.createElement('div');
        containerElement.classList.add('container-block');

        let token = getToken();
        var urlTag = this.URL_TAGS[tag];

        if (this.form_type == 'add') {
            let titleElement = this.generateTitle(tag);
            let contentElement = this.generateContent(tag);
            let submitButtonsElement = this.generateSubmitButtons(tag);

            containerElement.innerHTML = '';
            containerElement.appendChild(titleElement);
            containerElement.appendChild(contentElement);
            containerElement.appendChild(submitButtonsElement);
        } else if (this.form_type == 'edit') {
            containerElement.innerHTML = this.LOADING_MSG;

            requestAPI(urlTag.get, { token, id }, (response) => {
                let element = response[tag];

                let titleElement = this.generateTitle(tag, element);
                let contentElement = this.generateContent(tag, element);
                let submitButtonsElement = this.generateSubmitButtons(tag, id);

                containerElement.innerHTML = '';
                containerElement.appendChild(titleElement);
                containerElement.appendChild(contentElement);
                containerElement.appendChild(submitButtonsElement);
            });
        }

        return containerElement;
    }

    generateTitle(tag, element=null) {
        let titleElement = document.createElement('div');
        titleElement.classList.add('block-title');

        let titleElementText = document.createElement('span');
        if (this.form_type == 'add') {
            titleElementText.textContent = this.GET_ADD_TITLE[tag];
        } else if (this.form_type == 'edit') {
            titleElementText.textContent = this.GET_EDIT_TITLE_FN[tag](element);
        }
        titleElement.appendChild(titleElementText);

        return titleElement;
    }

    generateContent(tag, element=null) {
        let contentElement = document.createElement('div');
        contentElement.classList.add('form-block-content');

        let inputsInfo = this.INPUTS_INFOS[tag];

        if (this.form_type == "add") {
            inputsInfo.forEach((inputInfo) => {
                if (inputInfo.only_if_mode_is !== undefined && inputInfo.only_if_mode_is != this.form_type)
                    return;

                let inputElement = this.generateInput(inputInfo);
                this.get_input_value_fns.push(inputInfo.get.bind(inputElement));
                contentElement.appendChild(inputElement);
            });
        } else if (this.form_type == "edit") {
            inputsInfo.forEach((inputInfo) => {
                if (inputInfo.only_if_mode_is !== undefined && inputInfo.only_if_mode_is != this.form_type)
                    return;

                let inputElement = this.generateInput(inputInfo, element[inputInfo.id]);
                this.get_input_value_fns.push(inputInfo.get.bind(inputElement));
                contentElement.appendChild(inputElement);
            });
        }

        return contentElement;
    }

    generateInput(inputInfo, value=null) {
        let contentInputElement = document.createElement('div');
        contentInputElement.classList.add('input-block-element');

        if (inputInfo.label !== null) {
            let labelElement = document.createElement('label');
            labelElement.classList.add('input-block-label');
            labelElement.textContent = inputInfo.label;
            labelElement.setAttribute('for', inputInfo.id);
            contentInputElement.appendChild(labelElement);
        }

        if (inputInfo.type == 'string' || inputInfo.type == 'email' || inputInfo.type == 'password' || inputInfo.type == 'number' || inputInfo.type == 'date_time') {
            let inputElement = document.createElement('input');
            inputElement.classList.add('input-block-input');

            if (inputInfo.type == 'email' || inputInfo.type == 'password') {
                inputElement.setAttribute('type', inputInfo.type)
            } else if (inputInfo.type == 'date_time') {
                inputElement.setAttribute('type', 'datetime-local')
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
                    this.submitForm();
                }
            }, false);

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

    generateSubmitButtons(tag, id=null) {
        var urlTag = this.URL_TAGS[tag];

        let submitElement = document.createElement('div');
        submitElement.classList.add('form-block-submit');

        let cancelButton = document.createElement('input');
        cancelButton.setAttribute('type', 'button');
        cancelButton.setAttribute('value', 'Cancelar');
        cancelButton.classList.add('form-block-submit-cancel')
        cancelButton.addEventListener('click', function () {
            if (id === null) {
                window.location.href = urlTag.list_url;
            } else {
                window.location.href = urlTag.back_url(id);
            }
        }, false);
        submitElement.appendChild(cancelButton);

        if (this.form_type == 'add') {
            let addButton = document.createElement('input');
            addButton.setAttribute('type', 'submit');
            addButton.setAttribute('value', 'Adicionar');
            addButton.classList.add('form-block-submit-add');
            addButton.addEventListener('click', this.submitForm.bind(this), false);
            submitElement.appendChild(addButton);
        } else if (this.form_type == 'edit') {
            let editButton = document.createElement('input');
            editButton.setAttribute('type', 'submit');
            editButton.setAttribute('value', 'Alterar');
            editButton.classList.add('form-block-submit-edit');
            editButton.addEventListener('click', this.submitForm.bind(this), false);
            submitElement.appendChild(editButton);
        }

        return submitElement;
    }

    submitForm() {
        let params = { token: getToken() };

        let setFn = function (key, value) {
            params[key] = value;
        }

        let hasError = false;

        let errorFn = function (msg) {
            hasError = true;
            showNoCodeError(msg);
        };

        var urlTag = this.URL_TAGS[this.tag];

        for (const fn of this.get_input_value_fns) {
            fn(setFn, errorFn);

            if (hasError) break;
        }

        if (hasError) return;

        if (this.form_type == 'add') {
            requestAPI(urlTag.add, params, function (response) {
                window.location.href = urlTag.back_url(response.id);
            });
        } else if (this.form_type == 'edit') {
            params.id = this.id;

            requestAPI(urlTag.edit, params, () => {
                window.location.href = urlTag.back_url(this.id);
            });
        }
    }

    static get observedAttributes() { return ['tag', 'id']; }
}

class EditFormBlockElement extends FormBlockElement { form_type = 'edit'; }
class AddFormBlockElement extends FormBlockElement { form_type = 'add'; }

window.customElements.define('form-edit-block', EditFormBlockElement);
window.customElements.define('form-add-block', AddFormBlockElement);
