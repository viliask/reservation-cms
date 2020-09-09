import Axios from 'axios';
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import {visible, handleModalClose, validateForm, form} from './helper'

const openEl = document.querySelector('[data-open]');
const closeEl = document.querySelector('[data-close]');
const openPolicyModal = document.querySelector('[data-open-policy]');
const PRICE = 40;

const routes = require('../../public/js/fos_js_routes_website');
const roomId = document.querySelector('.id-js');
const checkIn = document.querySelector('#reservation_checkInDate');
const checkOut = document.querySelector('#reservation_checkOutDate');
let responseData = null;
const closeAvailabilityModal = document.querySelector('[data-close-availability]');
const closeRoomNotAvailableModal = document.querySelector('[data-close-not-available]');

handleModalClose();

const updatePrice = () => {
    const checkIn = new Date(document.querySelector('#event_checkIn').value);
    const checkOut = new Date(document.querySelector('#event_checkOut').value);
    const guests = document.querySelector('#event_guests').value;
    const daysOfVisit = (checkOut.getTime() - checkIn.getTime()) / (1000 * 3600 * 24);
    document.querySelector('#event_price').value = (daysOfVisit * PRICE * guests);
};

const loadData = async () => {
    const response = await Axios.get(Routing.generate('xhr_room_availability',
        {
            id:       roomId.dataset.roomId,
            checkIn:  checkIn.value,
            checkOut: checkOut.value
        }, true));
    return response.data;
};


const updateForm = () => {
    const guests = document.querySelector('#reservation_guests').value;
    document.querySelector('#event_checkIn').value = new Date(responseData.checkIn).toISOString().slice(0, 16);
    document.querySelector('#event_checkOut').value = new Date(responseData.checkOut).toISOString().slice(0, 16);
    document.querySelector('#event_guests').value = guests;
};

Routing.setRoutingData(routes);

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

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (validateForm()) {
        await loadData().then((data) => {
            responseData = data;
            if (responseData.status === true) {
                document.querySelector('#availability-modal').classList.add(visible);
                updateForm();
            } else {
                document.querySelector('#room-not-available-modal').classList.add(visible);
            }
        });
    }
});

closeAvailabilityModal.addEventListener('click', () => {
    document.querySelector('#availability-modal').classList.remove(visible);
});

closeRoomNotAvailableModal.addEventListener('click', () => {
    document.querySelector('#room-not-available-modal').classList.remove(visible);
});
