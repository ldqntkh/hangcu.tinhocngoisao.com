import React, {Component} from 'react';

import ListProductComponent from '../../product/listProductComponent';
import ContentOptionComponent from '../content/contentOptionComponent';
import SpecialProductMediumComponent from '../../product/specialProductMediumComponent';


class DisplayOptionThreeComponent extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        //let display_data = this.props.display_data;
        return(
            <div className="featured-entries-col woocommerce column custom-show-special-product custom-show-special-option option-three">
                {/** display special content */}
                <ContentOptionComponent display_data={this.props.display_data} display_option={this.props.display_option}/>
                {/** display special product */}
                <SpecialProductMediumComponent display_data={this.props.display_data} id={this.props.id} display_option={this.props.display_option}/>
                <div className="show-special-products">
                    <ListProductComponent {...this.props}/> 
                </div>
            </div>
        );
    }
}

export default DisplayOptionThreeComponent;