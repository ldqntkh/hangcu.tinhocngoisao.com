import React, {Component} from 'react';
import Slider from "react-slick";
import ProductItemComponent from './productItemComponent';
import {
    URL_API_GET_LIST_PRODUCT_ON_SALE
} from '../../../variable';
import axios from 'axios';

/**
 * tham số đầu vào 
 * display : mobile or pc
 * cat_slug : number
 * total_products : number
 * image_url : string
 */
// &cat_slug={cat_slug}&post_per_page={post_per_page}
class ListProductComponent extends Component {

    constructor (props) {
        super(props);

        this.state = {
            load_from_api : false,
            loaded : false,
            products : []
        }
    }

    componentWillMount() {
        /**
         * fetch data từ api
         * if không có product sẽ hiển thị component not found
         */
        let { cat_slug, total_products } = this.props;
        let url = URL_API_GET_LIST_PRODUCT_ON_SALE.replace('{cat_slug}', cat_slug).replace('{post_per_page}', total_products);
        axios.get (url)
        .then(resData => {
            this.setState({
                load_from_api : true,
                loaded : true,
                products : resData.data
            });
        }).catch((err) => {
            console.log(err);
        });
        
    }

    renderListProduct = ()=> {
        let products = this.state.products;
        let result = [];
        if (products && products.length > 0)
            for(let index in products) {
                result.push(<ProductItemComponent key={index} dataProduct={products[index]}/>);
            }
        return result;
    }

    render () {
        let {display, image_url} = this.props;
        let {load_from_api, loaded, products} = this.state;
        
        if (!load_from_api) {
            return (
                <div className="loading">
                    <i className="fa fa-spinner"></i>
                </div>
            );
        } else {
            if (loaded) {
                if (products.length == 0) {
                    if (display == 'mobile') {
                        // display component notfound
                        return(<div className="image-mobile">
                            <img src={image_url} alt="" />
                        </div>);
                    } else {
                        // display component notfound
                        return(<div className="image-pc">
                            <img src={image_url} alt="" />
                        </div>);
                    }
                } else {
                    let settings;
                    if (display == 'mobile') { // nho hon 767px
                        settings = {
                            autoplay: true,
                            adaptiveHeight: true,
                            arrows: false,
                            dots: false,
                            infinite: true,
                            speed: 500,
                            slidesToShow: 2,
                            slidesToScroll: 2
                        };
                    } else {
                        settings = {
                            autoplay: true,
                            adaptiveHeight: true,
                            arrows: false,
                            dots: false,
                            infinite: true,
                            speed: 500,
                            slidesToShow: 1,
                            slidesToScroll: 1
                        };
                    }
                    return (
                        <div className="featured-entries-col woocommerce column custom-primetime-sale">
                            <Slider {...settings}>
                                {this.renderListProduct()}
                            </Slider>
                        </div>
                    );
                }
            } else {
                return(<div></div>);
            }
        }
    }

}

export default ListProductComponent;