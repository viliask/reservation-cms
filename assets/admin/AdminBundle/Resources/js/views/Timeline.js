import React from 'react';
import {withToolbar} from 'sulu-admin-bundle/containers';
import type {ViewProps} from 'sulu-admin-bundle/containers';
import TimelineContainer from '../containers/Timeline';

class Timeline extends React.Component<ViewProps> {
    render() {
        return (<TimelineContainer />);
    }
}

export default withToolbar(Timeline, function() {
    return {};
});
