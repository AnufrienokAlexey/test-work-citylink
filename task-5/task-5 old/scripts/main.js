let num = 0;
(function() {
    //блок обьявления переменных
    let titleNav = window.location.pathname.substring(1),
        titleFormName,
        localStorageKey,
        incomingArray;

    //функция создания заголовка формы
    function createAppTitle(title) {
        let appTitle = document.createElement('h2');
        appTitle.classList.add('robotoslab');
        appTitle.textContent = title;
        return appTitle;
    }

    //функция создания формы ввода
    function createTodoItemForm() {
        let form = document.createElement('form'),
            input = document.createElement('input'),
            buttonWrapper = document.createElement('div'),
            button = document.createElement('button'),
            label = document.createElement('label');

        form.classList.add('input-group', 'mb-3', 'flex', 'form__participants');
        input.classList.add('form-control', 'robotoslab', 'form__input');
        input.setAttribute('id', 'participants')
        input.placeholder = 'Введите имена участников через запятую';
        buttonWrapper.classList.add('input-group-append');
        button.classList.add('btn', 'btn-primary', 'robotoslab');
        button.textContent = 'Добавить участника';
        label.classList.add('form__label');
        label.setAttribute('for', 'participants');
        label.textContent = 'Участники';

        button.disabled = true;
        input.addEventListener('input', function() {
            if (input.value) {
                button.disabled = false;
            } else {
                button.disabled = true;
            }
        });
        
        buttonWrapper.append(button);
        form.append(label);
        form.append(input);
        form.append(buttonWrapper);

        form.addEventListener('input', () => {
            function validateName(name) {
                let re = /^[\u0400-\u04FF\, ]+$/;
                return re.test(name);
            }

            if (validateName(input.value)) {
                input.classList.remove('input__error');
                input.classList.add('input__good');
            } else {
                modal.classList.remove('modal', 'modal__active');
                input.classList.add('input__error');
                input.classList.remove('input__good');
            }
        })

        return {
            form,
            input,
            button
        };
    }
    
    //функция создания списка
    function createTodoList() {
        let list = document.createElement('ul');
        list.classList.add('list-group');
        return list;
    }

    //функция создания элементов списка
    function createTodoItem(name) {
        let item = document.createElement('li'),
            buttonGroup = document.createElement('div'),
            doneButton = document.createElement('button'),
            deleteButton = document.createElement('button');
    
        item.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center', 'robotoslab');
        item.textContent = name;
    
        buttonGroup.classList.add('btn', 'btn-group-sm');
        doneButton.classList.add('btn', 'btn-success');
        doneButton.textContent = 'Победитель';
        deleteButton.classList.add('btn', 'btn-danger');
        deleteButton.textContent = 'Удалить';
    
        buttonGroup.append(doneButton);
        buttonGroup.append(deleteButton);
        item.append(buttonGroup);
    
        return {
            item,
            doneButton,
            deleteButton
        };
    };

    //функция создания таблиц
    function createTodoApp(titleFormName, incomingArray, localStorageKey) {
        let todoAppTitle = createAppTitle(titleFormName),
            todoItemForm = createTodoItemForm(),
            todoList = createTodoList(),
            localArray = [],
            localStorageItems = localStorage.getItem(localStorageKey);

        container.append(todoAppTitle);
        container.append(todoItemForm.form);
        container.append(todoList);

        if (!localStorageItems) {
            localArray = incomingArray.map(Array => Array);            
            localStorage.setItem(localStorageKey, JSON.stringify(localArray));
        } else {
            let parse = JSON.parse(localStorageItems);
            localArray = parse.map(Array => Array);
        };

        function addNewItem() {
            for (i in localArray) {
                let newItem = createTodoItem(localArray[i].name);
                    if (localArray[i].done === 'true') {
                        newItem.item.classList.add('list-group-item-success');
                    } else {
                        localArray[i].done = 'false';
                        newItem.item.classList.remove('list-group-item-success');
                    };
                todoList.append(newItem.item);
                buttonEvents(newItem);
            }
        };

        addNewItem();

        function buttonEvents(element) {
            element.doneButton.addEventListener('click', function() {
                let elementContent = element.item.textContent;
                let a = elementContent.substring(0, elementContent.length - 17);
                for(i = 0; i < localArray.length; i++) {
                    let b = localArray[i].name;
                    if (b == a) {
                        if (localArray[i].done === 'false') {
                            localArray[i].done = 'true';
                            element.item.classList.add('list-group-item-success');
                        } else {
                            localArray[i].done = 'false';
                            element.item.classList.remove('list-group-item-success');
                        }
                        localStorage.setItem(localStorageKey, JSON.stringify(localArray));
                    }
                }
            });
            element.deleteButton.addEventListener('click', function() {
                if (confirm('Вы действительно хотите удалить?')) {
                    element.item.remove();
                    localStorage.removeItem(localStorageKey);
                    let elementContent = element.item.textContent;
                    let a = elementContent.substring(0, elementContent.length - 17);
                    for(i = 0; i < localArray.length; i++) {
                        let b = localArray[i].name;
                        if (b == a) {
                            localArray.splice(i, 1);
                        }
                    }
                    localStorage.setItem(localStorageKey, JSON.stringify(localArray));
                }
            });
        };

        Array.prototype.last = function() {
            return this[this.length - 1];
        }

        const random = () => Math.trunc(Math.random()*101);
        
        //функция создания нижней таблицы
        function createTable(id, name, score) {
            const containerRow = document.createElement('div');
            containerRow.classList.add('container-row', 'flex', 'container');
            container.after(containerRow); 
    
            const tableId = document.createElement('div');
            tableId.classList.add('table', 'container');
            tableId.textContent = id;
            containerRow.append(tableId); 
    
            const tableName = document.createElement('div');
            tableName.classList.add('table', 'container');
            tableName.textContent = name;
            containerRow.append(tableName); 
    
            const tableScore = document.createElement('div');
            tableScore.classList.add('table', 'container');
            tableScore.textContent = score;
            containerRow.append(tableScore); 
        }
       
        todoItemForm.form.addEventListener('submit', function(e) {
            e.preventDefault();
                        
            if (!todoItemForm.input.value) {
                return;
            }

            let todoItem = createTodoItem(todoItemForm.input.value);

            buttonEvents(todoItem);

            todoList.append(todoItem.item);

            localArray = [];
            
            const parseData = dataString => {
                let arr = dataString
                .trim()
                .split(',');
                
                arr.forEach(el => {
                    localArray.push({
                        name: el.trim(),
                        done: 'false'
                    });
                })
                
                return arr;
            }   
            
            parseData(todoItemForm.input.value);

            localArray.forEach(item => {
                createTable(++num, item.name, random());
            })

            todoItemForm.input.value = '';
            todoItemForm.button.disabled = true;
        })
    }

    typeOfOlympiad.forEach(el => {
        if ( titleNav == el.href) {
            titleFormName = el.name;
            localStorageKey = el.type;
        }
    })

    nameOfOlympiad.forEach(el => {
        let a = el[1][0];
        if ( titleNav == a) {
            incomingArray = el[0];
        }
    })

    createTodoApp(titleFormName, incomingArray, localStorageKey);
}) ();
