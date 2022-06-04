import React from 'react';

const banks = [
  { id: "vp-bank", name: "VPBANK" },
  { id: "techcom-bank", name: "TECHCOMBANK" },
  { id: "acb-bank", name: "ACB" },
  { id: "anz-bank", name: "ANZ" },
  { id: "hsbc-bank", name: "HSBC" },
  { id: "shinhan-bank", name: "SHINHANBANK" },
  { id: "exim-bank", name: "EXIMBANK" },
  { id: "msb-bank", name: "MARITIMEBANK" },
  { id: "vib-bank", name: "VIB" },
  { id: "sac-bank", name: "SACOMBANK" },
  { id: "city-bank", name: "CITYBANK" },
  { id: "sea-bank", name: "SEABANK" },
  { id: "sc-bank", name: "SC" },
  { id: "tp-bank", name: "TPB" },
  { id: "scb-bank", name: "SCB" },
  { id: "fe-bank", name: "FE" },
  { id: "nama-bank", name: "NAB" },
  { id: "ocb-bank", name: "OCB" },
  { id: "kienlong-bank", name: "KLB" },
  { id: "shb-bank", name: "SHB" },
  { id: "bidv-bank", name: "BIDV" },
  { id: "vcb-bank", name: "VCB" },
  { id: "mb-bank", name: "MB" }
];

class CreditCardComponent extends React.Component {

  constructor(props) {
    super(props);
    this.state = {
      company: '',
      bank_id: '',
      card_type: 'mastercard',
      select_buy: '',
      gender: '',
      fullname: '',
      emailaddress: '',
      phone: ''
    }
  }

  _selectCompany = (company) => {
    this.setState({
      company,
      bank_id: '',
      card_type: company === 'payoo' ? "mastercard" : ''
    })
  }

  _selectBank = (value) => {
    this.setState({
      "bank_id" : value,
      card_type: 'mastercard',
      select_buy: '',
      gender: '',
      fullname: '',
      emailaddress: '',
      phone: ''
    })
  }

  _selectCard = (value) => {
    this.setState({
      "card_type" : value
    })
  }

  _formatPrice = (price)=> {
    return <React.Fragment> {price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}<span className="woocommerce-Price-currencySymbol">₫</span></React.Fragment>;
  }

  _renderInstallmentInfo = ()=> {
    let {
      bank_id, card_type
    } = this.state;
    let bank_name = '';
    for(let i = 0; i < banks.length; i++) {
      if ( banks[i].id === bank_id ) {
        bank_name = banks[i].name;
        break;
      }
    }
    let t6 = 1000000,
        t9 = 1500000,
        t12 = 2000000;
        
    return(
      <div className="installment-infos">
        <div className="head">
          <h4>Trả góp qua thẻ {card_type.toUpperCase()}, ngân hàng {bank_name}</h4>
        </div>
        <div className="body">
          <div className="line-item">
            <div className="col-01">Số tháng trả góp</div>
            <div className="col-02"><strong>6 tháng</strong></div>
            <div className="col-02"><strong>9 tháng</strong></div>
            <div className="col-02"><strong>12 tháng</strong></div>
          </div>

          <div className="line-item">
            <div className="col-01">Giá mua trả góp</div>
            <div className="col-02">
              <span className="woocommerce-Price-amount amount"><bdi>{ this._formatPrice( product_obj.product_price )}</bdi></span>
            </div>
            <div className="col-02">
              <span className="woocommerce-Price-amount amount"><bdi>{ this._formatPrice( product_obj.product_price )}</bdi></span>
            </div>
            <div className="col-02">
              <span className="woocommerce-Price-amount amount"><bdi>{ this._formatPrice( product_obj.product_price )}</bdi></span>
            </div>
          </div>

          <div className="line-item">
            <div className="col-01">Góp mỗi tháng</div>
            <div className="col-02">
              <span className="woocommerce-Price-amount amount"><bdi>{this._formatPrice( (( parseInt(product_obj.product_price) + t6 )/ 6).toFixed(0) )}</bdi></span>
            </div>
            <div className="col-02">
              <span className="woocommerce-Price-amount amount"><bdi>{this._formatPrice( (( parseInt(product_obj.product_price) + t9 )/ 9).toFixed(0) )}</bdi></span>
            </div>
            <div className="col-02">
              <span className="woocommerce-Price-amount amount"><bdi>{this._formatPrice( (( parseInt(product_obj.product_price) + t12 )/ 12).toFixed(0) )}</bdi></span>
            </div>
          </div>

          <div className="line-item">
            <div className="col-01">Phí chuyển đổi sang trả góp</div>
            <div className="col-02">
              <span className="woocommerce-Price-amount amount"><bdi>{this._formatPrice( t6 )}</bdi></span>
            </div>
            <div className="col-02">
              <span className="woocommerce-Price-amount amount"><bdi>{this._formatPrice( t9 )}</bdi></span>
            </div>
            <div className="col-02">
              <span className="woocommerce-Price-amount amount"><bdi>{this._formatPrice( t12 )}</bdi></span>
            </div>
          </div>

          <div className="line-item">
            <div className="col-01">Tổng tiền mua trả góp</div>
            <div className="col-02">
              <span className="woocommerce-Price-amount amount"><bdi>{this._formatPrice( parseInt(product_obj.product_price) + t6) }</bdi></span>
            </div>
            <div className="col-02">
              <span className="woocommerce-Price-amount amount"><bdi>{this._formatPrice( parseInt(product_obj.product_price) + t9) }</bdi></span>
            </div>
            <div className="col-02">
              <span className="woocommerce-Price-amount amount"><bdi>{this._formatPrice( parseInt(product_obj.product_price) + t12) }</bdi></span>
            </div>
          </div>

          <div className="line-item">
            <div className="col-01"></div>
            <div className="col-02" style={{textAlign: "center"}}><button type="button" onClick={()=> this.setState({select_buy: '6'})}>CHỌN MUA</button></div>
            <div className="col-02" style={{textAlign: "center"}}><button type="button" onClick={()=> this.setState({select_buy: '9'})}>CHỌN MUA</button></div>
            <div className="col-02" style={{textAlign: "center"}}><button type="button" onClick={()=> this.setState({select_buy: '12'})}>CHỌN MUA</button></div>
          </div>
        </div>
      </div>
    )
  }

