import React from 'react';
import axios from 'axios';
import FormData from 'form-data';
import Select from 'react-select';
import { hangcu_home_ajax } from '../../variable/variables';
const pattPhone = new RegExp("(09|03|07|08|05)+([0-9]{8}$)");
const pattEmail = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
class ListAddressComponent extends React.Component {

    constructor( props ) {
        super(props);
        this.state = {
            address : {},
            showAddNew: false,
            addNewObject: {
                address_is_default: "off",
                billing_address_1: "",
                billing_address_2: "",
                billing_address_2_name: "",
                billing_city: "",
                billing_city_name: "",
                billing_email: "",
                billing_last_name: "",
                billing_phone: "",
                billing_state: "",
                billing_state_name: "",
                full_address: "",
                errMsg: ''
            },

            showEdit: false,
            editObject: {
                key_edit_address: '',
                address_is_default: "off",
                billing_address_1: "",
                billing_address_2: "",
                billing_address_2_name: "",
                billing_city: "",
                billing_city_name: "",
                billing_email: "",
                billing_last_name: "",
                billing_phone: "",
                billing_state: "",
                billing_state_name: "",
                full_address: "",
                errMsg: ''
            },

            showDelete: false,
            deleteKey : '',
            deleleErr : '',

            devvn_cities: [],
            devvn_district: [],
            devvn_wards: [],
        }
    }

    async componentDidMount() {
        await this._getListAddress();
    }

