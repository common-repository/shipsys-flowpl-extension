<?php

/*
 TODO: Find a neat way of importing the endpoints
		 instead of just calling a function again and again.
		 Although we are using `require_once` but still.
*/
function shipsyGetEndpoint( $api ) {
	require DTDC_ECONNECT_PATH . 'config/settings.php';
	// WHY: Why is it not working when we use `require_once`?
	// echo $ENDPOINTS[$api];
	return $ENDPOINTS[ $api ];
}

function shipsyGetCookieTTL() {
	require DTDC_ECONNECT_PATH . 'config/settings.php';
	return time() + $COOKIE_TTL;
}

function shipsySanitizeArray( $input ) {
	// Initialize the new array that will hold the sanitize values
	$new_input = array();

	// Loop through the input and recursively sanitize each of the values
	foreach ( $input as $key => $val ) {
		if ( is_array( $val ) ) {
			$new_input[ $key ] = shipsySanitizeArray( $val );
		} else {
			$new_input[ $key ] = sanitize_text_field( $val );
		}
	}
	return $new_input;
}

function shipsyParseResponseError( $error ) {
	if ( $error['statusCode'] == 401 ) {
		return 'Authentication error! Please log in again.';
	}
	return $error['message'];
}

function shipsyValidateConsignmentAddresses( $consignment ) {
	$ends        = array( 'origin', 'destination' );
	$end_details = array( 'name', 'number', 'alt-number', 'line-1', 'line-2', 'pincode', 'city', 'state', 'country' );

	// error_log(print_r($consignment, true));

	foreach ( $ends as $end ) {
		foreach ( $end_details as $end_detail ) {
			$key = $end . '-' . $end_detail;
			if ( ! isset( $consignment[ $key ] ) ) {
				$consignment[ $key ] = '';
			}
		}
	}

	return $consignment;
}

function shipsyValidateCustomerAddressess( $addresses ) {
	$address_types   = array( 'forwardAddress', 'reverseAddress', 'exceptionalReturnAddress', 'returnAddress' );
	$address_details = array( 'name', 'phone', 'alternate_phone', 'address_line_1', 'address_line_2', 'pincode', 'city', 'state' );

	foreach ( $address_types as $address_type ) {
		if ( ! isset( $addresses[ $address_type ] ) ) {
			$addresses[ $address_type ] = array();
		}

		foreach ( $address_details as $address_detail ) {
			if ( ! isset( $addresses[ $address_type ][ $address_detail ] ) ) {
				$addresses[ $address_type ][ $address_detail ] = '';
			}
		}
	}
	return $addresses;
}

function dtdcGetAwbNumber( $synced_orders ) {
	$headers = array(
		'Content-Type'    => 'application/json',
		'organisation-id' => getOrgId(),
		'shop-origin'     => 'wordpress',
		'shop-url'        => dtdcGetShopUrl(),
		'customer-id'     => dtdcGetCustId(),
		'access-token'    => dtdcGetAccessToken(),
	);

	$dataToSendJson = wp_json_encode( array( 'customerReferenceNumberList' => $synced_orders ) );
	$args           = array(
		'body'        => $dataToSendJson,
		'timeout'     => '10',
		'redirection' => '10',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => $headers,
	);
	$request_url    = shipsyGetEndpoint( 'AWB_NUMBER_API' );
	$response       = wp_remote_post( $request_url, $args );
	$result         = wp_remote_retrieve_body( $response );
	$resultdata     = json_decode( $result, true );
	return $resultdata;

}


function dtdcGetVirtualSeries() {
	$headers     = array(
		'Content-Type'    => 'application/json',
		'organisation-id' => getOrgId(),
		'shop-origin'     => 'wordpress',
		'shop-url'        => dtdcGetShopUrl(),
		'customer-id'     => dtdcGetCustId(),
		'access-token'    => dtdcGetAccessToken(),
	);
	$args        = array(
		'headers' => $headers,
	);
	$request_url = shipsyGetEndpoint( 'VSERIES_API' );
	$response    = wp_remote_get( $request_url, $args );
	$result      = wp_remote_retrieve_body( $response );
	$resultdata  = json_decode( $result, true );
	return $resultdata;

}

