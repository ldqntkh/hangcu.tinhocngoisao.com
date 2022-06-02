import React, {Component} from 'react';
import ListProductComponent from '../../product/listProductComponent';
import SpecialProductLargeComponent from '../../product/specialProductLargeComponent';
class DisplayOptionTwoComponent extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        //let display_data = this.props.display_data;
        return(
            <div className="featured-entries-col woocommerce column custom-show-special-product">
                {/** display special product */}
                <SpecialProductLargeComponent display_data={this.props.display_data} id={this.props.id}/>
                
                <ListProductComponent {...this.props}/> 
            </div>
        );
    }
}

export default DisplayOptionTwoComponent;