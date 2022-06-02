import React, {Component} from 'react';

export default class NextArrowComponent extends Component {
    constructor(props) {
        super(props);
    }
    render() {
        return (
            <i
                className={this.props.className + ' fa fa-angle-right'}
                onClick={this.props.onClick}
            />
        );
    }
}