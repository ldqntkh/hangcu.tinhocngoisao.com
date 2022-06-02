import React, { Component } from "react";

class ProductDetailComponent extends Component {

    constructor(props) {
        super(props);

        this.state = {
            productId: null,
            image: null,
            link: null,
            name: null,
            average_rating: null,
            price: null,
            slug: null
        }
    }

    componentDidMount() {
        let productsChild = this.props.product.hasOwnProperty('product_childs') ? this.props.product.product_childs : [];
        let productChild = productsChild.length > 0 ? productsChild[0] : null;
        if ( productChild !== null ) {
            let productChildPrice = productChild.sale_price !== "" && productChild.sale_price !== "0" ? productChild.sale_price : productChild.regular_price;
            this.setState({
                productId: productChild.id,
                image: productChild.image,
                link: productChild.link,
                name: productChild.name,
                average_rating: productChild.average_rating,
                price: productChildPrice
            });
        }
    }

    componentDidUpdate(prevProps) {
        if (JSON.stringify(prevProps.product) !== JSON.stringify(this.props.product) ) {
            this.setState({
                productId: null,
                image: null,
                link: null,
                name: null,
                average_rating: null,
                price: null,
                slug: null
            });
        }
    }

    SetValueComputerProductByType = ()=> {
        let {
            product,
            action_data
        } = this.props;

        if ( this.state.productId !== null ) {
            let state = this.state;
            product.id = state.productId;
            product.image = state.image;
            product.link = state.link;
            product.name = state.name;
            product.average_rating = state.average_rating;
            product.regular_price = state.price;
            product.sale_price = '0';
        }

        this.props.SetValueComputerProductByType({
            "type" : action_data.value_product_type,
            "value" : product
        });
        this.props.ToogleModalChooseProduct(false);
    }

    handleChangeAttribute = (e) => {
        let slug = e.target.value;
        if ( this.state.slug !== null && this.state.slug === slug ) {
            return;
        }
        let productsChild = this.props.product.product_childs;
        let productChild = null;
        for ( let productIndex in productsChild ) {
            if ( productsChild[productIndex].attributes.slug === slug ) {
                productChild = productsChild[productIndex];
                break;
            }
        }
        if ( productChild !== null ) {
            let productChildPrice = productChild.sale_price !== "" && productChild.sale_price !== "0" ? productChild.sale_price : productChild.regular_price;
            this.setState({
                productId: productChild.id,
                image: productChild.image,
                link: productChild.link,
                name: productChild.name,
                average_rating: productChild.average_rating,
                price: productChildPrice,
                slug: slug
            });
        }
    }

    renderAttributeSelectBox = (productsChild) => {
        let showArr = [];
        let defaultValue = null;

        for ( let index in productsChild) {
            if (defaultValue === null) {
                defaultValue = productsChild[index].attributes.slug;
            }
            showArr.push(<option key={index} value={productsChild[index].attributes.slug}>{productsChild[index].attributes.name}</option>);
        }
        return <select className="variation-option" onChange={this.handleChangeAttribute}>
            {showArr}
        </select>
    }

    render() {
        let product = this.props.product;
        if (typeof product === "undefined") return <React.Fragment />;
        let rating = null;
        let price = product.sale_price !== "0" && product.sale_price !== "" ? product.sale_price : product.regular_price;
        let state = this.state;
        let name = state.name === null ? product.name : state.name,
            productId = state.productId === null ? product.id : state.productId,
            image = state.image === null ? product.image : state.image,
            link = state.link === null ? product.link : state.link,
            average_rating = state.average_rating === null ? product.average_rating : state.average_rating;

        if ( state.price !== null ) {
            price = state.price;
        }

        if (average_rating !== "0") {
            rating = <div className="star-rating">
                        <span style={{width: (average_rating)/5 *100 + '%' }}>Rated <strong className="rating">{average_rating}</strong> out of 5</span>
                    </div>
        }
        return(
            <div className="modal-product-detail">
                <div className="image">
                    <img src={image}/>
                </div>
                <div className="content">
                    <a href={link} target="_blank">
                        <p className="name"> {name} </p>
                        <p className="price"> Giá: {price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")} đ</p>
                        <p className="productid"> Mã sản phẩm: {productId} </p>
                        { rating }
                    </a>
                    {product.hasOwnProperty('product_childs') && product.product_childs.length > 0 && this.renderAttributeSelectBox(product.product_childs)}
                    <button className="add-to-build" type="button" onClick={this.SetValueComputerProductByType}>Chọn</button>
                </div>
            </div>
        );
    }
}

// create container
import { connect } from 'react-redux';

import {
    SetValueComputerProductByType,
    ToogleModalChooseProduct
} from '../../../../action/actionFunction';

const mapStateToProps = state => ({
    action_data : state.ActionReducer
});

const mapDispatchToProps = dispatch => ({
    SetValueComputerProductByType   : computer_product_type => dispatch(SetValueComputerProductByType(computer_product_type)),
    ToogleModalChooseProduct        : toogle_value => dispatch(ToogleModalChooseProduct(toogle_value))
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(ProductDetailComponent);