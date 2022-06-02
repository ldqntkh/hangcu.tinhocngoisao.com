import React, {Component} from 'react';
import ListProductComponent from '../../product/listProductComponent';
class DisplayOptionOneComponent extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        let display_data = this.props.display_data;
        return(
            <div className="featured-entries-col woocommerce column">
                {/** display image */}
                <div className="single-list acme-col-4 hidden-mobile">
                    <ul className="post-container products">
                        <li className="product custom-show-banner">
                            <a href={display_data.data_type_1_link_redirect}>
                                <img src={display_data.data_type_1_image_url} alt="" />
                            </a>
                        </li>
                    </ul>
                </div>
                <ListProductComponent {...this.props}/>
            </div>
        );
    }
}

export default DisplayOptionOneComponent;