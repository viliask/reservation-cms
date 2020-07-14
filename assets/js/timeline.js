import { DataSet, Timeline } from 'vis-timeline/standalone';
import 'vis-timeline/styles/vis-timeline-graph2d.css';
import Axios from 'axios';
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../public/js/fos_js_routes.json');
Routing.setRoutingData(routes);

const container = document.getElementById('visualization');
const date = new Date(), y = date.getFullYear(), m = date.getMonth();
const firstDOM = new Date(y, m, 1);
const lastDOM = new Date(y, m + 1, 0);

const options = {
    start: firstDOM,
    end: lastDOM
};

const timeline = new Timeline(container);
timeline.setOptions(options);

document.addEventListener('DOMContentLoaded',  () => {
    loadData();
});

function loadData() {
    Axios.get(Routing.generate('app.imeline_items', null, true)).then((response) => {
        timeline.setItems(new DataSet(response.data));
    }).then(() => {
        Axios.get(Routing.generate('app.imeline_groups', null, true)).then((response) => {
            timeline.setGroups(new DataSet(response.data));
        });
    });
}
