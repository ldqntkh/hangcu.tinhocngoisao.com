<?php

add_action( 'rest_api_init', function () {
    register_rest_route( 'rest_api/v1', '/getproductprice', array(
      array(
        'methods'             => 'POST',
        'callback'            => 'getproductprice',
        'args'                => array(),
        'permission_callback' => '__return_true'
      ))
    );
    register_rest_route( 'rest_api/v1', '/getproductpricebyids', array(
      array(
        'methods'             => 'GET',
        'callback'            => 'getproductpricebyids',
        'args'                => array(),
        'permission_callback' => '__return_true'
      ))
    );
});