const openEl = document.querySelector('[data-open]');
const closeEl = document.querySelector('[data-close]');
const openPolicyModal = document.querySelector('[data-open-policy]');
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
    if (document.querySelector('#availability-modal')) {
        document.querySelector('#availability-modal').classList.remove(visible);
    }

    document.querySelector('#reservation-modal').classList.add(visible);
    updatePrice();
});

closeEl.addEventListener('click', () => {
    document.querySelector('#reservation-modal').classList.remove(visible);
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

openPolicyModal.addEventListener('click', () => {
    document.querySelector('#policy-modal').classList.add(visible);
    document.querySelector('#reservation-modal').classList.remove(visible);

    document.querySelector('[data-close-policy]').addEventListener('click', () => {
        document.querySelector('#policy-modal').classList.remove(visible);
        document.querySelector('#reservation-modal').classList.add(visible);
    });
});