    _getListAddress = async()=> {
        try {
            document.body.classList.add('hangcu_loading');
            var data = new FormData();
            data.append('action', 'hc_get_list_address');

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );
            
            if( response.data.success ) {
                this.setState({
                    address: response.data.data.address
                });
                if( response.data.data.cities ) {
                    let rsData = [];
                    let citienames = response.data.data.cities;
                    let cities = Object.keys(citienames);
                    rsData.push({
                        value: '',
                        label: 'Chọn tỉnh/thành phố'
                    });
                    for( let i = 0; i < cities.length; i++ ) {
                        rsData.push( {
                            value: cities[i],
                            label: citienames[cities[i]]
                        } )
                    }
                    
                    this.setState({
                        devvn_cities: rsData
                    });
                }
            } else {
                
            }

        } catch (err) {
            console.log(err);
        } finally {
            document.body.classList.remove('hangcu_loading');
        }
    }

    _getListDistrict = async(matp)=> {
        try {
            document.body.classList.add('hangcu_loading');
            var data = new FormData();
            data.append('action', 'load_diagioihanhchinh');
            data.append('matp', matp);

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );
            
            if( response.data.success ) {
                let districtname = response.data.data;
                let rsData = [];
                rsData.push({
                    value: '',
                    label: 'Chọn quận/huyện'
                });
                for( let i = 0; i < districtname.length; i++ ) {
                    rsData.push( {
                        value: districtname[i].maqh,
                        label: districtname[i].name
                    } )
                }
                
                this.setState({
                    devvn_district: rsData
                });
                return rsData;
            } else {
                
            }

        } catch (err) {
            console.log(err);
        } finally {
            document.body.classList.remove('hangcu_loading');
        }
        return [];
    }

    _getListWards = async(maqh)=> {
        try {
            document.body.classList.add('hangcu_loading');
            var data = new FormData();
            data.append('action', 'load_diagioihanhchinh');
            data.append('maqh', maqh);

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );
            
            if( response.data.success ) {
                let wards = response.data.data;
                
                let rsData = [];
                rsData.push({
                    value: '',
                    label: 'Chọn phường/xã'
                });
                for( let i = 0; i < wards.length; i++ ) {
                    rsData.push( {
                        value: wards[i].xaid,
                        label: wards[i].name
                    } )
                }
                
                this.setState({
                    devvn_wards: rsData
                });
                return rsData
            } else {
                
            }

        } catch (err) {
            console.log(err);
        } finally {
            document.body.classList.remove('hangcu_loading');
        }
        return [];
    }

    _renderListAddress = ()=> {
        let {
            address
        } = this.state;
        let keys = Object.keys( address );
        
        let rs = [];
        for( let i = 0; i < keys.length; i++ ) {
            let item = address[keys[i]];
            rs.push(
                <div key={keys[i]} className={`address-item ${item.address_is_default == 'on' ? "active" : ''}`}>
                    <p className="full-name">
                        { item.billing_last_name }
                        {
                            item.address_is_default == 'on' &&
                            <span className="default">
                                <svg stroke="currentColor" fill="currentColor" strokeWidth="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z"></path></svg>
                                Địa chỉ mặc định</span>
                        }
                    </p>
                    <p>
                        <span>Địa chỉ: </span>
                        {item.full_address}
                    </p>
                    <p>
                        <span>Điện thoại: </span>
                        {item.billing_phone}
                    </p>
                    <div className="group-button">
                        <a className="edit" href="#" onClick={()=> this._editAddress(keys[i])}>Chỉnh sửa</a>
                        {
                            item.address_is_default != 'on' &&
                            <a className="delete" href="#" onClick={()=> this._deleteAddress(keys[i])}>Xóa</a>
                        }
                    </div>
                </div>
            );
        }

        return rs;
    }

    _showFormAddNew = ()=> {
        this.setState({
            addNewObject: {
                address_is_default: "off",
                billing_address_1: "",
                billing_address_2: "",
                billing_address_2_name: "",
                billing_city: "",
                billing_city_name: "",
                billing_email: "",
                billing_last_name: "",
                billing_phone: "",
                billing_state: "",
                billing_state_name: "",
                full_address: ""
            },
            showAddNew: true
        })
    }

    _handleChangeCity = async(e)=> {
        let {
            addNewObject, showAddNew, showEdit, editObject
        } = this.state;
        if( showAddNew ) {
            addNewObject.billing_state = e.value;
            addNewObject.billing_state_name = e.label;
            this.setState({
                addNewObject,
                devvn_district: []
            });
            if( e.value == '' ) return;
        } else if (showEdit) {
            editObject.billing_state = e.value;
            editObject.billing_state_name = e.label;
            this.setState({
                editObject,
                devvn_district: []
            });
        }

        // get district
        await this._getListDistrict( e.value );
    }

    _handleChangeDistrict = async(e)=> {
        let {
            addNewObject, showAddNew, showEdit, editObject
        } = this.state;
        if( showAddNew ) {
            addNewObject.billing_city = e.value;
            addNewObject.billing_city_name = e.label;

            this.setState({
                addNewObject,
                devvn_wards: []
            });
            if( e.value == '' ) return;
        } else if( showEdit ) {
            editObject.billing_city = e.value;
            editObject.billing_city_name = e.label;

            this.setState({
                editObject,
                devvn_wards: []
            });
            if( e.value == '' ) return;
        }

        // get wards
        await this._getListWards( e.value );
    }

    _handleChangeInputAddress = (e)=> {
        if( this.state.showAddNew ) {
            let addNewObject = this.state.addNewObject;
            addNewObject[e.target.name] = e.target.value;

            this.setState({
                addNewObject
            });
        } else {
            let editObject = this.state.editObject;
            editObject[e.target.name] = e.target.value;

            this.setState({
                editObject
            });
        }
        
    }

    _handleChangeWard = (e)=> {
        let {
            addNewObject, showAddNew, showEdit, editObject
        } = this.state;
        if( showAddNew ) {
            addNewObject.billing_address_2 = e.value;
            addNewObject.billing_address_2_name = e.label;
            this.setState({
                addNewObject
            });
        } else if( showEdit ) {
            editObject.billing_address_2 = e.value;
            editObject.billing_address_2_name = e.label;
            this.setState({
                editObject
            });
        }
    }

    _handleCheckAddressDefault = (e)=> {
        let {
            addNewObject, showAddNew, editObject, showEdit
        } = this.state;
        if( showAddNew ) {
            addNewObject.address_is_default = addNewObject.address_is_default == 'on' ? 'off' : 'on';
            this.setState({
                addNewObject
            });
        } else if( showEdit ) {
            editObject.address_is_default = editObject.address_is_default == 'on' ? 'off' : 'on';
            this.setState({
                editObject
            });
        }
    }

    _handleAddnewAddress = async()=> {
        let addNewObject = this.state.addNewObject;
        
        if( addNewObject.billing_last_name.trim() == '' ) {
            addNewObject.errMsg = 'Vui lòng nhập họ tên';
            this.setState({
                addNewObject
            });
            return false;
        } else if( !pattPhone.test(addNewObject.billing_phone.trim()) ) {
            addNewObject.errMsg = 'Số điện thoại không hợp lệ';
            this.setState({
                addNewObject
            });
            return false;
        } else if( !pattEmail.test(addNewObject.billing_email.trim()) ) {
            addNewObject.errMsg = 'Email không hợp lệ';
            this.setState({
                addNewObject
            });
            return false;
        } else if( addNewObject.billing_state.trim() == '' ) {
            addNewObject.errMsg = 'Vui lòng chọn Tỉnh/Thành phố';
            this.setState({
                addNewObject
            });
            return false;
        } else if( addNewObject.billing_city.trim() == '' ) {
            addNewObject.errMsg = 'Vui lòng chọn Quận/Huyện';
            this.setState({
                addNewObject
            });
            return false;
        } else if( addNewObject.billing_address_2.trim() == '' ) {
            addNewObject.errMsg = 'Vui lòng chọn Phường/Xã';
            this.setState({
                addNewObject
            });
            return false;
        } else if( addNewObject.billing_state.trim() == '' ) {
            addNewObject.errMsg = 'Vui lòng chọn Tỉnh/Thành phố';
            this.setState({
                addNewObject
            });
            return false;
        } else if( addNewObject.billing_address_1.trim() == '' ) {
            addNewObject.errMsg = 'Vui lòng chọn Tỉnh/Thành phố';
            this.setState({
                addNewObject
            });
            return false;
        }


        try {
            document.body.classList.add('hangcu_loading');
            let full_address = `${addNewObject.billing_address_1}, ${addNewObject.billing_address_2_name}, ${addNewObject.billing_city_name}, ${addNewObject.billing_state_name}`;
            var data = new FormData();
            data.append('action', 'hc_add_new_address');
            data.append('add_new_saved_address_field', true);
            data.append('billing_last_name', addNewObject.billing_last_name);
            data.append('billing_phone', addNewObject.billing_phone);
            data.append('billing_state', addNewObject.billing_state);
            data.append('billing_city', addNewObject.billing_city);
            data.append('billing_address_1', addNewObject.billing_address_1);
            data.append('billing_address_2', addNewObject.billing_address_2);
            data.append('billing_email', addNewObject.billing_email);
            data.append('address_is_default', addNewObject.address_is_default);
            data.append('full_address', full_address);

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );
            
            if( response.data.success ) {
                this.setState({
                    showAddNew: false,
                    addNewObject: {
                        address_is_default: "off",
                        billing_address_1: "",
                        billing_address_2: "",
                        billing_address_2_name: "",
                        billing_city: "",
                        billing_city_name: "",
                        billing_email: "",
                        billing_last_name: "",
                        billing_phone: "",
                        billing_state: "",
                        billing_state_name: "",
                        full_address: "",
                        errMsg: ''
                    }
                });
                await this._getListAddress();
            } else {
                
            }

        } catch (err) {
            console.log(err);
        } finally {
            document.body.classList.remove('hangcu_loading');
        }
    }

    _showAddNewAddress = ()=> {
        let addNewObject = this.state.addNewObject;

        return(
            <div id="popup-account">
                <div className="form-address">
                    <h3>Tạo địa chỉ mới</h3>
                    <span id="close-login-popup" className="electro-close-icon" onClick={()=> this.setState({ showAddNew: false })}></span>
                    <div className="form-add-new">
                        <div className="input-group full-name">
                            <label htmlFor="billing_last_name">Họ tên:</label>
                            <input type="text" placeholder="Họ tên" name="billing_last_name" value={addNewObject.billing_last_name} onChange={this._handleChangeInputAddress} />
                        </div>
                        <div className="input-group">
                            <label htmlFor="billing_phone">Số điện thoại:</label>
                            <input type="text" placeholder="Số điện thoại" name="billing_phone" value={addNewObject.billing_phone} onChange={this._handleChangeInputAddress} />
                        </div>
                        <div className="input-group">
                            <label htmlFor="billing_email">Email:</label>
                            <input type="text" placeholder="Email" name="billing_email" value={addNewObject.billing_email} onChange={this._handleChangeInputAddress} />
                        </div>
                        <div className="input-group">
                            <label htmlFor="billing_state">Tỉnh/thành phố:</label>
                            <Select className="select-address" name="billing_state" placeholder="Chọn Tỉnh/thành phố" 
                                options={this.state.devvn_cities} 
                                onChange={this._handleChangeCity} />
                        </div>
                        <div className="input-group">
                            <label htmlFor="billing_city">Quận/huyện:</label>
                            <Select className="select-address" name="billing_city" placeholder="Chọn Quận/huyện" options={this.state.devvn_district} onChange={this._handleChangeDistrict} />
                        </div>
                        <div className="input-group">
                            <label htmlFor="billing_address_2">Phường/xã:</label>
                            <Select className="select-address" name="billing_address_2" placeholder="Chọn Phường/xã" options={this.state.devvn_wards} onChange={this._handleChangeWard} />
                        </div>

                        <div className="input-group">
                            <label htmlFor="billing_address_1">Địa chỉ:</label>
                            <input type="text" placeholder="Địa chỉ" name="billing_address_1" value={addNewObject.billing_address_1} onChange={this._handleChangeInputAddress} />
                        </div>

                        <div className="input-group">
                            <p>
                                <input style={{ width: 20 }} type="checkbox" name="address_is_default" checked={addNewObject.address_is_default == 'on'}  onChange={this._handleCheckAddressDefault} />
                                <label htmlFor="address_is_default" onClick={this._handleCheckAddressDefault}>Đặt làm địa chỉ mặc định?</label>
                            </p>
                        </div>
                        {
                            addNewObject.errMsg &&
                            <div className="input-group" style={{width: '100%'}}>
                                <p className="error">
                                    { addNewObject.errMsg }
                                </p>
                            </div>
                        }
                    </div>
                    <div className="btns" style={{textAlign: 'right'}}>
                        <button type="button" className="btn btn-primary" onClick={this._handleAddnewAddress}>
                            <span>Xác nhận</span>
                        </button>
                    </div>
                </div>
            </div>
        )
    }

    // EDIT ADDRESS
    _editAddress = async(key)=> {
        let {
            address, editObject, devvn_cities
        } = this.state;

        let address_item = {...address[key]};
        if( !address_item ) return false;
        editObject = address_item;
        editObject.key_edit_address = key;

        // get city name
        for( let i = 0; i < devvn_cities.length; i++ ) {
            if( devvn_cities[i].value == editObject.billing_state ) {
                editObject.billing_state_name = devvn_cities[i].label;
                break;
            }
        }
        
        
        // get district
        let districts = await this._getListDistrict(editObject.billing_state);
        for( let i = 0; i < districts.length; i++ ) {
            if( districts[i].value == editObject.billing_city ) {
                editObject.billing_city_name = districts[i].label;
                break;
            }
        }
        // get ward
        let wards = await this._getListWards(editObject.billing_city);
        for( let i = 0; i < wards.length; i++ ) {
            if( wards[i].value == editObject.billing_address_2 ) {
                editObject.billing_address_2_name = wards[i].label;
                break;
            }
        }
        this.setState({
            showEdit: true,
            editObject
        })
    }

    _showEditAddress = ()=> {
        let editObject = this.state.editObject;
        
        return(
            <div id="popup-account">
                <div className="form-address">
                    <h3>Chỉnh sửa địa chỉ</h3>
                    <span id="close-login-popup" className="electro-close-icon" onClick={()=> this.setState({ showEdit: false })}></span>
                    <div className="form-add-new">
                        <div className="input-group full-name">
                            <label htmlFor="billing_last_name">Họ tên:</label>
                            <input type="text" placeholder="Họ tên" name="billing_last_name" value={editObject.billing_last_name} onChange={this._handleChangeInputAddress} />
                        </div>
                        <div className="input-group">
                            <label htmlFor="billing_phone">Số điện thoại:</label>
                            <input type="text" placeholder="Số điện thoại" name="billing_phone" value={editObject.billing_phone} onChange={this._handleChangeInputAddress} />
                        </div>
                        <div className="input-group">
                            <label htmlFor="billing_email">Email:</label>
                            <input type="text" placeholder="Email" name="billing_email" value={editObject.billing_email} onChange={this._handleChangeInputAddress} />
                        </div>
                        <div className="input-group">
                            <label htmlFor="billing_state">Tỉnh/thành phố:</label>
                            <Select className="select-address" name="billing_state" placeholder="Chọn Tỉnh/thành phố"
                                value={this.state.devvn_cities.filter(({value}) => value === editObject.billing_state)}
                                options={this.state.devvn_cities} 
                                onChange={this._handleChangeCity} />
                        </div>
                        <div className="input-group">
                            <label htmlFor="billing_city">Quận/huyện:</label>
                            <Select className="select-address" name="billing_city" placeholder="Chọn Quận/huyện" 
                                value={this.state.devvn_district.filter(({value}) => value === editObject.billing_city)}
                                options={this.state.devvn_district} 
                                onChange={this._handleChangeDistrict} />
                        </div>
                        <div className="input-group">
                            <label htmlFor="billing_address_2">Phường/xã:</label>
                            <Select className="select-address" name="billing_address_2" placeholder="Chọn Phường/xã" 
                                value={this.state.devvn_wards.filter(({value}) => value === editObject.billing_address_2)}
                                options={this.state.devvn_wards} 
                                onChange={this._handleChangeWard} />
                        </div>

                        <div className="input-group">
                            <label htmlFor="billing_address_1">Địa chỉ:</label>
                            <input type="text" placeholder="Địa chỉ" name="billing_address_1" value={editObject.billing_address_1} onChange={this._handleChangeInputAddress} />
                        </div>

                        <div className="input-group">
                            <p>
                                <input style={{ width: 20 }} type="checkbox" name="address_is_default" checked={editObject.address_is_default == 'on'}  onChange={this._handleCheckAddressDefault} />
                                <label htmlFor="address_is_default" onClick={this._handleCheckAddressDefault}>Đặt làm địa chỉ mặc định?</label>
                            </p>
                        </div>
                        {
                            editObject.errMsg &&
                            <div className="input-group" style={{width: '100%'}}>
                                <p className="error">
                                    { editObject.errMsg }
                                </p>
                            </div>
                        }
                    </div>
                    <div className="btns" style={{textAlign: 'right'}}>
                        <button type="button" className="btn btn-primary" onClick={this._handleEditAddress}>
                            <span>Xác nhận</span>
                        </button>
                    </div>
                </div>
            </div>
        )
    }

    _handleEditAddress = async()=> {
        let editObject = this.state.editObject;
        
        if( editObject.billing_last_name.trim() == '' ) {
            editObject.errMsg = 'Vui lòng nhập họ tên';
            this.setState({
                editObject
            });
            return false;
        } else if( !pattPhone.test(editObject.billing_phone.trim()) ) {
            editObject.errMsg = 'Số điện thoại không hợp lệ';
            this.setState({
                editObject
            });
            return false;
        } else if( !pattEmail.test(editObject.billing_email.trim()) ) {
            editObject.errMsg = 'Email không hợp lệ';
            this.setState({
                editObject
            });
            return false;
        } else if( editObject.billing_state.trim() == '' ) {
            editObject.errMsg = 'Vui lòng chọn Tỉnh/Thành phố';
            this.setState({
                editObject
            });
            return false;
        } else if( editObject.billing_city.trim() == '' ) {
            editObject.errMsg = 'Vui lòng chọn Quận/Huyện';
            this.setState({
                editObject
            });
            return false;
        } else if( editObject.billing_address_2.trim() == '' ) {
            editObject.errMsg = 'Vui lòng chọn Phường/Xã';
            this.setState({
                editObject
            });
            return false;
        } else if( editObject.billing_state.trim() == '' ) {
            editObject.errMsg = 'Vui lòng chọn Tỉnh/Thành phố';
            this.setState({
                editObject
            });
            return false;
        } else if( editObject.billing_address_1.trim() == '' ) {
            editObject.errMsg = 'Vui lòng chọn Tỉnh/Thành phố';
            this.setState({
                editObject
            });
            return false;
        }


        try {
            document.body.classList.add('hangcu_loading');
            let full_address = `${editObject.billing_address_1}, ${editObject.billing_address_2_name}, ${editObject.billing_city_name}, ${editObject.billing_state_name}`;
            var data = new FormData();
            data.append('action', 'hc_update_address');
            data.append('key_edit_address', editObject.key_edit_address);
            data.append('add_new_saved_address_field', true);
            data.append('billing_last_name', editObject.billing_last_name);
            data.append('billing_phone', editObject.billing_phone);
            data.append('billing_state', editObject.billing_state);
            data.append('billing_city', editObject.billing_city);
            data.append('billing_address_1', editObject.billing_address_1);
            data.append('billing_address_2', editObject.billing_address_2);
            data.append('billing_email', editObject.billing_email);
            data.append('address_is_default', editObject.address_is_default);
            data.append('full_address', full_address);

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );
            
            if( response.data.success ) {
                this.setState({
                    showEdit: false,
                    editObject: {
                        address_is_default: "off",
                        billing_address_1: "",
                        billing_address_2: "",
                        billing_address_2_name: "",
                        billing_city: "",
                        billing_city_name: "",
                        billing_email: "",
                        billing_last_name: "",
                        billing_phone: "",
                        billing_state: "",
                        billing_state_name: "",
                        full_address: "",
                        errMsg: ''
                    }
                });
                await this._getListAddress();
            } else {
                
            }

        } catch (err) {
            console.log(err);
        } finally {
            document.body.classList.remove('hangcu_loading');
        }
    }
    // END EDIT ADDRESS

    // DELETE ADDRESS
    _deleteAddress = (key)=> {
        this.setState({
            showDelete: true,
            deleteKey: key,
            deleleErr: ''
        });
    }

    _showDeleteAddress = ()=> {
        let {
            deleleErr
        } = this.state;

        return <div id="popup-account">
                    <div className="form-address">
                        <h3>Xóa địa chỉ</h3>
                        <span id="close-login-popup" className="electro-close-icon" onClick={()=> this.setState({ showDelete: false })}></span>
                        <div className="form-add-new">
                            <div className="input-group" style={{width: '100%'}}>
                                <p >Bạn chắc chắn muốn xóa địa chỉ này?</p>
                            </div>
                        </div>
                        {
                            deleleErr &&
                            <div className="input-group" style={{width: '100%'}}>
                                <p className="error">
                                    { deleleErr }
                                </p>
                            </div>
                        }
                            
                        <div className="btns" style={{textAlign: 'right'}}>
                            <button type="button" className="btn btn-primary" onClick={this._handleDeleteAddress}>
                                <span>Xác nhận</span>
                            </button>
                        </div>
                    </div>
                </div>
    }

    _handleDeleteAddress = async()=> {
        try {
            document.body.classList.add('hangcu_loading');
            
            var data = new FormData();
            data.append('action', 'hc_delete_address');
            data.append('delete-address', this.state.deleteKey);

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );
            
            if( response.data.success ) {
                this.setState({
                    showDelete: false,
                    deleteKey: '',
                    deleleErr: ''
                });
                await this._getListAddress();
            } else {
                
            }

        } catch (err) {
            console.log(err);
        } finally {
            document.body.classList.remove('hangcu_loading');
        }
    }
    // END DELETE ADDRESS

    render() {
        let {
            address,
            showAddNew,
            showEdit,
            showDelete
        } = this.state;
        return(
            <>
                <div className="address-container">
                    <h3>Sổ địa chỉ</h3>
                    {
                        Object.keys( address ).length <= 10 &&
                        <div className="add-new" onClick={this._showFormAddNew} >
                            <p>
                                <i className="fa fa-plus"></i>
                                Thêm địa chỉ mới
                            </p>
                        </div>
                    }
                    <div className="list-address">
                    {
                        Object.keys( address ).length ? this._renderListAddress() : null
                    }
                    </div>
                </div>
                {
                    showAddNew && this._showAddNewAddress()
                }
                {
                    showEdit && this._showEditAddress()
                }
                {
                    showDelete && this._showDeleteAddress()
                }
            </>
        );
    }
}

export default ListAddressComponent;