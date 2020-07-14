import React from 'react';
import 'vis-timeline/styles/vis-timeline-graph2d.css';
import Timeline from 'react-vis-timeline'
import Axios from 'axios';
import Routing from '../../../../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../../../../../public/js/fos_js_routes.json');
Routing.setRoutingData(routes);

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
            this.setState({
                items: response.data,
            });
        }).then(() => {
            Axios.get(Routing.generate('app.imeline_groups', null, true)).then((response) => {
                this.setState({
                    groups: response.data,
                });
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
