const container = document.createElement('div');
container.classList.add('container', 'mb-5');
container.id = "app";
document.body.append(container);

const modal = document.createElement('div');
modal.classList.add('modal', 'modal__active');
document.body.append(modal);

const overlay = document.createElement('div');
overlay.classList.add('modal__overlay');
modal.append(overlay);

const modalWindow = document.createElement('div');
modalWindow.classList.add('modal__window', 'flex');
overlay.append(modalWindow);

const modalText = document.createElement('p', 'reset');
modalText.classList.add('modal__text', 'reset');
modalText.textContent = 'Пожалуйста, используйте только символы кириллицы и запятую.';
modalWindow.append(modalText);

const modalA = document.createElement('div');
modalA.classList.add('modal__img-close', 'reset');
modalWindow.append(modalA);

modalA.addEventListener('click', () => {
    modal.classList.add('modal', 'modal__active');
})