import Axios from 'axios';
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../public/js/fos_js_routes_website');
Routing.setRoutingData(routes);

const form = document.querySelector('.submit-form');

form.addEventListener('submit',  () => {
    loadData();
});

function loadData() {
    Axios.get(Routing.generate('xhr_room_availability', {}, true)).then((response) => {
        const data = response.data;
    })
}
