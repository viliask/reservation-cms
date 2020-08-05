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

document.querySelector('#event_guests').addEventListener('change', () => {
    const checkIn = new Date(document.querySelector('#event_checkIn').value);
    const checkOut = new Date(document.querySelector('#event_checkOut').value);
    const guests = document.querySelector('#event_guests').value;
});
