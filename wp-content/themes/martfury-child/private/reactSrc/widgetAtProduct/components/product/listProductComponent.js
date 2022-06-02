import React, {Component} from 'react';
import Slider from "react-slick";
import {
    URL_GET_LIST_PRODUCT_CAT
} from '../../../variable';
import axios from 'axios';
import NextArrowComponent from '../../../shareComponent/arrows/nextArrowComponent';
import PrevArrowComponent from '../../../shareComponent/arrows/prevArrowComponent';
import ProductItemColumnComponent from './productItemColumnComponent';
import ProductItemRowComponent from './productItemRowComponent';
import Axios from 'axios';

class ListProductComponent extends Component {
    constructor(props) {
        super(props);
        this.state= {
            loaded : false,
            fetched : false,
            start_page : 0,
            products : []
        }
    }

    componentWillMount() {
        let {
            id, advanced_option, product_cat, product_tag, post_number, orderby, order
        } = this.props;
        if(sessionStorage.getItem(id) && JSON.parse(sessionStorage.getItem(id)).products && JSON.parse(sessionStorage.getItem(id)).products.length > 0) {
            this.setState({
                fetched : false,
                loaded : true,
                products : JSON.parse(sessionStorage.getItem(id)).products
            });
        } else {
            let url = URL_GET_LIST_PRODUCT_CAT.replace('{advanced_option}', advanced_option)
                                          .replace('{product_cat}', product_cat)
                                          .replace('{product_tag}', product_tag)
                                          .replace('{post_number}', post_number)
                                          .replace('{orderby}', orderby)
                                          .replace('{order}', order)
                                          .replace('{start_page}', this.state.start_page);
            axios.get (url)
            .then(resData => {
                let _resData = resData.data;
                if (_resData.status !== "OK") {
                    console.log(_resData.errMsg)
                }
                this.setState({
                    fetched : true,
                    loaded : true,
                    products : _resData.data == null ? [] : _resData.data
                });

                if (sessionStorage.getItem(id)) {
                    let data = JSON.parse(sessionStorage.getItem(id));
                    data.products = this.state.products;
                    sessionStorage.setItem(id, JSON.stringify(data));
                } else {
                    sessionStorage.setItem(id, JSON.stringify({
                        products : this.state.products
                    }));
                }
            }).catch((err) => {
                console.log(err);
            });
        }
    }

    _renderListProducts = ()=> {
        let display_option = this.props.display_option;
        let {
            products
        } = this.state;
        let listItems = products.map((item, index) => {
            if (display_option == 3) return <ProductItemRowComponent product={item} key={index} />
            return <ProductItemColumnComponent product={item} key={index} />
        });

        
        let slideShow_1 = 4;
        let slideShow_2 = 4;
        let slideShow_3 = 4;
        let slideShow_4 = 4;
        let vertical = null;
        if (display_option == 1) {
            slideShow_2 = 3;
            slideShow_3 = 2;
            slideShow_4 = 2;
        } else if (display_option == 2) {
            slideShow_1 = 3
            slideShow_2 = 2;
            slideShow_3 = 1;
            slideShow_4 = 2;
        } else if (display_option == 3) {
            slideShow_1 = 5
            slideShow_2 = 4;
            slideShow_3 = 3;
            slideShow_4 = 3;
            vertical = true;
        } else if (display_option == 4) {
            slideShow_1 = 3;
            slideShow_2 = 4;
            slideShow_3 = 3;
            slideShow_4 = 2;
        }
        
        let settings = {
            autoplay: true,
            adaptiveHeight: true,
            arrows: true,
            dots: false,
            infinite: true,
            autoplaySpeed: 5000,
            speed: 1000,
            slidesToShow: slideShow_1,
            slidesToScroll: slideShow_1,
            nextArrow: <NextArrowComponent className="fa fa-angle-left"/>,
            prevArrow: <PrevArrowComponent className="fa fa-angle-right" />,
            adaptiveHeight : false,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: slideShow_2,
                        slidesToScroll: slideShow_2
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: slideShow_3,
                        slidesToScroll: slideShow_3
                    }
                },
                {
                    breakpoint: 640,
                    settings: {
                        slidesToShow: slideShow_4,
                        slidesToScroll: slideShow_4
                    }
                }
            ]
        };
        if (vertical) {
            settings.vertical = vertical;
            settings.adaptiveHeight = false;
        }
        return <Slider {...settings}>
                    {listItems}
                </Slider>;
    }

    render() {
        let {
            loaded, fetched, products
        } = this.state;
        if (!loaded) {
            return <div className="loading">
                        <i className="fa fa-spinner"></i>
                    </div>
        } else {
            if (products.length > 0) {
                return this._renderListProducts();
            }
        }
        return <React.Fragment></React.Fragment>
    }
}

export default ListProductComponent;