import React from 'react';
import 'vis-timeline/styles/vis-timeline-graph2d.css';
import '../../../../../css/timeline.css';
import Timeline from 'react-vis-timeline'
import Axios from 'axios';
import Routing from '../../../../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../../../../../public/js/fos_js_routes.json');
Routing.setRoutingData(routes);

// TODO Make these consts global variables
const date = new Date(), y = date.getFullYear(), m = date.getMonth();
const firstDOM = new Date(y, m, 1);
const lastDOM = new Date(y, m + 1, 0);

const options = {
    template: function (item) {
        return `<a href="${item.link}" target="_blank">${item.customer}, 
            ${item.className}, 
            guests: ${item.guests}, 
            ${item.start.toLocaleDateString()} ${item.start.toLocaleTimeString()} - 
            ${item.end.toLocaleDateString()} ${item.end.toLocaleTimeString()}
            </a>`;
    },
    start: firstDOM,
    end: lastDOM,
    showTooltips: true,
    tooltip: {
        template: function (item) {
            return `<span>Customer: ${item.customer}</span><br>
                    <span>Status: ${item.className}</span><br>
                    <span>Guests amount: ${item.guests}</span><br>
                    <span>Check in: ${item.start.toLocaleDateString() + ' ' + item.start.toLocaleTimeString()}</span><br>
                    <span>Check out: ${item.end.toLocaleDateString() + ' ' + item.end.toLocaleTimeString()}</span><br>
                    `;
        }
    }
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
            this.setState({
                items: response.data,
            });
        });
        Axios.get(Routing.generate('app.imeline_groups', null, true)).then((response) => {
            this.setState({
                groups: response.data,
            });
        });
    }

    render() {
        if (this.state.items === null || this.state.groups === null) {
            return <h1>Loading...</h1>
        } else {
            return <div>
                <h1>Timeline</h1>
                <Timeline options={options} initialItems={this.state.items} initialGroups={this.state.groups}/>
            </div>
        }
    }
}

export default Timeline2D;