function dtdcGetAddresses() {
	$headers     = array(
		'Content-Type'    => 'application/json',
		'organisation-id' => getOrgId(),
		'shop-origin'     => 'wordpress',
		'shop-url'        => dtdcGetShopUrl(),
		'customer-id'     => dtdcGetCustId(),
		'access-token'    => dtdcGetAccessToken(),
	);
	$args        = array(
		'timeout'     => '10',
		'redirection' => '10',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => $headers,
	);
	$request_url = shipsyGetEndpoint( 'SHOP_DATA_API' );
	$response    = wp_remote_post( $request_url, $args );
	$result      = wp_remote_retrieve_body( $response );
	$resultdata  = json_decode( $result, true );
	return $resultdata;
}

function dtdcConfig( $postRequestParams ) {
	$postRequestParams['org_id'] = strtolower( $postRequestParams['org_id'] );

	$headers         = array(
		'Content-Type'    => 'application/json',
		'organisation-id' => $postRequestParams['org_id'],
		'shop-origin'     => 'wordpress',
		'shop-url'        => dtdcGetShopUrl(),
	);
	$dataToSendArray = array(
		'username' => $postRequestParams['user-name'],
		'password' => $postRequestParams['password'],
	);
	$dataToSendJson  = wp_json_encode( $dataToSendArray );
	$args            = array(
		'body'        => $dataToSendJson,
		'timeout'     => '10',
		'redirection' => '10',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => $headers,
	);
	$request_url     = shipsyGetEndpoint( 'REGISTER_SHOP_API' );
	// error_log($request_url);
	$response              = wp_remote_post( $request_url, $args );
	$result                = wp_remote_retrieve_body( $response );
	$resultdata            = json_decode( $result, true );
	$notifications         = array();
	$notifications['page'] = 'shipsy-configuration';

	if ( array_key_exists( 'data', $resultdata ) ) {
		if ( array_key_exists( 'access_token', $resultdata['data'] ) ) {
			$accesstoken              = $resultdata['data']['access_token'];
			$notifications['success'] = 'Configuration is successful';
			setcookie( 'access_token', $accesstoken, shipsyGetCookieTTL() );

			// if registration is successful store the org-id in cookies
			setOrgId( $postRequestParams['org_id'] );
			setDownloadLabelOption(
				( array_key_exists( 'download_label_option', $postRequestParams ) &&
				 $postRequestParams['download_label_option'] ) ? 1 : 0
			);
		}
		if ( array_key_exists( 'customer', $resultdata['data'] ) &&
			array_key_exists( 'id', $resultdata['data']['customer'] ) &&
			array_key_exists( 'code', $resultdata['data']['customer'] ) ) {
			$customerId   = $resultdata['data']['customer']['id'];
			$customerCode = $resultdata['data']['customer']['code'];
			setcookie( 'cust_id', $customerId, shipsyGetCookieTTL() );
			setcookie( 'cust_code', $customerCode, shipsyGetCookieTTL() );
		}
	} else {
		$notifications['failure'] = $resultdata['error']['message'];
	}
	wp_safe_redirect( add_query_arg( $notifications, admin_url( 'admin.php' ) ) );

}

