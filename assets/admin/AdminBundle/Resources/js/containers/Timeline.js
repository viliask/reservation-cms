import React from 'react';
import 'vis-timeline/styles/vis-timeline-graph2d.css';
import Timeline from 'react-vis-timeline'
import Axios from 'axios';
import Routing from '../../../../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import {DataSet} from 'vis-data';

const routes = require('../../../../../../public/js/fos_js_routes.json');
Routing.setRoutingData(routes);

let items, groups;

const date = new Date(), y = date.getFullYear(), m = date.getMonth();
const firstDOM = new Date(y, m, 1);
const lastDOM = new Date(y, m + 1, 0);

const options = {
    start: firstDOM,
    end: lastDOM
};

class Timeline2D extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            items: null,
            groups: null,
        };
    }

    componentDidMount() {
        this.loadData();
    }

    loadData() {
        Axios.get(Routing.generate('app.imeline_items', null, true)).then((response) => {
            items = response.data;
            this.setState({
                items: new DataSet(response.data),
            });
        }).then(() => {
            Axios.get(Routing.generate('app.imeline_groups', null, true)).then((response) => {
                groups = response.data;
                this.setState({
                    groups: new DataSet(response.data),
                });
            });
        });
    }

    render() {
        return <div id="visualization">Test</div>;
    }
}

export default Timeline2D;
