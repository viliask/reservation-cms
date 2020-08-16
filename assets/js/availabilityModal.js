import Axios from 'axios';
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../public/js/fos_js_routes_website');
Routing.setRoutingData(routes);

const form = document.querySelector('.submit-form');
const roomId = document.querySelector('h1');
const checkIn = document.querySelector('#reservation_checkInDate');
const checkOut = document.querySelector('#reservation_checkOutDate');
let data = null;
const closeEl = document.querySelector('[data-close-availability]');
const visible = 'visible';

function handleForm(event) { event.preventDefault(); }
form.addEventListener('submit', handleForm);

form.addEventListener('submit',  () => {
    loadData();
    document.querySelector('#availability-modal').classList.add(visible);
});

closeEl.addEventListener('click', () => {
    document.querySelector('#availability-modal').classList.remove(visible);
});

function loadData() {
    Axios.get(Routing.generate('xhr_room_availability', {id: roomId.dataset.roomId, checkIn: checkIn.value, checkOut: checkOut.value}, true)).then((response) => {
        data = response.data;
    })
}
