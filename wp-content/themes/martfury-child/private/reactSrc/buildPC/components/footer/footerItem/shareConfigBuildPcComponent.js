import React, {Component} from 'react';

import {
    HOST_URL
} from '../../../../variable';

export default class ShareConfigBuildPcComponent extends Component {

    shareLinkFacebook = ()=> {
        // check require product build pc
        // ok => open link in new tab
        //<a target="_blank" href={`https://www.facebook.com/sharer.php?s=100&amp;p%5Btitle%5D=${encodeURI("Chia sẻ cấu hình")}&amp;p%5Burl%5D=${link}`} >
        if (localStorage.getItem('computer_building_data')) {
            let computer_building_data = JSON.parse(localStorage.getItem('computer_building_data'));
            let flagRequire = false;
            for(let index in computer_building_data) {
                if (computer_building_data[index].require && computer_building_data[index].product === null) {
                    //flagRequire = true;
                    break;
                }
            }

            if (flagRequire) {
                alert("Vui lòng chọn những sản phẩm bắt buộc phải có (*) trong cấu hình máy tính trước khi thực hiện chức năng này!");
            } else {
                // loại bỏ những phần tử ko cần thiết, chỉ giữ lại productid và quantity
                var result_building_data = {};
                for(let index in computer_building_data) {
                    let item = {
                        "product_id" : computer_building_data[index].product !== null ? computer_building_data[index].product.id : null,
                        "quantity" : computer_building_data[index].quantity
                    };
                    result_building_data[index] = item;
                }
                
                let link = HOST_URL + 'share-buildpc?building_data=' + btoa(escape(JSON.stringify(result_building_data)));
                let fbAppId = window.facebookAppId !== undefined ? window.facebookAppId : '';
                //let openLink = `https://www.facebook.com/sharer.php?s=100&amp;p%5Btitle%5D=${encodeURI("Chia sẻ cấu hình")}&amp;p%5Burl%5D=${link}`;
                var url = 'https://www.facebook.com/dialog/feed?' +
                            'app_id=' + fbAppId +
                            '&display=popup'+
                            '&name='+encodeURIComponent('Chia sẻ cấu hình máy tính')+
                            '&link='+encodeURIComponent(link)+
                            '&href='+encodeURIComponent(link)+
                            '&redirect_uri='+encodeURIComponent(link);
                window.open(url, 'EscrowInfo', 'resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no,width=600px,height=600px');
                // try {
                //     console.log(link);
                //     FB.ui({
                //         app_id: "350121975741363",
                //         method: 'share',
                //         href: link,
                //     }, function(response){});
                // } catch(err) {
                //     //
                // }
            }
        }
    }

    render() {
        
        let link = "";
        return(
            <div className="btn-item">
                <button type="button" className="btn btn-share" onClick={this.shareLinkFacebook}>
                    <i className="fa fa-facebook"/>
                    Chia sẻ cấu hình
                </button>
            </div>
        );
    }
}