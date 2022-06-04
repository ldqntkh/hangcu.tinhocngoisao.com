import React from 'react';

class PaylaterComponent extends React.Component {
  
  _formatPrice = (price)=> {
    return <React.Fragment> {price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}<span className="woocommerce-Price-currencySymbol">₫</span></React.Fragment>;
  }

  render() {
    let lai = 5000000;

    return(
      <div className="paylater">
        <div className="line-item">
          <div className="col-01"><strong>Số tiền trả trước</strong></div>
          <div className="col-02"><span className="highline">{this._formatPrice( (parseInt(product_obj.product_price)/100*30).toFixed(0) )}</span><span> (30%)</span></div>
        </div>
        <div className="line-item">
          <div className="col-01"><strong>Khoản vay trả góp</strong></div>
          <div className="col-02"><span className="highline">{this._formatPrice( (parseInt(product_obj.product_price)/100*70).toFixed(0) )}</span></div>
        </div>
        <div className="line-item">
          <div className="col-01"><strong>Số tiền trả hàng tháng</strong></div>
          <div className="col-02"><span className="highline">{this._formatPrice( ((parseInt(product_obj.product_price)/100*70 + lai)/12).toFixed(0) )}</span></div>
        </div>
        <div className="line-item">
          <div className="col-01"><strong>Phí trả chậm</strong></div>
          <div className="col-02"><span className="highline">Chỉ từ 1.46%</span></div>
        </div>
        <div className="line-item">
          <div className="col-01"><strong>Tổng tiền sau trả góp</strong></div>
          <div className="col-02"><span className="highline">{this._formatPrice( parseInt(product_obj.product_price) + lai )}</span></div>
        </div>
        <div className="line-item">
          <div className="col-01"><strong>Chênh lệch trả thường</strong></div>
          <div className="col-02"><span className="highline">{this._formatPrice( lai )}</span></div>
        </div>
        <div className="line-item">
          <div className="col-01"><strong>Giấy tờ cần có</strong></div>
          <div className="col-02"><span>Chỉ cần chứng minh nhân dân</span></div>
        </div>
        <div className="line-item">
          <div className="col-01"><strong>Thời gian duyệt hồ sơ</strong></div>
          <div className="col-02"><span>15 phút</span></div>
        </div>

        <div className="line-item" style={{textAlign: 'center', paddingTop: 20}}>
          <button type="button">Tiến hành đăng ký</button>
        </div>
        <div className="line-item" style={{textAlign: 'center'}}>
          <p><strong>Lưu ý:</strong> Bảng tính có thể chênh lệch không đáng kể so với thực tế. Vui lòng liên hệ 1800.6018 hoặc cửa hàng gần nhất để được tư vấn chính xác</p>
        </div>
      </div>
    )
  }
}

export default PaylaterComponent;