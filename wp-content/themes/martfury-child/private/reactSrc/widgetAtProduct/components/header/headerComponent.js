import React, {Component} from 'react';

class HeaderComponent extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        let {
            title, description, display_option,
            title_link, link
        } = this.props;
        return(
            <div className="at-cat-color-wrap-15">
                <div className="at-title-action-wrapper clearfix">
                    <div className="pointer">
                        <h2 className="widgettitle">
                            {title}
                            <span className="wg-description">{description}</span>
                        </h2>
                    </div>
                    { display_option !== 1 && <span className="at-action-wrapper">
                        <a href={!link ? "#" : link} className="all-link">
                            {title_link}
                        </a>
                    </span>
                    }
                </div>
            </div>
        );
    }
}

export default HeaderComponent;