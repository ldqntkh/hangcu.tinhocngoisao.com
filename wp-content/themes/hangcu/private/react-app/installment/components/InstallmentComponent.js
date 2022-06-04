import React from 'react';

import CreditCardComponent from './credit/CreditCardComponent';
import PaylaterComponent from './paylater/PaylaterComponent';

const installmentOptions = [
  {
    id: 'credit_card',
    name: 'QUA THẺ TÍN DỤNG',
    desc: 'Không cần xét duyệt. L.S 0%'
  },
  {
    id: 'paylater',
    name: 'CÔNG TY PAYLATER',
    desc: 'Chỉ cần CMND. Xét duyệt Online'
  },
  {
    id: 'com',
    name: 'CÔNG TY TÀI CHÍNH',
    desc: 'Xét duyệt hồ sơ qua điện thoại'
  }
]

class InstallmentComponent extends React.Component {

  constructor( props ) {
    super(props);
    this.state = {
      "installmentOption" : "credit_card"
    }
  }

  _selectInstallmentOption = (value) => {
    this.setState({
      "installmentOption" : value
    })
  }

  render() {
    let {
      installmentOption
    } = this.state;
    return(
      <div className="container">
        {/* header */}
        <div className="pr-header">
          <p>
            Sản phẩm <strong>{product_obj.product_name}</strong>
          </p>
          <p>
            Trị giá <strong dangerouslySetInnerHTML={{__html: product_obj.html_price}}></strong>
          </p>
        </div>
        {/* body */}
        <div className="pr-body">
          {/* hình thức trả góp */}
          <div className="hinh-thuc-tra-gop">
            <p>Chọn hình thức trả góp phù hợp</p>
            <div className="installment-options">
              {
                installmentOptions.map( item => {
                  return (
                    <div className={ installmentOption === item.id ? "installment-item active" : "installment-item" } key={item.id} onClick={()=> this._selectInstallmentOption(item.id)}>
                      <h4>{ item.name }</h4>
                      <span>{ item.desc }</span>
                    </div>
                  )
                } )
              }
            </div>
          </div>
        
          {/* Bank */}
          {
            installmentOption === 'credit_card' ?
            <CreditCardComponent />
            :
            installmentOption === 'paylater' ?
            <PaylaterComponent />
            : 
            <div class="alert alert-warning">
              <p>Hệ thống đang trong quá trình nâng cấp. Quý khách hàng cần tư vấn trả góp, vui lòng liên hệ <a href="tel:18006975"><strong>1800 6975 (miễn phí)</strong></a></p>
            </div>
          }
        </div>
      </div>
    )
  }
}

export default InstallmentComponent;