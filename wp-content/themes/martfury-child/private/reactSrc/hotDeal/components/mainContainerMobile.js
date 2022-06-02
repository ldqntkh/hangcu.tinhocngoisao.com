import React, {Component} from 'react';

import ListProductComponent from './product/listProductComponent';
class MainContainerMobile extends Component {

    constructor (props) {
        super (props);
        this.state = {
            cat_slug : '',
            display : '',
            image_url : '',
            total_products : ''
        }
    }

    componentWillMount() {
        let element = document.getElementById('dv-primetime-price-mobile');
        let dataset = element.dataset;
        if (dataset) {
            this.setState({
                cat_slug : dataset.cat_slug,
                display : dataset.display,
                image_url : dataset.image_url,
                total_products : dataset.total_products
            });
        }
    }

    render() {
        let { cat_slug, display, image_url, total_products } = this.state;
        return(
            <ListProductComponent cat_slug={cat_slug} display={display} image_url={image_url} total_products={total_products} />
        );
    }
}

export default MainContainerMobile