function dtdcUpdateAddresses( $postRequestParams ) {

	$headers = array(
		'Content-Type'    => 'application/json',
		'organisation-id' => getOrgId(),
		'shop-origin'     => 'wordpress',
		'shop-url'        => dtdcGetShopUrl(),
		'customer-id'     => dtdcGetCustId(),
		'access-token'    => dtdcGetAccessToken(),
	);

	if ( isset( $postRequestParams['useForwardCheck'] ) && $postRequestParams['useForwardCheck'] === 'true' ) {
		$useForwardAddress = true;
		$reverseAddress    = array(
			'name'            => $postRequestParams['forward-name'],
			'phone'           => $postRequestParams['forward-phone'],
			'alternate_phone' => $postRequestParams['forward-alt-phone'] ?? '',
			'address_line_1'  => $postRequestParams['forward-line-1'],
			'address_line_2'  => $postRequestParams['forward-line-2'],
			'pincode'         => $postRequestParams['forward-pincode'],
			'city'            => $postRequestParams['forward-city'],
			'state'           => $postRequestParams['forward-state'],
			'country'         => $postRequestParams['forward-country'],
		);
	} else {
		$useForwardAddress = false;
		$reverseAddress    = array(
			'name'            => $postRequestParams['reverse-name'],
			'phone'           => $postRequestParams['reverse-phone'],
			'alternate_phone' => $postRequestParams['reverse-alt-phone'] ?? '',
			'address_line_1'  => $postRequestParams['reverse-line-1'],
			'address_line_2'  => $postRequestParams['reverse-line-2'],
			'pincode'         => $postRequestParams['reverse-pincode'],
			'city'            => $postRequestParams['reverse-city'],
			'state'           => $postRequestParams['reverse-state'],
			'country'         => $postRequestParams['reverse-country'],
		);
	}
	$dataToSendArray = array(
		'forwardAddress'           => array(
			'name'            => $postRequestParams['forward-name'],
			'phone'           => $postRequestParams['forward-phone'],
			'alternate_phone' => $postRequestParams['forward-alt-phone'] ?? '',
			'address_line_1'  => $postRequestParams['forward-line-1'],
			'address_line_2'  => $postRequestParams['forward-line-2'],
			'pincode'         => $postRequestParams['forward-pincode'],
			'city'            => $postRequestParams['forward-city'],
			'state'           => $postRequestParams['forward-state'],
			'country'         => $postRequestParams['forward-country'],
		),
		'reverseAddress'           => $reverseAddress,
		'useForwardAddress'        => $useForwardAddress,
		'exceptionalReturnAddress' => array(
			'name'            => $postRequestParams['exp-return-name'],
			'phone'           => $postRequestParams['exp-return-phone'],
			'alternate_phone' => $postRequestParams['exp-return-alt-phone'] ?? '',
			'address_line_1'  => $postRequestParams['exp-return-line-1'],
			'address_line_2'  => $postRequestParams['exp-return-line-2'],
			'pincode'         => $postRequestParams['exp-return-pincode'],
			'city'            => $postRequestParams['exp-return-city'],
			'state'           => $postRequestParams['exp-return-state'],
			'country'         => $postRequestParams['exp-return-country'],
		),
	);

	// Return address is required iff the Ord Id is 1
	if ( getOrgId() == '1' ) {
		$dataToSendArray['returnAddress'] = array(
			'name'            => $postRequestParams['return-name'],
			'phone'           => $postRequestParams['return-phone'],
			'alternate_phone' => $postRequestParams['return-alt-phone'] ?? '',
			'address_line_1'  => $postRequestParams['return-line-1'],
			'address_line_2'  => $postRequestParams['return-line-2'],
			'pincode'         => $postRequestParams['return-pincode'],
			'city'            => $postRequestParams['return-city'],
			'state'           => $postRequestParams['return-state'],
			'country'         => $postRequestParams['return-country'],
		);
	}
	// error_log(print_r($dataToSendArray, true));
	$dataToSendJson = wp_json_encode( $dataToSendArray );

	$args        = array(
		'body'        => $dataToSendJson,
		'timeout'     => '10',
		'redirection' => '10',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => $headers,
	);
	$request_url = shipsyGetEndpoint( 'UPDATE_ADDRESS_API' );
	$response    = wp_remote_post( $request_url, $args );
	$result      = wp_remote_retrieve_body( $response );
	$array2      = json_decode( $result, true );

	$notifications         = array();
	$notifications['page'] = 'shipsy-setup';
	if ( is_array( $array2 ) ) {
		if ( array_key_exists( 'success', $array2 ) ) {
			if ( $array2['success'] == 1 ) {
				$notifications['success'] = 'Setup is Succesful';
			}
		} else {
			$notifications['failure'] = $array2['error']['message'];
		}
	}
	wp_safe_redirect( add_query_arg( $notifications, admin_url( 'admin.php' ) ) );

}

function dtdcGetCustId() {
	return isset( $_COOKIE['cust_id'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['cust_id'] ) ) : null;
}

function dtdcGetAccessToken() {
	return isset( $_COOKIE['access_token'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['access_token'] ) ) : null;
}

