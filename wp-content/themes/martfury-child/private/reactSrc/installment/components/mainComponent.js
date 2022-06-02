import React, { Component } from 'react';

export default class MainComponent extends Component {

    constructor(props) {
        super(props);
        this.state = {
            congty: 'taichinh',
            bank_taichinh: [],
            month_taichinh: [],
            bank_tindung: [],
            month_tindung: [],
            price: 0,
            price_display: 0,
            month: 0,
            percent: 0,
            tindung_selected : null
        }
    }

    componentDidMount() {
        if ( typeof bank_data === 'undefined') return;
        let bank_taichinh = [];
        let month_taichinh = [];

        let bank_tindung = [];
        let month_tindung = [];

        let taichinhs = bank_data['taichinh'];
        
        if ( taichinhs ) {
            for( let index in taichinhs ) {
                let bankitem = taichinhs[index];
                let item = {};
                item.id = bankitem.bank.ID;
                item.name = bankitem.bank.bank_name;
                item.img = bankitem.bank.bank_img;
                item.installments = bankitem.installments;

                let month = [];
                for( let k in item.installments ) {
                    month.push( parseInt(item.installments[k].month) );
                }
                month_taichinh = month_taichinh.concat( month );

                bank_taichinh.push(item);
            }
        }

        let tindungs = bank_data['tindung'];
        if ( tindungs ) {
            for( let index in tindungs ) {
                let bankitem = tindungs[index];
                let item = {};
                item.id = bankitem.bank.ID;
                item.name = bankitem.bank.bank_name;
                item.img = bankitem.bank.bank_img;
                item.subbank = bankitem.sub_bank;
                item.installments = bankitem.installments;

                let month = [];
                for( let k in item.installments ) {
                    month.push( parseInt(item.installments[k].month) );
                }
                month_tindung = month_tindung.concat( month );

                bank_tindung.push(item);
            }
        }

        month_taichinh = this.arrayUnique(month_taichinh);
        month_tindung = this.arrayUnique(month_tindung);

        this.setState({
            bank_taichinh,
            bank_tindung,
            month_taichinh,
            month_tindung,
            month: month_taichinh[0]
        }, ()=> {
            // console.log(this.state);
        });
    }

    sortNumber(a, b) {
        return a - b;
    }

    arrayUnique = (array)=> {
        var a = array.concat();
        for(var i=0; i<a.length; ++i) {
            for(var j=i+1; j<a.length; ++j) {
                if(a[i] === a[j])
                    a.splice(j--, 1);
            }
        }
    
        return a.sort(this.sortNumber);
    }

    _handleChangePrice = (e)=> {
        let  val = e.target.value.split(",").join("");
        this.setState({
            price: parseInt(val),
            price_display: this.numberWithCommas(parseInt(val))
        })
    }

    _handleChangePercent = (e)=> {
        this.setState({
            percent: e.target.value
        })
    }

