import {visible, handleModalClose} from './helper'

handleModalClose();

const openEl = document.querySelector('[data-open]');
const closeEl = document.querySelector('[data-close]');
const openPolicyModal = document.querySelector('[data-open-policy]');
const PRICE = 40;

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

document.addEventListener('DOMContentLoaded',  () => {
    if (document.querySelector('[data-checked]')) {
        document.querySelector('#reservation-modal').classList.add(visible);
        updatePrice();
    }
});
