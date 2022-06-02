import React, {Component} from 'react';
import Slider from "react-slick";
import {
    URL_GET_LIST_PRODUCT_IDS
} from '../../../variable';
import axios from 'axios';
import NextArrowComponent from '../../../shareComponent/arrows/nextArrowComponent';
import PrevArrowComponent from '../../../shareComponent/arrows/prevArrowComponent';
class SpecialProductMediumComponent extends Component {
    constructor(props) {
        super(props);
        this.state = {
            loaded : false,
            fetched : false,
            products : []
        };
    }

    componentWillMount() {
        let id = this.props.id;
        if(sessionStorage.getItem(id)) {
            let datajson = JSON.parse(sessionStorage.getItem(id));
            if (datajson.display_data && datajson.display_data.products.length > 0)
            this.setState({
                fetched : false,
                loaded : true,
                products : datajson.display_data.products
            });
        } else {
            let {display_data, display_option} = this.props;
            let productIds = [];
            for(let index = 1; index <= 4; index++) {
                if (display_data['data_type_' +display_option+ '_special_product_id_' + index] && display_data['data_type_' +display_option+ '_special_product_id_' + index] !== "") {
                    productIds.push(display_data['data_type_' +display_option+ '_special_product_id_' + index]);
                }
            }

            productIds = productIds.join(',');
            let url = URL_GET_LIST_PRODUCT_IDS.replace('{productids}', productIds);
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
                        data.display_data = {
                            products : this.state.products
                        };
                        sessionStorage.setItem(id, JSON.stringify(data));
                    } else {
                        sessionStorage.setItem(id, JSON.stringify({
                            display_data : {
                                products : this.state.products
                            }
                        }));
                    }
                }).catch((err) => {
                    console.log(err);
                });
        }
    }

    _renderSpecialProduct = ()=> {
        let {
            loaded,
            products
        } = this.state;
        if (!loaded) {
            return <div className="loading">
                    <i className="fa fa-spinner"></i>
                </div>
        } else {
            if (products.length > 0) {
                let result = [];
                for (let index in products) {
                    let product = products[index];
                    let rating = '';
                    if (product.average_rating !== "0") {
                        rating = <div className="star-rating">
                                    <span style={{width: (product.average_rating)/5 *100 + '%' }}>Rated <strong className="rating">{product.average_rating}</strong> out of 5</span>
                                </div>
                    }
                    let price = '';
                    if (product.sale_price !== '0') {
                        // regular_price
                        price = <span className="price">
                                    <ins>
                                        <span className="price-label">Khuyến mãi: </span>
                                        <span className="woocommerce-Price-amount amount">{product.sale_price}<span className="woocommerce-Price-currencySymbol">đ</span></span>
                                    </ins>
                                    <del>
                                        <strong class="price-label">Giá: </strong>
                                        <span className="woocommerce-Price-amount amount">{product.regular_price}<span className="woocommerce-Price-currencySymbol">đ</span></span>
                                    </del>
                                </span>
                    } else {
                        price = <span className="price">
                                    <ins>
                                        <span className="price-label">Giá: </span>
                                        <span className="woocommerce-Price-amount amount">{product.regular_price}<span className="woocommerce-Price-currencySymbol">đ</span></span>
                                    </ins>
                                </span>
                    }
                    result.push(
                        <a href={product.link} key={index}>
                            <div className="special-product-item contents" >
                                <div className="image">
                                    <img src={product.image} alt="" />
                                </div>
                                <div className="content">
                                    <h3>{product.name.length <= 40 ? product.name : product.name.substr(0, 40) + "..."}</h3>
                                    {price}
                                    {rating}
                                </div>
                            </div>
                        </a>
                    );
                }
                if (this.props.display_option == 4) {
                    // render slide
                    let settings = {
                        autoplay: true,
                        adaptiveHeight: true,
                        arrows: true,
                        dots: false,
                        infinite: true,
                        autoplaySpeed: 5000,
                        speed: 1000,
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        adaptiveHeight : false,
                        nextArrow: <NextArrowComponent className="fa fa-angle-left"/>,
                        prevArrow: <PrevArrowComponent className="fa fa-angle-right" />,
                        responsive: [
                            {
                                breakpoint: 1366,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 2,
                                    vertical : false
                                }
                            },
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 4,
                                    slidesToScroll: 4,
                                    vertical : true
                                }
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 4,
                                    slidesToScroll: 4,
                                    vertical : true
                                }
                            },
                            {
                                breakpoint: 640,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 2,
                                    vertical : true
                                }
                            }
                        ]
                    };
                    return <Slider {...settings}>
                        {result}
                    </Slider>;
                }
                else return result;

            }
        }
        return <React.Fragment></React.Fragment>
    }

    render() {
        return (
            <div className="show-special-product">
                {this._renderSpecialProduct()}    
            </div>
        )
    }
}

export default SpecialProductMediumComponent;