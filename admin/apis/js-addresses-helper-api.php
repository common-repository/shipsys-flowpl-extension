<?php

include_once(DTDC_ECONNECT_PATH."admin/helper/helper.php");

$addresses = dtdcGetAddresses();
$addresses = shipsySanitizeArray($addresses);

// header('Content-Type: application/json');
$result = array();

if(array_key_exists('data', $addresses) && !is_null($addresses['data'])) {
    $all_addresses = shipsyValidateCustomerAddressess($addresses['data']);
    $result['data'] = array(
        'addresses' => $all_addresses,
        'success' => true
    );
}
else {
    $result['error'] = array(
        'message' => 'Invalid Request please try after sometime',
        'success' => false
    );
}
wp_send_json($result);