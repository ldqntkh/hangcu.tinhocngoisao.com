import React, {lazy} from 'react';
import {
    BrowserRouter as Router,
    Switch,
    Route
} from "react-router-dom";

import NavbarComponent from './NavbarComponent';
import AccountInfoComponent from '../share/account/AccountInfoComponent';
import ListOrderComponent from '../share/order/ListOrderComponent';
import OrderDetailComponent from '../share/order/OrderDetailComponent';
import ListAddressComponent from '../share/address/ListAddressComponent';

// const NavbarComponent = lazy(()=> import('./NavbarComponent')) ;
// const AccountInfoComponent = lazy(()=> import('../share/account/AccountInfoComponent'));
// const ListOrderComponent = lazy(()=> import('../share/order/ListOrderComponent'));
// const OrderDetailComponent = lazy(()=> import('../share/order/OrderDetailComponent'));
// const ListAddressComponent = lazy(()=> import('../share/address/ListAddressComponent'));
class DK_MainAccountComponent extends React.Component {

    render() {
        return (
            <Router>
                <NavbarComponent />
                <div className="account-container">
                    <Switch>
                        <Route exact path="/tai-khoan/">
                            <AccountInfoComponent />
                        </Route>
                        <Route exact path="/tai-khoan/edit-account/">
                            <AccountInfoComponent />
                        </Route>
                        <Route exact path="/tai-khoan/edit-address/">
                            <ListAddressComponent />
                        </Route>
                        <Route exact path={["/tai-khoan/orders", "/tai-khoan/orders/:page"]} render={(props) => <ListOrderComponent {...props} />}>
                        </Route>

                        <Route exact path="/tai-khoan/view-order/:order_id" component={OrderDetailComponent}/>
                        <Route component={AccountInfoComponent} />
                    </Switch>
                </div>
            </Router>
        );
    }
}

export default DK_MainAccountComponent;