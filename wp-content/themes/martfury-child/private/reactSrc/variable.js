var HOST = 'http://localhost:9999/starComputer/star_ecommerce/web-tinhocngoisao/';
const SERVER_HOST = 'https://tinhocngoisao.com/';
HOST = SERVER_HOST;
export const URL_API_GET_LIST_PRODUCT_ON_SALE = HOST + 'wp-json/rest_api/v1/primetime_pricing?type=json&post_type=reactjs&response=json&cat_slug={cat_slug}&post_per_page={post_per_page}';
export const HOST_URL = HOST;
export const HOST_URL_API = HOST + 'wp-json/rest_api/v1/';
export const URL_GET_LIST_PRODUCT_CAT = HOST + 'wp-json/rest_api/v1/get_products_by_categoryid?advanced_option={advanced_option}&product_cat={product_cat}&product_tag={product_tag}&post_number={post_number}&orderby={orderby}&order={order}&start_page={start_page}';
export const URL_GET_LIST_PRODUCT_IDS = HOST + 'wp-json/rest_api/v1/get_products_by_productids?productids={productids}';
export const URL_GET_LIST_PRODUCT_HAS_SLUG = HOST + 'wp-json/rest_api/v1/get_products_by_categoryid?advanced_option=recent&post_number={post_number}&start_page={start_page}&orderby=date&order=DESC&get_slug=1';

export const URL_GET_LIST_PRODUCT_TABLE_SALE = HOST + 'wp-json/rest_api/v1/get_products_sales?advanced_option=recent&post_number={post_number}&start_page={start_page}&orderby=date&order=DESC&get_slug=1';
//--Mobile
export const URL_GET_LIST_CATEGORIES = HOST + 'wp-json/rest_api/v1/get_special_menus?cat_id={cat_id}';