    _renderTaiChinh = ()=> {
        let {
            bank_taichinh,
            percent,
            price,
            month
        } = this.state;
        !price ? price = 0 : price = price;

        let length = bank_taichinh.length;
        let width = 100 / (length + 1);

        
        return(
            <div className="content-taichinh">
                <div className="row-item">
                    <div className="col-item header" style={{width: width + '%'}}>
                        Công ty
                    </div>
                    {
                        bank_taichinh.map((item, index) => {
                            return (
                                <div key={index} className="col-item header" style={{width: width + '%'}}>
                                    {
                                        item.img ? 
                                        <img src={item.img} alt="" />
                                        :
                                        item.name
                                    }
                                </div>
                            )
                        })
                    }
                </div>

                <div className="row-item">
                    <div className="col-item" style={{width: width + '%'}}>
                        Trả trước
                        <select onChange={this._handleChangePercent} value={percent}>
                            <option value="0">0%</option>
                            <option value="10">10%</option>
                            <option value="20">20%</option>
                            <option value="30">30%</option>
                            <option value="40">40%</option>
                            <option value="50">50%</option>
                            <option value="60">60%</option>
                            <option value="70">70%</option>
                            <option value="80">80%</option>
                        </select>
                    </div>
                    {
                        bank_taichinh.map((item, index) => {
                            return (
                                <div key={index} className="col-item" style={{width: width + '%'}}>
                                    {
                                        this.numberWithCommas( price / 100 * percent ) + 'đ'
                                    }
                                </div>
                            )
                        })
                    }
                </div>
                <div className="row-item">
                    <div className="col-item" style={{width: width + '%'}}>
                        Lãi suất (%)
                    </div>
                    {
                        bank_taichinh.map((item, index) => {
                            let installment = null;
                            for( let k in bank_taichinh[index].installments ) {
                                if ( month == bank_taichinh[index].installments[k].month ) {
                                    installment = bank_taichinh[index].installments[k];
                                    break;
                                }
                            }
                            
                            if ( !installment || parseInt(installment.min_price) > price ) {
                                return <div key={index} className="col-item" style={{width: width + '%'}}>
                                        Ngân hàng không hỗ trợ
                                    </div>
                            } else {
                                return <div key={index} className="col-item" style={{width: width + '%'}}>
                                        {installment.fee + "%"}
                                    </div>
                            }
                        })
                    }
                </div>

                <div className="row-item">
                    <div className="col-item" style={{width: width + '%'}}>
                        Góp mỗi tháng (đ)
                    </div>
                    {
                        bank_taichinh.map((item, index) => {
                            let installment = null;
                            for( let k in bank_taichinh[index].installments ) {
                                if ( month == bank_taichinh[index].installments[k].month ) {
                                    installment = bank_taichinh[index].installments[k];
                                    break;
                                }
                            }
                            let total_price = price - (price * percent / 100);
                            
                            if ( !installment || installment.min_price > price ) {
                                return <div key={index} className="col-item" style={{width: width + '%'}}>
                                        
                                    </div>
                            } else {
                                return <div key={index} className="col-item" style={{width: width + '%'}}>
                                        { this.numberWithCommas( ((total_price + ( total_price * installment.fee / 100 * month )) / month).toFixed(0) ) + 'đ' }
                                    </div>
                            }
                        })
                    }
                </div>

                <div className="row-item">
                    <div className="col-item" style={{width: width + '%'}}>
                        Tổng tiền phải trả (đ)
                    </div>
                    {
                        bank_taichinh.map((item, index) => {
                            let installment = null;
                            for( let k in bank_taichinh[index].installments ) {
                                if ( month == bank_taichinh[index].installments[k].month ) {
                                    installment = bank_taichinh[index].installments[k];
                                    break;
                                }
                            }
                            let total_price = price - (price * percent / 100);
                            
                            if ( !installment || installment.min_price > price ) {
                                return <div key={index} className="col-item" style={{width: width + '%'}}>
                                        
                                    </div>
                            } else {
                                return <div key={index} className="col-item" style={{width: width + '%'}}>
                                        { this.numberWithCommas( ((total_price + ( total_price * installment.fee / 100 * month )) + (price * percent / 100) ).toFixed(0) ) + 'đ' }
                                    </div>
                            }
                        })
                    }
                </div>

                <div className="row-item">
                    <div className="col-item" style={{width: width + '%'}}>
                        Chênh lệch với mua thẳng (đ)
                    </div>
                    {
                        bank_taichinh.map((item, index) => {
                            let installment = null;
                            for( let k in bank_taichinh[index].installments ) {
                                if ( month == bank_taichinh[index].installments[k].month ) {
                                    installment = bank_taichinh[index].installments[k];
                                    break;
                                }
                            }
                            let total_price = price - (price * percent / 100);
                            
                            if ( !installment || installment.min_price > price ) {
                                return <div key={index} className="col-item" style={{width: width + '%'}}>
                                        
                                    </div>
                            } else {
                                return <div key={index} className="col-item" style={{width: width + '%'}}>
                                        { this.numberWithCommas( ((total_price + ( total_price * installment.fee / 100 * month )) + (price * percent / 100) - price ).toFixed(0) ) + 'đ' }
                                    </div>
                            }
                        })
                    }
                </div>

                <div className="row-item">
                    <div className="col-item" style={{width: width + '%'}}>
                        Giấy tờ cần có
                    </div>
                    {
                        bank_taichinh.map((item, index) => {
                            let installment = null;
                            for( let k in bank_taichinh[index].installments ) {
                                if ( month == bank_taichinh[index].installments[k].month ) {
                                    installment = bank_taichinh[index].installments[k];
                                    break;
                                }
                            }
                            let total_price = price - (price * percent / 100);
                            
                            if ( !installment || installment.min_price > price ) {
                                return <div key={index} className="col-item" style={{width: width + '%'}}>
                                        
                                    </div>
                            } else {
                                return <div key={index} className="col-item" style={{width: width + '%'}}>
                                        { installment.docs_require }
                                    </div>
                            }
                        })
                    }
                </div>
            </div>  
        )
    }

