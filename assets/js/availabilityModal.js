import Axios from 'axios';
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../public/js/fos_js_routes_website');
const form = document.querySelector('.submit-form');
const roomId = document.querySelector('h1');
const checkIn = document.querySelector('#reservation_checkInDate');
const checkOut = document.querySelector('#reservation_checkOutDate');
let responseData = null;
const closeAvailabilityModal = document.querySelector('[data-close-availability]');
const closeRoomNotAvailableModal = document.querySelector('[data-close-not-available]');
const visible = 'visible';

const loadData = async () => {
    const response = await Axios.get(Routing.generate('xhr_room_availability',
        {
            id: roomId.dataset.roomId,
            checkIn: checkIn.value,
            checkOut: checkOut.value
        }, true));
    return response.data;
};

const updateForm = () => {
    const guests = document.querySelector('#reservation_guests').value;
    document.querySelector('#event_checkIn').value = new Date(responseData.checkIn).toISOString().slice(0,16);
    document.querySelector('#event_checkOut').value = new Date(responseData.checkOut).toISOString().slice(0,16);
    document.querySelector('#event_guests').value = guests;
};

Routing.setRoutingData(routes);

form.addEventListener('submit',  async (event) => {
    event.preventDefault();
    await loadData().then((data) => {
        responseData = data;
        if (responseData.status === true) {
            document.querySelector('#availability-modal').classList.add(visible);
            updateForm();
        } else {
            document.querySelector('#room-not-available-modal').classList.add(visible);
        }
    });
});

closeAvailabilityModal.addEventListener('click', () => {
    document.querySelector('#availability-modal').classList.remove(visible);
});

closeRoomNotAvailableModal.addEventListener('click', () => {
    document.querySelector('#room-not-available-modal').classList.remove(visible);
});
