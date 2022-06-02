import React, {Component} from 'react';

export default class SaveConfigBuilPcComponent extends Component {

    render() {
        return(
            <div className="btn-item">
                <button type="button" className="btn btn-saveconfig">
                    <i className="fa fa-floppy-o" />
                    Lưu cấu hình
                </button>
            </div>
        );
    }
}