function dtdcGetCustCode() {
	return isset( $_COOKIE['cust_code'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['cust_code'] ) ) : null;
}

function dtdcGetShopUrl() {
	return get_bloginfo( 'wpurl' );
}

function setOrgId( $org_id ) {
	// save org id to cookies for 30 days
	setcookie( 'org_id', $org_id, shipsyGetCookieTTL() );
}

function getOrgId() {
	return isset( $_COOKIE['org_id'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['org_id'] ) ) : null;
}

function setDownloadLabelOption( $val ) {
	require_once DTDC_ECONNECT_PATH . 'utils/db/class-shipsydbconnector.php';

	$dbconnector = ShipsyDBConnector::get_instance();
	$table       = 'options';

	$exists = getDownloadLabelOption();
	if ( is_null( $exists ) ) {
		// error_log("adding");
		$data   = array(
			'option_name'  => 'shipsy_download_label_option',
			'option_value' => $val,
		);
		$format = array( '%s', '%d' );
		$dbconnector->write( $table, $data, $format );
	} else {
		// error_log("updating");
		$data    = array( 'option_value' => $val );
		$where   = array( 'option_name' => 'shipsy_download_label_option' );
		$format  = array( '%d' );
		$wformat = array( '%s' );
		$dbconnector->update( $table, $data, $where, $format, $wformat );
	}
}

function getDownloadLabelOption() {
	require_once DTDC_ECONNECT_PATH . 'utils/db/class-shipsydbconnector.php';

	$dbconnector = ShipsyDBConnector::get_instance();
	$table       = 'options';
	$col         = 'option_value';
	$where       = array(
		'option_name' => 'shipsy_download_label_option',
	);
	return $dbconnector->read( $table, $col, $where );
}

function dtdcAddSynctrack( $data ) {
	require_once DTDC_ECONNECT_PATH . 'utils/db/class-shipsydbconnector.php';

	$dbconnector = ShipsyDBConnector::get_instance();
	$table       = 'sync_track_order';
	$data        = array(
		'orderId'      => $data['orderId'],
		'shipsy_refno' => $data['shipsy_refno'],
	);
	$format      = array( '%s', '%s' );
	$dbconnector->write( $table, $data, $format );
}

function dtdcGetShipsyRefNo( $orderId ) {
	require_once DTDC_ECONNECT_PATH . 'utils/db/class-shipsydbconnector.php';

	$dbconnector = ShipsyDBConnector::get_instance();
	$table       = 'sync_track_order';
	$col         = 'shipsy_refno';
	$where       = array(
		'orderId' => $orderId,
	);
	return $dbconnector->read( $table, $col, $where );
}

function dtdcGetTrackingUrl( $orderId ) {
	require_once DTDC_ECONNECT_PATH . 'utils/db/class-shipsydbconnector.php';

	$dbconnector = ShipsyDBConnector::get_instance();
	$table       = 'sync_track_order';
	$col         = 'track_url';
	$where       = array(
		'orderId' => $orderId,
	);
	return $dbconnector->read( $table, $col, $where );
}

function addTrackingUrl( $orderId ) {
	global $wpdb;
	$headers            = array(
		'Content-Type'    => 'application/json',
		'organisation-id' => getOrgId(),
		'shop-origin'     => 'wordpress',
		'shop-url'        => dtdcGetShopUrl(),
		'customer-id'     => dtdcGetCustId(),
		'access-token'    => dtdcGetAccessToken(),
	);
	$data['cust_refno'] = $orderId;
	$dataToSendJson     = wp_json_encode( array( 'customerReferenceNumberList' => array( $data['cust_refno'] ) ) );
	$args               = array(
		'body'        => $dataToSendJson,
		'timeout'     => '10',
		'redirection' => '10',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => $headers,
	);
	$request_url        = shipsyGetEndpoint( 'TRACKING_API' );
	$response           = wp_remote_post( $request_url, $args );
	$result             = wp_remote_retrieve_body( $response );
	$array2             = json_decode( $result, true );
	if ( ! empty( $array2['data'] ) && $array2['success'] == 1 ) {
		$track_url = $array2['data'][ $orderId ];

		$dbconnector  = ShipsyDBConnector::get_instance();
		$table        = 'sync_track_order';
		$data         = array(
			'track_url' => $track_url,
		);
		$where        = array(
			'orderId' => $orderId,
		);
		$format       = array( '%s' );
		$where_format = array( '%s' );
		$dbconnector->update( $table, $data, $where, $format, $where_format );
		return true;
	} else {
		return false;
	}
}