    _renderTinDung = ()=> {
        let {
            bank_tindung,
            price,
            tindung_selected
        } = this.state;
        !price ? price = 0 : price = price;
        let installments = tindung_selected ? tindung_selected.installments : [];

        let width = 100 / (installments.length + 1);

        return(
            <div className="content-tindung">
                <h3>Chọn loại ngân hàng</h3>
                <div className="banks">
                    {
                        bank_tindung.map( (item, index) => {
                            return(
                                <img src={item.img} alt="" key={index} className={tindung_selected && item.id == tindung_selected.id ? 'active' : ''}
                                onClick={()=> this.setState({tindung_selected: item})}/>
                            )
                        } )
                    }
                </div>
                <h3>Thẻ thanh toán hỗ trợ</h3>
                {
                    tindung_selected && 
                    <div className="sub-banks">
                        {
                            tindung_selected.subbank.map( (sub, index) => {
                                return(
                                    <div className={sub.sub_bank_name} key={index}/>
                                )
                            } )
                        }
                    </div>
                }

                {
                    tindung_selected && 
                    <React.Fragment>
                        <div className="row-item">
                            <div className="col-item" style={{width: width + '%'}}>
                                Số tháng trả góp
                            </div>
                            {
                                installments.map( (item, index) => {
                                    if ( item.min_price > price ) {
                                        return <div key={index} className="col-item" style={{width: width + '%'}}>
                                            Ngân hàng không hỗ trợ
                                        </div>
                                    } else {
                                        return <div key={index} className="col-item" style={{width: width + '%'}}>
                                            {item.month + " tháng"}
                                        </div>
                                    }
                                } )
                            }
                        </div>

                        <div className="row-item">
                            <div className="col-item" style={{width: width + '%'}}>
                                Phụ phí
                            </div>
                            {
                                installments.map( (item, index) => {
                                    if ( item.min_price > price ) {
                                        return <div key={index} className="col-item" style={{width: width + '%'}}>
                                            
                                        </div>
                                    } else {
                                        return <div key={index} className="col-item" style={{width: width + '%'}}>
                                            {item.fee + "%"}
                                        </div>
                                    }
                                } )
                            }
                        </div>

                        <div className="row-item">
                            <div className="col-item" style={{width: width + '%'}}>
                                Trả góp mỗi tháng (đ)
                            </div>
                            {
                                installments.map( (item, index) => {
                                    if ( item.min_price > price ) {
                                        return <div key={index} className="col-item" style={{width: width + '%'}}>
                                            
                                        </div>
                                    } else {
                                        return <div key={index} className="col-item" style={{width: width + '%'}}>
                                            { this.numberWithCommas( ((price + ( price * item.fee / 100 )) / item.month).toFixed(0) ) + 'đ' }
                                        </div>
                                    }
                                } )
                            }
                        </div>

                        <div className="row-item">
                            <div className="col-item" style={{width: width + '%'}}>
                                Tổng tiền phải trả (đ)
                            </div>
                            {
                                installments.map( (item, index) => {
                                    if ( item.min_price > price ) {
                                        return <div key={index} className="col-item" style={{width: width + '%'}}>
                                            
                                        </div>
                                    } else {
                                        return <div key={index} className="col-item" style={{width: width + '%'}}>
                                            { this.numberWithCommas( ((price + ( price * item.fee / 100 ))).toFixed(0) ) + 'đ' }
                                        </div>
                                    }
                                } )
                            }
                        </div>

                        <div className="row-item">
                            <div className="col-item" style={{width: width + '%'}}>
                                Chênh lệch với mua thẳng (đ)
                            </div>
                            {
                                installments.map( (item, index) => {
                                    if ( item.min_price > price ) {
                                        return <div key={index} className="col-item" style={{width: width + '%'}}>
                                            
                                        </div>
                                    } else {
                                        return <div key={index} className="col-item" style={{width: width + '%'}}>
                                            { this.numberWithCommas( ((price * item.fee / 100 )).toFixed(0) ) + 'đ' }
                                        </div>
                                    }
                                } )
                            }
                        </div>
                    </React.Fragment>
                }
            </div>
        );
    }

