import React, {Component} from 'react';

import ListProductComponent from '../../product/listProductComponent';
import ContentOptionComponent from '../content/contentOptionComponent';
import SpecialProductMediumComponent from '../../product/specialProductMediumComponent';


class DisplayOptionFourComponent extends Component {
    constructor(props) {
        super(props);
        this.state = {
            width : window.innerWidth
        }
    }

    componentDidMount = ()=> {
        window.addEventListener("resize", this.updateDimensions);
    }

    componentWillUnmount = ()=> {
        window.removeEventListener("resize", this.updateDimensions);
    }

    updateDimensions =() => {
        let width = window.innerWidth;
        // if (width >= 768 && this.state.width < 768) {
            
        // } if (width < 768 && this.state.width >= 768) {
        //     this.setState({width: width});
        // }
        this.setState({width: width});
    }

    render() {
        //let display_data = this.props.display_data;
        return(
            <div className="featured-entries-col woocommerce column custom-show-special-product custom-show-special-option option-four">
                {/** display special content */}
                <ContentOptionComponent display_data={this.props.display_data} display_option={this.props.display_option}/>
                {
                    (this.state.width >= 768 && this.state.width < 1366) && <SpecialProductMediumComponent display_data={this.props.display_data} id={this.props.id} display_option={this.props.display_option}/>
                }
                {/** display special product */}
                <div className="content-four">
                    {
                        (this.state.width >= 1366 || this.state.width < 768) && <SpecialProductMediumComponent display_data={this.props.display_data} id={this.props.id} display_option={this.props.display_option}/>
                    }
                    <div className="show-special-products-four">
                        <ListProductComponent {...this.props}/> 
                    </div>
                </div>
            </div>
        );
    }
}

export default DisplayOptionFourComponent;