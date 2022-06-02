import React, {Component} from 'react';

export default class PrevArrowComponent extends Component {
    constructor(props) {
        super(props);
    }
    render() {
        return (
            <i
                className={this.props.className + ' fa fa-angle-left'}
                onClick={this.props.onClick}
            />
        );
    }
}