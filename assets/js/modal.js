const openEl = document.querySelector('[data-open]');
const closeEl = document.querySelector('[data-close]');
const visible = 'visible';
const PRICE = 40;

const makeInvisible = (modal) => {
    modal.classList.remove(visible);
};

const updatePrice = () => {
    const checkIn = new Date(document.querySelector('#event_checkIn').value);
    const checkOut = new Date(document.querySelector('#event_checkOut').value);
    const guests = document.querySelector('#event_guests').value;
    const daysOfVisit = (checkOut.getTime() - checkIn.getTime()) / (1000 * 3600 * 24);
    document.querySelector('#event_price').value = (daysOfVisit * PRICE * guests);
};

openEl.addEventListener('click', () => {
    const modalId = this.dataset.open;
    document.getElementById(modalId).classList.add(visible);
    updatePrice();
});

closeEl.addEventListener('click', () => {
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
    updatePrice();
});
