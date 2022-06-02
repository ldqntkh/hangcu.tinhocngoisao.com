import React, {Component} from 'react';

class ContentOptionComponent extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        let display_data = this.props.display_data;
        let display_option = this.props.display_option;

        return(
            <div className="special-content">
                <a href={display_data['data_type_' + display_option + '_special_content_url']}>
                    <img src={display_data['data_type_' + display_option + '_special_content_url_image']} alt="" />
                    {/* <div className="content">
                        <h3>{display_data['data_type_' + display_option + '_special_content_title']}</h3>
                        <i>{display_data['data_type_' + display_option + '_special_content_desc']}</i>
                    </div> */}
                </a>
            </div>
        );
    }
}

export default ContentOptionComponent;