<?php

require(DTDC_ECONNECT_PATH . 'config/settings.php');
include_once(DTDC_ECONNECT_PATH."admin/helper/helper.php");

$request = shipsySanitizeArray($_REQUEST);
$api = $request['api'];

// header('Content-Type: application/json');
$result = array();

if(array_key_exists($api, $ENDPOINTS)) {
    $result['data'] = array(
        'url' => $ENDPOINTS[$api],
        'success' => true
    );
}
else {
    $result['error'] = array(
        'message' => 'Invalid API requested',
        'success' => false
    );
}
wp_send_json($result);