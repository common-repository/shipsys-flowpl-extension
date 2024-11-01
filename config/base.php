<?php

// This setting file contains all the routes to the backend APIs.

$ENDPOINTS = array(
	'AWB_NUMBER_API'         => '/api/ecommerce/getawbnumber',
	'VSERIES_API'            => '/api/ecommerce/getSeries',
	'SHOP_DATA_API'          => '/api/ecommerce/getshopdata',
	'REGISTER_SHOP_API'      => '/api/ecommerce/registershop',
	'UPDATE_ADDRESS_API'     => '/api/ecommerce/updateaddress',
	'TRACKING_API'           => '/api/ecommerce/gettracking',
	'SOFTDATA_API'           => '/api/ecommerce/softdata',
	'SHIPPING_LABEL_API'     => '/api/ecommerce/shippinglabel',
	'CANCEL_CONSIGNMENT_API' => '/api/ecommerce/cancelconsignment',
);

$COOKIE_TTL = 2592000;
