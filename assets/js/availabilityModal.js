import Axios from 'axios';
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../public/js/fos_js_routes_website');
Routing.setRoutingData(routes);

function loadData() {
    Axios.get(Routing.generate('xhr_room_availability', {}, true)).then((response) => {
        const data = response.data;
    })
}
