import React, { Component } from 'react';

class HeaderComponent extends Component {

    constructor(props) {
        super(props);
        this.state = {
            ma_sp : '',
            ten_sp: ''
        }
    }

    _handle_masp = (e)=> {
        this.setState({
            ma_sp: e.target.value,
            ten_sp: ''
        });
        this.props.setValueSearch({
            ma_sp : e.target.value,
            ten_sp: ''
        });
    }

    _handle_tensp = (e)=> {
        this.setState({
            ten_sp: e.target.value,
            ma_sp: ''
        });
        this.props.setValueSearch({
            ma_sp : '',
            ten_sp: e.target.value
        });
    }

    render() {
        let date = new Date();
        return (
            <div className="headers">
                <h1 className="title-date">
                    {`Bảng giá bán ngày: ${date.getDate()}-${date.getMonth()+1}-${date.getFullYear()}`}
                </h1>
                <div className="table-header">
                    <div className="tb-col-1">
                        <h3>Mã SP</h3>
                        <input className="input-search" id="ma_sp" value={this.state.ma_sp} onChange={this._handle_masp} placeholder="Search mã SP"/>
                        <i className="fa fa-search"></i>
                    </div>
                    <div className="tb-col-2">
                        <h3>Thiết bị & mô tả</h3>
                        <input className="input-search" id="ten_sp" value={this.state.ten_sp} onChange={this._handle_tensp} placeholder="Search tên sản phẩm"/>
                        <i className="fa fa-search"></i>
                    </div>
                    <div className="tb-col-3">
                        <h3>Đơn giá</h3>
                    </div>
                    <div className="tb-col-4">
                        <h3>Bảo hành</h3>
                    </div>
                    {/* <div className="tb-col-5">
                        <h3>Tình trạng</h3>
                    </div> */}
                </div>
            </div>
        )
    }
}

export default HeaderComponent;