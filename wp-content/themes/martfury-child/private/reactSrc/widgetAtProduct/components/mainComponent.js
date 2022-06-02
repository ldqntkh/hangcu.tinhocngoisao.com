import React, {Component} from 'react';
import HeaderComponent from './header/headerComponent';
import DisplayOptionOneComponent from './body/display/displayOptionOneComponent';
import DisplayOptionTwoComponent from './body/display/displayOptionTwoComponent';
import DisplayOptionThreeComponent from './body/display/displayOptionThreeComponent';
import DisplayOptionFourComponent from './body/display/displayOptionFourComponent';

class MainComponent extends Component {
    constructor(props) {
        super(props);
        product_widget_custom[this.props.id]['id'] = this.props.id;
    }

    render() {
        let {
            title, description,
            display_option, display_data
        } = product_widget_custom[this.props.id];
        let title_link = null,
            link = null;
        switch (display_option) {
            case 3:
                title_link = display_data.data_type_3_special_title;
                link = display_data.data_type_3_special_url;
                break;
            case 4:
                title_link = display_data.data_type_4_special_title;
                link = display_data.data_type_4_special_url;
                break;
        }
        let displayBody = null;
        switch(display_option) {
            case 1:
                displayBody = <DisplayOptionOneComponent {...product_widget_custom[this.props.id]}/>;
                break;
            case 2:
                displayBody = <DisplayOptionTwoComponent {...product_widget_custom[this.props.id]}/>;
                break;
            case 3:
                displayBody = <DisplayOptionThreeComponent {...product_widget_custom[this.props.id]}/>;
                break;
            default:
                displayBody = <DisplayOptionFourComponent {...product_widget_custom[this.props.id]}/>;
        }
        return(
            <React.Fragment>
                <HeaderComponent 
                    title={title} 
                    description={description} 
                    display_option={display_option}
                    title_link={title_link}
                    link={link}
                />
                {displayBody}
            </React.Fragment>
        )
    }
}

export default MainComponent;