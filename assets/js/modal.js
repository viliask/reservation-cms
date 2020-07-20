const openEl = document.querySelector('[data-open]');
const closeEl = document.querySelector('[data-close]');
const visible = 'visible';
const makeInvisible = (modal) => {
    modal.classList.remove(visible);
};

openEl.addEventListener('click', function () {
    const modalId = this.dataset.open;
    document.getElementById(modalId).classList.add(visible);
});

closeEl.addEventListener('click', function () {
    this.parentElement.parentElement.parentElement.classList.remove(visible);
});

document.addEventListener('click', e => {
    const openedModal = document.querySelector('.modal.visible');

    if (e.target === openedModal) {
        makeInvisible(openedModal);
    }
});

document.addEventListener('keyup', e => {
    const openedModal = document.querySelector('.modal.visible');

    if (e.key === 'Escape' && openedModal) {
        makeInvisible(openedModal);
    }
});
