import React from 'react';
import Select from 'react-select';

import Axios from 'axios';

class DangKyBaoHanhComponent extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            input_imei: '',
            CustomerAdd: '',
            CustomerMobile: '',
            CustomerName: '',
            infos: {},
            fetching: false,
            success: "",
            errorMsg: '',
            hts_errors: [],
            errphone: "",
            gui_theo: "imei", // kien-hăng
            package_name: '',
            package_total: 1,
            package_address: '',
            package_add_nhaxe: ''
        }
    }

    async componentDidMount() {
        document.body.classList.add('compare_loading');
        try {
            let response = await Axios.get(
                '/wp-json/rest_api/v1/get-error-hts'
            );
            
            this.setState({
                hts_errors: response.data.data ? response.data.data : []
            })
        } catch (err) {
            console.log(err);
        } finally {
            document.body.classList.remove('compare_loading');
        }
    }

    _handleChangeInputPhone = async(event)=> {
        let data = this.state;
        data.success = '';
        data.errphone = '';
        data.CustomerMobile = event.target.value;
        if ( !/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/.test(event.target.value.trim()) ) {
            data.errphone = "Số điện thoại không hợp lệ";
            this.setState(data)
        } else {
            // lấy thông tin khách hàng
            this.setState(data);
            await this._getCustomer( event.target.value.trim() );
        }
    }

    _searchCustomerInfo = async()=> {
        let data = this.state;
        data.success = '';
        data.errphone = '';
        if ( !/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/.test(data.CustomerMobile.trim()) ) {
            data.errphone = "Số điện thoại không hợp lệ";
            this.setState(data)
        } else {
            // lấy thông tin khách hàng
            this.setState(data);
            await this._getCustomer( data.CustomerMobile.trim() );
        }
    }

    _getCustomer = async (phone)=> {
        document.body.classList.add('compare_loading');
        try {
            let response = await Axios.post(
                '/wp-json/rest_api/v1/get-customer-info-hts', 
                {
                    "phone_number": phone
                }
            );
            
            // let returnDate = new Date();
            // returnDate.setDate( returnDate.getDate() + 15 );
            // if( this.state.gui_theo == 'kien-hang' ) {
            //     let _infos = this.state.infos;
            //     _infos['k'] = {
            //         "Assignedby":"", 
            //         "Description":"", 
            //         "ErrorCode":"", 
            //         "ErrorDescription":"",
            //         "ErrorID":-1, 
            //         "Imei": 'k', 
            //         "ItemCode": 'KIEN.HANG', 
            //         "Priorityid":"1",	
            //         "Requestdate": parseInt(new Date().getTime()/1000),
            //         "Returndate": parseInt(returnDate.getTime()/1000),
            //         "ItemName": "Nhận theo kiện hàng"
            //     }
            // }
            
            if( response.data.data.info ) {
                let info = response.data.data.info;
                let returnDate = new Date();
                returnDate.setDate( returnDate.getDate() + 15 );
                this.setState({
                    errorMsg: '',
                    CustomerAdd: info.Address,
                    CustomerMobile: phone,
                    CustomerName: info.Name.replace('.' + info.Mobile, ''),
                    CreatedDate: parseInt(new Date().getTime()/1000), 
                    Returndate: parseInt(returnDate.getTime()/1000),
                    Description: '',
                });
            } 
        } catch (err) {

        } finally {
            document.body.classList.remove('compare_loading');
        }
    }

    _handleChangeInput = (event)=> {
        let data = this.state;
        data.success = '';
        data[event.target.name] = event.target.value;
        this.setState(data)
    }

    _handleSubmitForm = async()=> {
        let data = this.state;
        if( data.fetching ) return false;
        data.errorMsg = '';
        this.setState(data);
        
        if( data.CustomerAdd.trim() == '' ) {
            data.errorMsg = 'Vui lòng cho biết địa chỉ của bạn';
            this.setState(data);
            return false;
        }
        if( data.CustomerMobile.trim() == '' ) {
            data.errorMsg = 'Vui lòng cho biết số điện thoại của bạn';
            this.setState(data);
            return false;
        }
        if( data.CustomerName.trim() == '' ) {
            data.errorMsg = 'Vui lòng cho biết họ tên của bạn';
            this.setState(data);
            return false;
        }
        if( Object.keys(data.infos).length == 0 ) {
            data.errorMsg = 'Vui lòng nhập sản phẩm lỗi';
            this.setState(data);
            return false;
        }
        let keys = Object.keys(data.infos);
        for( let i = 0; i < keys.length; i++ ) {
            if( data.infos[keys[i]].ErrorID == -1 ) {
                data.errorMsg = 'Vui lòng chọn mã lỗi cho sản phẩm: ' + data.infos[keys[i]].ItemName ;
                this.setState(data);
                return false;
            }
        }

        let dataPost = {...this.state};
        let htsErr = [...dataPost.hts_errors];
        try {
            document.body.classList.add('compare_loading');
            this.setState({
                fetching: true
            });
            
            delete(dataPost.hts_errors)
            let response = await Axios.post('/wp-json/rest_api/v1/add-bao-hanh-hts',
                dataPost
            );
            
            if( response.data.success ) {
                this.setState({
                    // input_imei: '',
                    // CustomerAdd: '',
                    // CustomerMobile: '',
                    // CustomerName: '',
                    // infos: {},
                    // fetching: false,
                    // errorMsg: '',
                    // hts_errors: [],
                    success: response.data.data.code,
                    hts_errors: htsErr
                });
            } else {
                this.setState({
                    errorMsg: response.data.data.msg ? response.data.data.msg : "Đã có lỗi xảy ra. Vui lòng thử lại hoặc liên hệ hotline để được hỗ trợ!",
                    hts_errors: htsErr
                })
            }
        } catch (err) {
            this.setState({
                errorMsg: err.message ? err.message : "Đã có lỗi xảy ra. Vui lòng thử lại hoặc liên hệ hotline để được hỗ trợ!",
                hts_errors: htsErr
            })
        } finally {
            document.body.classList.remove('compare_loading');
        }
    }

    _searchImei = async(_imei = '') => {
        if( this.state.input_imei.trim() == '' || _imei.trim() == '' ) return false;

        let imei = this.state.input_imei.trim() == '' ? _imei : this.state.input_imei.trim();
        document.body.classList.add('compare_loading');
        try {
            let response = await Axios.post(
                '/wp-json/rest_api/v1/get-info-by-imei-hts', 
                {
                    "Imei": imei
                }
            );
            if( response.data.data.info && response.data.data.info.length > 0 ) {
                let info = response.data.data.info[0];
                
                let _infos = this.state.infos;

                if( this.state.CustomerMobile != '' &&  this.state.CustomerMobile != info.Mobile ) {
                    alert('Bạn đang nhập IMEI thuộc về 1 đơn hàng khác, chúng tôi sẽ hủy thông tin hiện tại để lưu thông tin mới.');
                    _infos = {};
                }
                let returnDate = new Date();
                returnDate.setDate( returnDate.getDate() + 15 );
                _infos[imei] = {
                    "Assignedby":"", 
                    "Description":"", 
                    "ErrorCode":"", 
                    "ErrorDescription":"",
                    "ErrorID":-1, 
                    "Imei": imei, 
                    "ItemCode": info.ItemCode, 
                    "Priorityid":"1",	
                    "Requestdate": parseInt(new Date().getTime()/1000),
                    "Returndate": parseInt(returnDate.getTime()/1000),
                    "ItemName": info.ItemName
                }
                
                this.setState({
                    errorMsg: '',
                    CustomerAdd: info.Address,
                    CustomerMobile: info.Mobile,
                    CustomerName: info.CusName.replace('.' + info.Mobile, ''),
                    CreatedDate: parseInt(new Date().getTime()/1000), 
                    Returndate: parseInt(returnDate.getTime()/1000),
                    Description: '',
                    infos: _infos
                });
            } else {
                this.setState({
                    errorMsg: "Chúng tôi không tìm thấy thông tin với IMEI được cung cấp"
                })
            }
        } catch (err) {
            console.log(err);
            this.setState({
                errorMsg: err.message
            })
        } finally {
            document.body.classList.remove('compare_loading');
        }
    }

    _renderErrorCode = (imei)=> {
        let hts_errors = this.state.hts_errors;
        let _infos = this.state.infos;

        const optHTS = [];
        optHTS.push({ label: 'Chọn mã lỗi', value: '' });
        for( let i = 0; i < hts_errors.length; i++ ) {
            optHTS.push({ label: hts_errors[i].Name, value: hts_errors[i].ID });
        }

        return(
            <div className="form-group">
                <label htmlFor={imei}>Mã lỗi<span style={{color: 'red'}}>*</span></label>
                <Select options={optHTS} name={imei} placeholder="Chọn mã lỗi" className="select2"
                    onChange={ (values) => {
                        if( values.value == '' ) {
                            _infos[imei].ErrorID = -1;
                            _infos[imei].ErrorCode = '';
                        } else {
                            _infos[imei].ErrorID = values.value;
                            _infos[imei].ErrorCode = values.label;
                        }
                        this.setState({
                            infos: _infos
                        })
                    } }
                />
            </div>
        )
    }

    _handleSubmitFormKienHang = async()=> {
        let data = this.state;
        if( data.fetching ) return false;
        data.errorMsg = '';
        this.setState(data);
        if( data.CustomerMobile.trim() == '' || !/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/.test(data.CustomerMobile.trim())) {
            data.errorMsg = 'Vui lòng cho biết số điện thoại của bạn';
            this.setState(data);
            return false;
        }
        if( data.CustomerName.trim() == '' ) {
            data.errorMsg = 'Vui lòng cho biết họ tên của bạn';
            this.setState(data);
            return false;
        }

        if( data.CustomerAdd.trim() == '' ) {
            data.errorMsg = 'Vui lòng cho biết địa chỉ của bạn';
            this.setState(data);
            return false;
        }

        if( data.package_total <= 0 ) {
            data.errorMsg = 'Vui lòng cho biết số lượng kiện hàng';
            this.setState(data);
            return false;
        }

        if( data.package_address == '' ) {
            data.errorMsg = 'Vui lòng cho biết địa chỉ nhận kiện hàng tại HCM';
            this.setState(data);
            return false;
        }

        if( data.package_add_nhaxe == '' ) {
            data.errorMsg = 'Vui lòng cho biết mã biên nhận của nhà xe';
            this.setState(data);
            return false;
        }
        
        if( Object.keys(data.infos).length == 0 ) {
            data.errorMsg = 'Vui lòng nhập sản phẩm lỗi';
            this.setState(data);
            return false;
        }

        if( data.infos['k'].Description.trim() == '' ) {
            data.errorMsg = 'Vui lòng cung cấp chi tiết về kiện hàng (tên sản phẩm, số lượng..)' ;
            this.setState(data);
            return false;
        }

        if( data.infos['k'].ErrorID == -1 ) {
            data.errorMsg = 'Vui lòng chọn mã lỗi cho kiện hàng ' ;
            this.setState(data);
            return false;
        }

        let dataPost = {...this.state};
        let htsErr = [...dataPost.hts_errors];
        
        try {
            document.body.classList.add('compare_loading');
            this.setState({
                fetching: true
            });
            
            delete(dataPost.hts_errors)
            let response = await Axios.post('/wp-json/rest_api/v1/add-bao-hanh-hts',
                dataPost
            );
            
            if( response.data.success ) {
                let returnDate = new Date();
                returnDate.setDate( returnDate.getDate() + 15 );
                let _infos = this.state.infos;
                _infos['k'] = {
                    "Assignedby":"", 
                    "Description":"", 
                    "ErrorCode":"", 
                    "ErrorDescription":"",
                    "ErrorID":-1, 
                    "Imei": 'k', 
                    "ItemCode": 'KIEN.HANG', 
                    "Priorityid":"1",	
                    "Requestdate": parseInt(new Date().getTime()/1000),
                    "Returndate": parseInt(returnDate.getTime()/1000),
                    "ItemName": "Nhận theo kiện hàng"
                }
                this.setState({
                    input_imei: '',
                    CustomerAdd: '',
                    CustomerMobile: '',
                    CustomerName: '',
                    infos: _infos,
                    fetching: false,
                    errorMsg: '',
                    errphone: "",
                    gui_theo: "kien-hang", // kien-hăng
                    package_name: '',
                    package_total: 1,
                    package_address: '',
                    package_add_nhaxe: '',
                    hts_errors: htsErr,
                    success: response.data.data.code
                });
            } else {
                this.setState({
                    errorMsg: response.data.data.msg ? response.data.data.msg : "Đã có lỗi xảy ra. Vui lòng thử lại hoặc liên hệ hotline để được hỗ trợ!",
                    hts_errors: htsErr,
                    fetching: false,
                });
            }
        } catch (err) {
            this.setState({
                errorMsg: err.message ? err.message : "Đã có lỗi xảy ra. Vui lòng thử lại hoặc liên hệ hotline để được hỗ trợ!",
                hts_errors: htsErr,
                fetching: false,
            });
        } finally {
            document.body.classList.remove('compare_loading');
        }
    }

    _changeTab = (tabname)=> {
        if( tabname == 'imei' ) {
            this.setState({
                input_imei: '',
                CustomerAdd: '',
                CustomerMobile: '',
                CustomerName: '',
                infos: {},
                fetching: false,
                success: "",
                errorMsg: '',
                errphone: "",
                gui_theo: "imei",
                package_name: '',
                package_total: 0,
                package_address: '',
                package_add_nhaxe: ''
            })
        } else {
            let returnDate = new Date();
            returnDate.setDate( returnDate.getDate() + 15 );
            let _infos = this.state.infos;
            _infos['k'] = {
                "Assignedby":"", 
                "Description":"", 
                "ErrorCode":"", 
                "ErrorDescription":"",
                "ErrorID":-1, 
                "Imei": 'k', 
                "ItemCode": 'KIEN.HANG', 
                "Priorityid":"1",	
                "Requestdate": parseInt(new Date().getTime()/1000),
                "Returndate": parseInt(returnDate.getTime()/1000),
                "ItemName": "Nhận theo kiện hàng"
            }
            this.setState({
                input_imei: '',
                CustomerAdd: '',
                CustomerMobile: '',
                CustomerName: '',
                infos: _infos,
                fetching: false,
                success: "",
                errorMsg: '',
                errphone: "",
                gui_theo: "kien-hang",
                package_name: '',
                package_total: 1,
                package_address: '',
                package_add_nhaxe: ''
            })
        }
    }

    _handleKeyDown = async (e)=> {
        if (e.key === 'Enter') {
            await this._searchImei( e.target.value.trim() );
        }
    }

    render() {
        let data = this.state;
        let infos = this.state.infos;
        let infoKeys = Object.keys( infos );

        return (
            <div className="form-container">
                <div className="header">
                    <h3>YÊU CẦU LẤY HÀNG LỖI TẬN NƠI TẠI HỒ CHÍ MINH</h3>
                    <p>Sau khi nhận được yêu cầu và xem xét thông tin quý khách cung cấp, nếu hợp lệ theo điều khoản của chúng tôi, nhân viên của TinHocNgoiSao sẽ liên hệ với quý khách trong thời gian sớm nhất.</p>
                    <p><i>Quý khách có thể nhập nhiều imei khác nhau trên cùng 1 đơn hàng. Nếu quý khách muốn đổi số điện thoại vui lòng thay đổi sau khi đã nhập hết imei.</i></p>
                    <p><i>Nếu sản phẩm lỗi thuộc <strong>2 đơn hàng khác nhau</strong>, quý khách vui lòng <strong>không tạo chung 1 yêu cầu</strong> để tránh nhầm lẫn thông tin</i></p>
                </div>

                <div className="form-body">
                    <div className="header">
                        <button onClick={ ()=> this._changeTab('imei') } className={ `btn btn-small ${ data.gui_theo == 'imei' ? 'active' : '' }` }>Theo IMEI sản phẩm</button>
                        <button onClick={ ()=> this._changeTab('kien-hang') } className={ `btn btn-small ${ data.gui_theo != 'imei' ? 'active' : '' }` }>Theo Kiện Hàng</button>
                    </div>
                </div>

                {
                    data.gui_theo == 'imei' ?
                    // IMEI
                    <div className="form-content">
                        <h3>Gửi sản phẩm lỗi</h3>
                        <div className="form-group form-imei">
                            <label htmlFor="input_imei">Imei in trên sản phẩm<span style={{color: 'red'}}>*</span></label>
                            <input name="input_imei" value={data.input_imei} onChange={this._handleChangeInput} onKeyDown={this._handleKeyDown}/>
                            <button onClick={this._searchImei}><i className="fa fa-search"></i></button>
                        </div>

                        <div className="form-group form-imei">
                            <label htmlFor="CustomerMobile">Số điện thoại<span style={{color: 'red'}}>*</span></label>
                            <input name="CustomerMobile" value={data.CustomerMobile} onChange={this._handleChangeInputPhone}/>
                            <button onClick={this._searchCustomerInfo}><i className="fa fa-search"></i></button>
                            {
                                data.errphone && <label style={{color: 'red', width: '100%'}}>Số điện thoại không hợp lệ</label>
                            }
                        </div>

                        <div className="form-group">
                            <label htmlFor="CustomerName">Họ tên<span style={{color: 'red'}}>*</span></label>
                            <input name="CustomerName" value={data.CustomerName} onChange={this._handleChangeInput}/>
                        </div>
                        {/* <div className="form-group">
                            <label htmlFor="CustomerMobile">Số điện thoại</label>
                            <input readOnly={true} name="CustomerMobile" value={data.CustomerMobile} onChange={()=>null}/>
                        </div> */}
                        <div className="form-group">
                            <label htmlFor="CustomerAdd">Địa chỉ<span style={{color: 'red'}}>*</span></label>
                            <input name="CustomerAdd" value={data.CustomerAdd} onChange={this._handleChangeInput}/>
                        </div>

                        {
                            infoKeys && infoKeys.length > 0 &&
                            infoKeys.map((item, index)=> {
                                let info = infos[item];
                                return (
                                    <fieldset key={item}>
                                        <legend>{info.Imei}</legend>
                                        <div className="form-group">
                                            <label htmlFor="">Tên sản phẩm</label>
                                            <input readOnly={true} name="" value={ info.ItemName} onChange={()=>null}/>
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="">Imei</label>
                                            <input readOnly={true} name="" value={ info.Imei} onChange={()=>null}/>
                                        </div>
                                        {
                                            this._renderErrorCode(item)
                                        }
                                        <div className="form-group">
                                            <label htmlFor="noidung">Mô tả lỗi</label>
                                            <textarea name="noidung" value={ info.ErrorDescription} onChange={(e)=> {
                                                let _infos = this.state.infos;
                                                _infos[item].ErrorDescription = e.target.value;
                                                this.setState({
                                                    infos: _infos
                                                })

                                            }}/>
                                        </div>
                                        <button onClick={()=> {
                                            let _infos = this.state.infos;
                                            delete _infos[item];
                                            this.setState({
                                                errorMsg: '',
                                                infos: _infos
                                            })
                                        }}>Xóa sản phẩm này</button>
                                    </fieldset>
                                )
                            })
                        }
                        
                        {
                            data.errorMsg != '' &&
                            <div className="form-group">
                                <p style={{color: 'red'}}>Lỗi: <i>{data.errorMsg}</i></p>
                            </div>
                        }
                        {
                            data.success != '' &&
                            <div className="form-group">
                                <p style={{color: 'green'}}>Thông báo: 
                                    <i>
                                        Mã phiếu biên nhận của quý khách là {data.success}. <br/>
                                        Chúng tôi sẽ kiểm tra và liên hệ với quý khách trong thời gian sớm nhất.<br/>
                                        Ngoài ra để xem lại thông tin bảo hành quý khách truy cập tại <a href="/kiem-tra-bao-hanh">KIỂM TRA BẢO HÀNH</a>
                                    </i>
                                </p>
                            </div>
                        }
                        {
                            infoKeys && infoKeys.length > 0 &&
                            <div className="form-group-btn">
                                <button type="button" onClick={this._handleSubmitForm}>{ !data.fetching ? "Gửi yêu cầu" : "Đang gửi yêu cầu..." }</button>
                            </div>
                        }
                    </div>
                    :
                    // KIỆN HÀNG
                    <div className="form-content">
                        <h3>Gửi kiện hàng lỗi</h3>
                        <div className="form-group form-imei">
                            <label htmlFor="CustomerMobile">Số điện thoại<span style={{color: 'red'}}>*</span></label>
                            <input name="CustomerMobile" value={data.CustomerMobile} onChange={this._handleChangeInputPhone}/>
                            <button onClick={this._searchCustomerInfo}><i className="fa fa-search"></i></button>
                            {
                                data.errphone && <label style={{color: 'red', width: "100%"}}>Số điện thoại không hợp lệ</label>
                            }
                        </div>

                        <div className="form-group">
                            <label htmlFor="CustomerName">Họ tên<span style={{color: 'red'}}>*</span></label>
                            <input name="CustomerName" value={data.CustomerName} onChange={this._handleChangeInput}/>
                        </div>
                        {/* <div className="form-group">
                            <label htmlFor="CustomerMobile">Số điện thoại</label>
                            <input name="CustomerMobile" value={data.CustomerMobile} onChange={()=>null}/>
                        </div> */}
                        <div className="form-group">
                            <label htmlFor="CustomerAdd">Địa chỉ khách hàng<span style={{color: 'red'}}>*</span></label>
                            <input name="CustomerAdd" value={data.CustomerAdd} onChange={this._handleChangeInput}/>
                        </div>

                        <div className="form-group">
                            <label htmlFor="package_total">Số lượng kiện hàng<span style={{color: 'red'}}>*</span></label>
                            <input type="number" min="0" name="package_total" value={data.package_total} onChange={this._handleChangeInput}/>
                        </div>

                        <div className="form-group">
                            <label htmlFor="package_address">Địa chỉ chỗ lấy tại HCM<span style={{color: 'red'}}>*</span></label>
                            <input name="package_address" value={data.package_address} onChange={this._handleChangeInput}/>
                        </div>

                        <div className="form-group">
                            <label htmlFor="package_add_nhaxe">Mã biên nhận của nhà xe<span style={{color: 'red'}}>*</span></label>
                            <input type="text" name="package_add_nhaxe" value={data.package_add_nhaxe} onChange={this._handleChangeInput}/>
                        </div>

                        <fieldset>
                            <legend>Kiện hàng muốn gửi</legend>
                            <div className="form-group">
                                <label htmlFor="package_name">Tên kiện hàng<span style={{color: 'red'}}>*</span></label>
                                <input name="package_name" value={ data.infos['k'].ItemName } onChange={()=>null}/>
                            </div>
                            <div className="form-group">
                                <label htmlFor="noidung">Chi tiết kiện hàng<span style={{color: 'red'}}>*</span></label>
                                <textarea name="noidung" value={ data.infos['k'].Description } onChange={(e)=> {
                                    let _infos = this.state.infos;
                                    _infos['k'].Description = e.target.value;
                                    this.setState({
                                        infos: _infos
                                    })

                                }}/>
                            </div>
                            {
                                this._renderErrorCode('k')
                            }
                            <div className="form-group">
                                <label htmlFor="noidung">Mô tả lỗi</label>
                                <textarea name="noidung" value={ data.infos['k'].ErrorDescription } onChange={(e)=> {
                                    let _infos = this.state.infos;
                                    _infos['k'].ErrorDescription = e.target.value;
                                    this.setState({
                                        infos: _infos
                                    })

                                }}/>
                            </div>
                        </fieldset>
                        
                        {
                            data.errorMsg != '' &&
                            <div className="form-group">
                                <p style={{color: 'red'}}>Lỗi: <i>{data.errorMsg}</i></p>
                            </div>
                        }
                        {
                            data.success != '' &&
                            <div className="form-group">
                                <p style={{color: 'green'}}>Thông báo: 
                                    <i>
                                        Mã phiếu biên nhận của quý khách là {data.success}. <br/>
                                        Chúng tôi sẽ kiểm tra và liên hệ với quý khách trong thời gian sớm nhất.<br/>
                                        Ngoài ra để xem lại thông tin bảo hành quý khách truy cập tại <a href="/kiem-tra-bao-hanh">KIỂM TRA BẢO HÀNH</a>
                                    </i>
                                </p>
                            </div>
                        }
                        {
                            infoKeys && infoKeys.length > 0 &&
                            <div className="form-group-btn">
                                <button type="button" onClick={this._handleSubmitFormKienHang}>{ !data.fetching ? "Gửi yêu cầu" : "Đang gửi yêu cầu..." }</button>
                            </div>
                        }
                    </div>
                }


                
            </div>
        );
    }
}

export default DangKyBaoHanhComponent;