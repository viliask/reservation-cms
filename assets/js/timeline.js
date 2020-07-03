import { DataSet, Timeline } from 'vis-timeline/standalone';
import 'vis-timeline/styles/vis-timeline-graph2d.css';

// DOM element where the Timeline will be attached
const container = document.getElementById('visualization');

// Create a DataSet (allows two way data-binding)
const items = new DataSet([
    { id: 1, group: 1, content: 'item 1', start: '2014-04-20' },
    { id: 2, group: 1, content: 'item 2', start: '2014-04-14' },
    { id: 3, group: 1, content: 'item 3', start: '2014-04-18' },
    { id: 4, group: 2, content: 'item 4', start: '2014-04-16', end: '2014-04-19' },
    { id: 5, group: 2, content: 'item 5', start: '2014-04-25' },
    { id: 6, group: 2, content: 'item 6', start: '2014-04-27', type: 'point' }
]);

const groups = [
    {
        id: 1,
        content: 'Group 1'
    },
    {
        id: 2,
        content: 'Group 2'
    }
];

// Configuration for the Timeline
const options = {
    start: '2014-04-01',
    end: '2014-10-01'
};

// Create a Timeline
const timeline = new Timeline(container, items, groups, options);