function dtdcSoftdataapi( $postRequestParams ) {
	// print_r($postRequestParams);
	$postRequestParams = shipsySanitizeArray( $postRequestParams );
	$postRequestParams = shipsyValidateConsignmentAddresses( $postRequestParams );

	$samePiece = false;
	if ( array_key_exists( 'multiPieceCheck', $postRequestParams ) ) {
		$samePiece = true;
	}
	$result = dtdcGetAddresses();
	$result = $result['data'];
	$result = shipsyValidateCustomerAddressess( $result );
	// print_r($result);

	$dataToSendArray = array(
		'consignments' => array(
			array(
				'customer_code'              => dtdcGetCustCode(),
				'consignment_type'           => $postRequestParams['consignment-type'],
				'service_type_id'            => $postRequestParams['service-type'],
				'reference_number'           => '',
				'load_type'                  => $postRequestParams['courier-type'],
				'customer_reference_number'  => $postRequestParams['customer-reference-number'],
				'commodity_name'             => 'Other',
				'num_pieces'                 => $postRequestParams['num-pieces'],
				'origin_details'             => array(
					'name'            => $postRequestParams['origin-name'],
					'phone'           => $postRequestParams['origin-number'],
					'alternate_phone' => ( $postRequestParams['origin-alt-number'] == '' ) ? $postRequestParams['origin-number'] : $postRequestParams['origin-alt-number'],
					// 'alternate_phone' => $postRequestParams['origin-alt-number'],
					'address_line_1'  => $postRequestParams['origin-line-1'],
					'address_line_2'  => $postRequestParams['origin-line-2'],
					'pincode'         => $postRequestParams['origin-pincode'] ?? '',
					'city'            => $postRequestParams['origin-city'],
					'state'           => $postRequestParams['origin-state'],
					'country'         => $postRequestParams['origin-country'],
				),
				'destination_details'        => array(
					'name'            => $postRequestParams['destination-name'],
					'phone'           => $postRequestParams['destination-number'],
					'alternate_phone' => ( $postRequestParams['destination-alt-number'] == '' ) ? $postRequestParams['destination-number'] : $postRequestParams['destination-alt-number'],
					'address_line_1'  => $postRequestParams['destination-line-1'],
					'address_line_2'  => $postRequestParams['destination-line-2'],
					'pincode'         => $postRequestParams['destination-pincode'] ?? '',
					'city'            => $postRequestParams['destination-city'],
					'state'           => $postRequestParams['destination-state'],
					'country'         => $postRequestParams['destination-country'],
				),
				'same_pieces'                => $samePiece,
				'cod_favor_of'               => '',
				'pieces_detail'              => array(),
				'cod_collection_mode'        => ( $postRequestParams['cod-collection-mode'] == 'cod' ) ? 'cash' : '',
				'cod_amount'                 => $postRequestParams['cod-amount'],
				'return_details'             => array(
					'name'            => $result['reverseAddress']['name'],
					'phone'           => $result['reverseAddress']['phone'],
					'alternate_phone' => $result['reverseAddress']['alternate_phone'],
					'address_line_1'  => $result['reverseAddress']['address_line_1'],
					'address_line_2'  => $result['reverseAddress']['address_line_2'],
					'pincode'         => $result['reverseAddress']['pincode'],
					'city'            => $result['reverseAddress']['city'],
					'state'           => $result['reverseAddress']['state'],
				),
				'rto_details'                => array(
					'name'            => $result['returnAddress']['name'],
					'phone'           => $result['returnAddress']['phone'],
					'alternate_phone' => $result['returnAddress']['alternate_phone'],
					'address_line_1'  => $result['returnAddress']['address_line_1'],
					'address_line_2'  => $result['returnAddress']['address_line_2'],
					'pincode'         => $result['returnAddress']['pincode'],
					'city'            => $result['returnAddress']['city'],
					'state'           => $result['returnAddress']['state'],
				),
				'exceptional_return_details' => array(
					'name'            => $result['exceptionalReturnAddress']['name'],
					'phone'           => $result['exceptionalReturnAddress']['phone'],
					'alternate_phone' => $result['exceptionalReturnAddress']['alternate_phone'],
					'address_line_1'  => $result['exceptionalReturnAddress']['address_line_1'],
					'address_line_2'  => $result['exceptionalReturnAddress']['address_line_2'],
					'pincode'         => $result['exceptionalReturnAddress']['pincode'],
					'city'            => $result['exceptionalReturnAddress']['city'],
					'state'           => $result['exceptionalReturnAddress']['state'],
				),
			),
		),
	);

	if ( $postRequestParams['num-pieces'] === 1 || $samePiece === true ) {
		$temp_pieces_details = array(
			'description'    => $postRequestParams['description'],
			'declared_value' => $postRequestParams['declared-value'],
			'weight'         => (float) $postRequestParams['weight'],
			'height'         => (float) $postRequestParams['height'],
			'length'         => (float) $postRequestParams['length'],
			'width'          => (float) $postRequestParams['width'],
		);
		array_push( $dataToSendArray['consignments'][0]['pieces_detail'], $temp_pieces_details );
	} else {
		for ( $index = 0; $index < $postRequestParams['num-pieces']; $index++ ) {
			$temp_pieces_details = array(
				'description'    => $postRequestParams['description'][ $index ],
				'declared_value' => $postRequestParams['declared-value'][ $index ],
				'weight'         => (float) $postRequestParams['weight'][ $index ],
				'height'         => (float) $postRequestParams['height'][ $index ],
				'length'         => (float) $postRequestParams['length'][ $index ],
				'width'          => (float) $postRequestParams['width'][ $index ],
			);
			array_push( $dataToSendArray['consignments'][0]['pieces_detail'], $temp_pieces_details );
		};
	}

	$headers        = array(
		'Content-Type'    => 'application/json',
		'organisation-id' => getOrgId(),
		'shop-origin'     => 'wordpress',
		'shop-url'        => dtdcGetShopUrl(),
		'customer-id'     => dtdcGetCustId(),
		'access-token'    => dtdcGetAccessToken(),
	);
	$dataToSendJson = wp_json_encode( $dataToSendArray );
	$args           = array(
		'body'        => $dataToSendJson,
		'timeout'     => '50',
		'redirection' => '50',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => $headers,
	);
	// print_r($dataToSendJson);
	$request_url                = shipsyGetEndpoint( 'SOFTDATA_API' );
	$response                   = wp_remote_post( $request_url, $args );
	$result                     = wp_remote_retrieve_body( $response );
	$array                      = json_decode( $result, true );
	$data                       = array();
	$data['orderId']            = $postRequestParams['customer-reference-number'];
	$notifications              = array();
	$notifications['post_type'] = 'shop_order';

	/*
	Order sync is successful iff,
		- we have data in response
		- the data has success set as true
		- there is a non empty value for `reference_number` key
	Please refer to https://shipsy.atlassian.net/browse/DTDCSPT-2057 for the
	issue that occurs when not doing so.
	*/
	// error_log(print_r($response, true));
	// error_log(print_r($array, true));

	if ( ( array_key_exists( 'data', $array ) && $array['data'][0]['success'] ) &&
		( array_key_exists( 'reference_number', $array['data'][0] ) &&
			strlen( $array['data'][0]['reference_number'] ) > 0 ) ) {
		$data['shipsy_refno'] = $array['data'][0]['reference_number'];
		dtdcAddSynctrack( $data );
		$notifications['success'] = 'Order is Synced Successfully!';
	} else {
		if ( array_key_exists( 'data', $array ) && array_key_exists( 'message', $array['data'][0] ) ) {
			$notifications['message'] = $array['data'][0]['message'];
		} else {
			$notifications['failure'] = $array['error']['message'];
		}
	}

	wp_safe_redirect( add_query_arg( $notifications, admin_url( 'edit.php' ) ) );
}
