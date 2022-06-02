import React, {Component} from 'react';

// import component
// import SaveConfigBuilPcComponent from './footerItem/saveConfigBuildPcComponent';
import SaveImageConfigBuildPcComponent from './footerItem/saveImageConfigBuildPcComponent';
import ShareConfigBuildPcComponent from './footerItem/shareConfigBuildPcComponent';
import AddConfigBuildPcToCartComponent from './footerItem/addConfigBuildPcToCartComponent';

export default class FooterComponent extends Component {

    render() {
        return (
            <div className="build-pc-footer">
                {/* <SaveConfigBuilPcComponent /> */}
                <SaveImageConfigBuildPcComponent />
                <ShareConfigBuildPcComponent />
                <AddConfigBuildPcToCartComponent />
            </div>
        );
    }
}