  _renderFormBuy = ()=> {
    let {
      select_buy, gender, bank_id, phone, fullname, emailaddress
    } = this.state;
    let bank_name = '';
    for(let i = 0; i < banks.length; i++) {
      if ( banks[i].id === bank_id ) {
        bank_name = banks[i].name;
        break;
      }
    }
    return (
      <div className="form-buy">
        <h6>4. Nhập thông tin người mua</h6>
        <p>Trả góp thông qua ngân hàng {bank_name}, số tháng trả góp: {select_buy}</p>
        <div className="genders">
          <div className={ gender === 'male' ? "gender active" : "gender" } key={"male"} onClick={()=> this.setState({gender: 'male'})}>
            <input type="radio" id="gender-male" value="male" checked={gender === "male"} onChange={()=> this.setState({gender: 'male'})}/>
            <label htmlFor="gender-male">Anh</label>
          </div>

          <div className={ gender === 'female' ? "gender active" : "gender" } key={"female"} onClick={()=> this.setState({gender: 'female'})}>
            <input type="radio" id="gender-female" value="female" checked={gender === "female"} onChange={()=> this.setState({gender: 'female'})}/>
            <label htmlFor="gender-female">Chị</label>
          </div>
        </div>
        
        <div className="forms">
          <input type="text" value={fullname} name="fullname" placeholder="Họ tên"  onChange={ (e)=> { this.setState({ fullname: e.target.value}) } }/>
          <input type="text" value={phone} name="phone" placeholder="Số điện thoại"  onChange={ (e)=> { this.setState({ phone: e.target.value}) } }/>
          <input type="text" value={emailaddress} name="emailaddress" placeholder="Địa chỉ Email"  onChange={ (e)=> { this.setState({ emailaddress: e.target.value}) } }/>
        </div>
        <div className="btns">
          <button type="button">THANH TOÁN NGAY</button>
        </div>
      </div>
    )
  }

  _renderCardType = ()=> {
    let {
      card_type, select_buy
    } = this.state;
    return(
      <div className="banks">
        <h6>3. Chọn thẻ thanh toán</h6>
        <div className="com-banks">
          <div className={ card_type === 'visa' ? "bank-item active" : "bank-item" } key={"visa"} onClick={()=> this._selectCard('visa')}>
            <input type="radio" value="visa" checked={card_type === "visa"} onChange={()=> this._selectCard(bank.id)}/>
            <i className={ "icon visa" }></i>
          </div>

          <div className={ card_type === 'mastercard' ? "bank-item active" : "bank-item" } key={"mastercard"} onClick={()=> this._selectCard('mastercard')}>
            <input type="radio" value="mastercard" checked={card_type === "mastercard"} onChange={()=> this._selectCard(bank.id)}/>
            <i className={ "icon mastercard" }></i>
          </div>

          <div className={ card_type === 'jcb' ? "bank-item active" : "bank-item" } key={"jcb"} onClick={()=> this._selectCard('jcb')}>
            <input type="radio" value="jcb" checked={card_type === "jcb"} onChange={()=> this._selectCard(bank.id)}/>
            <i className={ "icon jcb" }></i>
          </div>
        </div>
        {
          card_type && this._renderInstallmentInfo()
        }
        {
          select_buy && this._renderFormBuy()
        }
      </div>
    )
  }

  _renderInfo = ()=> {
    let {
      company
    } = this.state;

    if ( company === 'payoo' ) {
      return this._renderInstallmentInfo();
    } else {
      return this._renderCardType();
    }
  }

  render() {
    let {
      company, bank_id
    } = this.state;

    return(
      <div className="credit-card">
        {/* chọn công ty */}
        <div className="company">
          <h6>1. Chọn công ty</h6>
          <div className="opts">
            <div className="item">
              <input type="radio" name="company" id="alepay" value="alepay" checked={company === 'alepay'} onChange={()=> this._selectCompany('alepay')}/>
              <label htmlFor="alepay">Thông qua Alepay</label>
            </div>
            <div className="item">
              <input type="radio" name="company" id="payoo" value="payoo" checked={company === 'payoo'} onChange={()=> this._selectCompany('payoo')}/>
              <label htmlFor="payoo">Thông qua Payoo</label>
            </div>
          </div>
        </div>
      
        {/* Ngân hàng */}
        <div className="banks">
          {
            company &&
            <React.Fragment>
              <h6>2. Chọn ngân hàng</h6>
              <div className="com-banks">
                {
                  banks.map( bank => {
                    return(
                      <div className={ bank_id === bank.id ? "bank-item active" : "bank-item" } key={bank.id} onClick={()=> this._selectBank(bank.id)}>
                        <input type="radio" value={bank.id} checked={bank.id === bank_id} onChange={()=> this._selectBank(bank.id)}/>
                        <i className={ "icon " + bank.id }></i>
                      </div>
                    )
                  } )
                }
              </div>

            </React.Fragment>
          }
        </div>
      
        {/* card value */}
        {
          company && bank_id && this._renderInfo()
        }

      </div>
    )
  }
}

export default CreditCardComponent;