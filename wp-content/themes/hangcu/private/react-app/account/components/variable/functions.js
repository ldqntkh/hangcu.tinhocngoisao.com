const formatPrice = (price)=> price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '<span class="woocommerce-Price-currencySymbol">₫</span>';

export {
    formatPrice
}