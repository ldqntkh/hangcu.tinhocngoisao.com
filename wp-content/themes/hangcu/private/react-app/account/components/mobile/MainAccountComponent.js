import React, { lazy } from 'react';
import {
    BrowserRouter as Router,
    Switch,
    Route
} from "react-router-dom";

// import NavbarComponent from './NavbarComponent';
// import MBAccountComponent from './account/MBAccountComponent';
// import MBListOrderComponent from './order/MBListOrderComponent';
// import OrderDetailComponent from './order/OrderDetailComponent';
// import MBListAddressComponent from './address/MBAddressComponent';

const NavbarComponent = lazy(()=> import('./NavbarComponent'));
const MBAccountComponent = lazy(()=> import('./account/MBAccountComponent'));
const MBListOrderComponent = lazy(()=> import('./order/MBListOrderComponent'));
const OrderDetailComponent = lazy(()=> import('./order/OrderDetailComponent'));
const MBListAddressComponent = lazy(()=> import('./address/MBAddressComponent'));

class MB_MainAccountComponent extends React.Component {

    render() {
        return (
            <Router>
                <div className="account-container">
                    <Switch>
                        <Route exact path="/tai-khoan/">
                            <NavbarComponent />
                        </Route>
                        <Route exact path="/tai-khoan/edit-account/" component={MBAccountComponent}></Route>

                        <Route exact path="/tai-khoan/edit-address/" component={MBListAddressComponent}></Route>

                        <Route exact path={["/tai-khoan/orders", "/tai-khoan/orders/:page"]} render={(props) => <MBListOrderComponent {...props} />}></Route>

                        <Route exact path="/tai-khoan/view-order/:order_id" component={OrderDetailComponent}/>

                        <Route component={NavbarComponent} />
                    </Switch>
                </div>
            </Router>
        );
    }
}

export default MB_MainAccountComponent;