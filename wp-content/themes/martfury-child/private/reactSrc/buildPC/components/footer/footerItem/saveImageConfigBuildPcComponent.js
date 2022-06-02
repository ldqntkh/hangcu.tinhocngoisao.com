import React, {Component} from 'react';
import Modal from 'react-modal';
import * as htmlToImage from 'html-to-image';
class SaveImageConfigBuildPcComponent extends Component {
    constructor(props) {
        super(props);
        this.state = {
            open_popup : false,
            image_content : null,
            saving : false,
            total_item : 0
        }
        Modal.setAppElement('#build-pc-function');
    }
    handleSaveImages = () => {
        // check require product build pc
        if (localStorage.getItem('computer_building_data')) {
            let computer_building_data = JSON.parse(localStorage.getItem('computer_building_data'));
            let flagRequire = false;
            for(let index in computer_building_data) {
                if (computer_building_data[index].require && computer_building_data[index].product === null) {
                    // flagRequire = true;
                    break;
                }
            }

            if (flagRequire) {
                alert("Vui lòng chọn những sản phẩm bắt buộc phải có (*) trong cấu hình máy tính trước khi thực hiện chức năng này!");
            } else {
                this.saveImages();
            }
        }
    }

    saveImages = () => {

        // render image to popup
        let customLogoLink = document.getElementsByClassName('site-logo')[0];
        let customLogoSrc = typeof(customLogoLink) !== 'undefined' ? customLogoLink.src : '';
        let computerBuildingData = JSON.parse(localStorage.getItem('computer_building_data'));
        let dataProductType = this.props.data_product_type;
        let imageResult = null;
        let totalPrice = 0;
        let list_product_items = [];
        let total_item = 0;

        for( let index in dataProductType ) {
            let item = computerBuildingData[dataProductType[index].value];
            if (!item || !item.product || item.product === null) {
                continue;
            } else {
                let productPrice = (item.product.sale_price !== "0" && item.product.sale_price !== "") ? parseInt(item.product.sale_price) : parseInt(item.product.regular_price);
                let itemHtml = <div className="row-item" key={index}>
                    <img src={item.product.image} alt = "" />
                    <div className="row-content">
                        <h3>{item.product.name}</h3>
                        <span className="pd-id">
                            Mã sản phẩm: <strong>{item.product.id}</strong>
                        </span>
                        <span className="pd-price">
                            Số lượng: <strong>{item.quantity}</strong>
                        </span>
                        <span className="pd-price">
                            Giá: <strong>{this.formatPrice(parseInt(item.quantity) * productPrice) + 'đ'}</strong>
                        </span>
                    </div>
                </div>
                list_product_items.push(itemHtml);
                totalPrice += parseInt(item.quantity) * productPrice;
                total_item++;
            }
        }
        imageResult = 
            <React.Fragment>
                <div className="image-header">
                    <img src={customLogoSrc} alt="" />
                    <h1>Xây dựng cấu hình máy tính TinHocNgoiSao</h1>
                </div>
                <div className="image-body">
                    {list_product_items}
                </div>
                <div className="image-footer">
                    <div className="bill">
                        <h1>
                            Chi phí dự tính: <strong>{this.formatPrice(totalPrice) + 'đ'}</strong>
                        </h1>
                    </div>
                    {/* <div className="info" dangerouslySetInnerHTML={{__html: document.getElementsByClassName('header-left')[0].innerHTML}}>
                    </div> */}
                </div>
            </React.Fragment>
        this.setState({
            open_popup : true,
            image_content : imageResult,
            total_item : total_item
        });
    }

    formatPrice = (price) => {
        return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    _closeModal = ()=> {
        this.setState({
            open_popup : false,
            image_content : null
        })
    }
    _saveImageToDevice = ()=> {
        if (this.state.saving) return;
        this.setState({
            saving : true
        });
        const content = document.getElementById('custom-save-image-buildpc');
        const parentDom = content.closest('.ReactModal__Content--after-open');
        parentDom.style.height = 'auto';
        let width = parentDom.offsetWidth;
        parentDom.style.width = '1200px';
        // set state to disable button click
        // html2canvas(content, { allowTaint : true , logging : false}).then((canvas) =>
        // {
        //     canvas.getContext('2d');
        //     this._saveAs(canvas.toDataURL('image/jpeg', 1.0), "BuildPC_STARCOMPUTER.png");
        //     parentDom.style.height = 'unset';
        //     parentDom.style.width = width + 'px';
        // });

        let that  = this;
        htmlToImage.toPng(content)
        .then(function (dataUrl) {
            that._saveAs(dataUrl, "BuildPC_STARCOMPUTER.png");
        }).catch(function (error) {
            console.log(error);
            that.setState({
                saving : false
            });
        });

        // domtoimage.toPng(content)
        // .then(function (dataUrl) {
        //     that._saveAs(dataUrl, "BuildPC_STARCOMPUTER.png");
        //     parentDom.style.height = 'unset';
        //     parentDom.style.width = width + 'px';
        // })
        // .catch(function (error) {
        //     console.log(error);
        // });
    }

    _saveAs = (uri, filename) => {
        try {
            var link = document.createElement('a');
            if (typeof link.download === 'string') {
                link.href = uri;
                link.download = filename;
                link.target = '_blank';

                //Firefox requires the link to be in the body
                document.body.appendChild(link);

                //simulate click
                link.click();
                //remove the link when done
                document.body.removeChild(link);
                this.setState({
                    saving : false
                });
            } else {
                window.open(uri);
            }
        } catch(err) {
            // console.log(err.message);
        }
    }

    render() {
        return(
            <React.Fragment>
                <div className="btn-item">
                    <button type="button" className="btn btn-saveimg" onClick={this.handleSaveImages}>
                        <i className="fa fa-file-image-o" />
                        Tải ảnh cấu hình
                    </button>
                </div>
                <Modal
                    isOpen={this.state.open_popup}
                    onAfterOpen={this.afterOpenModal}
                    shouldCloseOnOverlayClick={false}
                >
                    <div className="modal-header">
                        <h2 className="header-title">Cấu hình máy tính</h2>
                        <button onClick={this._saveImageToDevice}>
                            Tải ảnh
                            <i className={`fa ${this.state.saving ? "fa-spinner" : "fa-save"}`}></i>
                        </button>
                        <i className="fa fa-close" onClick={this._closeModal}></i>
                    </div>
                    <div className="content-image">
                        <div className="modal-body" id="custom-save-image-buildpc">
                            {this.state.image_content}
                        </div>
                    </div>
                </Modal>
            </React.Fragment>
        );
    }
}

// create container
import { connect } from 'react-redux';

const mapStateToProps = state => ({
    data_product_type : state.ProductTypeReducer
});

const mapDispatchToProps = dispatch => ({
    
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(SaveImageConfigBuildPcComponent);
