import React, { lazy, Suspense } from 'react';
import ReactDom from 'react-dom';

// import InstallmentComponent from './components/InstallmentComponent';
const InstallmentComponent = lazy(()=> import('./components/InstallmentComponent'));

class AppInstallment extends React.Component {
  render() {
    return(
      <Suspense fallback={<div>.</div>}>
        <InstallmentComponent />
      </Suspense>
    )
  }
}

ReactDom.render(<AppInstallment />, document.getElementById('product-installment-payment'));