import { DataSet, Timeline } from 'vis-timeline/standalone';
import 'vis-timeline/styles/vis-timeline-graph2d.css';
import Axios from 'axios';
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

// DOM element where the Timeline will be attached
const container = document.getElementById('visualization');

// Configuration for the Timeline
const date = new Date(), y = date.getFullYear(), m = date.getMonth();
const firstDOM = new Date(y, m, 1);
const lastDOM = new Date(y, m + 1, 0);

const options = {
    start: firstDOM,
    end: lastDOM
};

// Create a Timeline
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
