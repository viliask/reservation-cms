import Axios from 'axios';
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import {visible, handleModalClose, validateForm, form} from './helper'

Date.prototype.addHours = function(h) {
    this.setTime(this.getTime() + (h*60*60*1000));
    return this;
};

const routes = require('../../public/js/fos_js_routes_website');
Routing.setRoutingData(routes);

const openEventForm = document.querySelector('[data-open]');
const closeEventForm = document.querySelector('[data-close]');
const openPolicyModal = document.querySelector('[data-open-policy]');
const roomId = document.querySelector('.id-js');
const checkIn = document.querySelector('#reservation_checkInDate');
const checkOut = document.querySelector('#reservation_checkOutDate');
const closeAvailabilityModal = document.querySelector('[data-close-availability]');
const closeRoomNotAvailableModal = document.querySelector('[data-close-not-available]');
const alert = document.querySelector('div.alert-success');
const alertText = alert.textContent;
let PRICE = 0;
let stepsAmount = 0;
let stepsDiscount = 0;
let maxGuests = 0;
let finalStepsDiscount = 0;
let responseData = {
    basePrice: null,
    checkIn: null,
    checkOut: null,
    discount: null,
    discountName: null,
    maxGuests: null,
    status: null,
    stepsAmount: null,
    stepsContent: null,
    stepsDiscount: null,
};
let discount = 0;
let stepsContent = '';

const updatePrice = () => {
    document.querySelector('#event_checkIn').value = new Date(document.querySelector('#reservation_checkInDate').value).addHours(16).toISOString().slice(0, 16);
    document.querySelector('#event_checkOut').value = new Date(document.querySelector('#reservation_checkOutDate').value).addHours(12).toISOString().slice(0, 16);
    const checkIn = new Date(document.querySelector('#reservation_checkInDate').value);
    const checkOut = new Date(document.querySelector('#reservation_checkOutDate').value);
    const guests = document.querySelector('#event_guests').value;
    const daysOfVisit = (checkOut.getTime() - checkIn.getTime()) / (1000 * 3600 * 24);

    if (stepsAmount > 0 && maxGuests > guests) {
        const multiplier = maxGuests - guests;
        finalStepsDiscount = (100 - (stepsDiscount * multiplier)) / 100;
        showPromo(stepsContent + (stepsDiscount * multiplier).toString() + '%');
    } else {
        finalStepsDiscount = 1;
    }

    document.querySelector('#event_price').value = (daysOfVisit * PRICE * finalStepsDiscount) * (discount/100);
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

const showPromo = (content) => {
    const oldUl = document.querySelector('div.alert-success ul');

    alert.classList.remove('invisible');
    alert.classList.add('alert');

    if (oldUl) {
        const li = document.createElement('li');
        li.appendChild(document.createTextNode(content));
        oldUl.appendChild(li);
    } else {
        const ul = document.createElement('ul');
        const li = document.createElement('li');

        li.appendChild(document.createTextNode(content));
        ul.style.paddingLeft = '20px';
        ul.appendChild(li);
        alert.appendChild(ul);
    }
};

const updateForm = () => {
    const guests = document.querySelector('#reservation_guests').value;
    const alert = document.querySelector('div.alert-success.alert');
    document.querySelector('#event_guests').value = guests;
    discount = 100 - responseData.discount;
    PRICE = responseData.basePrice;
    stepsAmount = responseData.stepsAmount;
    stepsDiscount = responseData.stepsDiscount;
    maxGuests = responseData.maxGuests;
    stepsContent = responseData.stepsContent;

    if (responseData.discountName) {
        showPromo(responseData.discountName);
    } else if (alert) {
        alert.classList.add('invisible');
        alert.classList.remove('alert');
    }
};

handleModalClose();

// Modal after availability check - form modal
openEventForm.addEventListener('click', () => {
    if (document.querySelector('#availability-modal')) {
        document.querySelector('#availability-modal').classList.remove(visible);
    }

    document.querySelector('#reservation-modal').classList.add(visible);
    updatePrice();
});

// Close form modal
closeEventForm.addEventListener('click', () => {
    document.querySelector('#reservation-modal').classList.remove(visible);
});

openPolicyModal.addEventListener('click', () => {
    document.querySelector('#policy-modal').classList.add(visible);
    document.querySelector('#reservation-modal').classList.remove(visible);

    document.querySelector('[data-close-policy]').addEventListener('click', () => {
        document.querySelector('#policy-modal').classList.remove(visible);
        document.querySelector('#reservation-modal').classList.add(visible);
    });
});

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (validateForm()) {
        alert.innerHTML = alertText;
        await loadData().then((data) => {
            responseData = data;
            if (responseData.status === true) {
                updateForm();
                // Homepage path - open filled reservation form modal on simulated submit event
                if (document.querySelector('[data-checked]')) {
                    const homepagePathAttrs = document.querySelector('#homepage-path-js');
                    delete homepagePathAttrs.dataset.checked;

                    document.querySelector('#reservation-modal').classList.add(visible);
                    updatePrice();
                } else {
                    document.querySelector('#availability-modal').classList.add(visible);
                }
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

// Homepage path - redirect from reservation cards
document.addEventListener('DOMContentLoaded',  () => {
    if (document.querySelector('#homepage-path-js')) {
        // Simulate button click to prevent code duplication
        document.querySelector('#reservation_submit').click();
    }
});