    numberWithCommas = ( x ) => {
        return x.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }

    render() {
        let {
            congty,
            price,
            price_display,
            month,
            month_taichinh,
            month_tindung
        } = this.state;
        return (
            <div className="main-installment">
                <div className="header">
                    <h3>Công thức tính phí trả góp</h3>
                    
                    { 
                        typeof installment_hotline !== 'undefined' && 
                        <div dangerouslySetInnerHTML={{ __html: installment_hotline }} />
                    }
                   
                </div>
                <div className="input-price">
                    <label htmlFor="input-price">Nhập số tiền mua hàng(đ)</label>
                    <input name="input-price" id="input-price"  value={!price ? 0 : price_display} type="numer" onChange={this._handleChangePrice}/>
                </div>
                <div className="content">
                    <div className="tabs">
                        <div className={"tab tab-taichinh " + ( congty == 'taichinh' ? 'active' : '') }
                            onClick={()=> this.setState({
                                congty: 'taichinh',
                                month: month_taichinh[0]
                            })}>
                            <p>Trả góp qua công ty tài chính</p>
                            <i>Xét duyệt hồ sơ nhanh chóng</i>
                        </div>
                        <div className={"tab tab-tindung " + ( congty == 'tindung' ? 'active' : '') }
                            onClick={()=> this.setState({
                                congty: 'tindung',
                                month: month_tindung[0]
                            })}>
                            <p>Trả góp bằng thẻ tín dụng</p>
                            <i>Nhận hàng ngay, không cần xét duyệt hồ sơ</i>
                        </div>
                    </div>
                    {
                        congty == 'taichinh' &&
                        <div className="months">
                            <p>Chọn số tháng trả góp: </p>
                            {
                                congty == 'taichinh' ?
                                month_taichinh.map( (item, index) => {
                                    return (
                                        <button className={ item == month ? 'active' : '' } key={index}
                                            onClick={()=> this.setState({month: item})}>{item} tháng</button>
                                    )
                                } )
                                :
                                month_tindung.map( (item, index) => {
                                    return (
                                        <button className={ item == month ? 'active' : '' } key={index}
                                            onClick={()=> this.setState({month: item})}>{item} tháng</button>
                                    )
                                } )
                            }
                        </div>
                    }
                    
                    {
                        congty == 'taichinh' && this._renderTaiChinh()
                    }
                    {
                        congty == 'tindung' && this._renderTinDung()
                    }


                </div>
                
                { 
                    typeof installment_message !== 'undefined' && 
                    <div dangerouslySetInnerHTML={{ __html: installment_message }} />
                }
                
            </div>
        )
    }
}