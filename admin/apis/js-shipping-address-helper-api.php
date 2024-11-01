<?php
include_once(DTDC_ECONNECT_PATH."admin/helper/helper.php");

$request = shipsySanitizeArray($_REQUEST);
$order_id = $request['order_id'];

$result = array();

if(strlen($order_id) > 0) {
    $order = wc_get_order(sanitize_text_field($order_id));

    // header('Content-Type: application/json');
    if (!is_null($order)) {
        $shippingAddress = array();
        $shippingAddress['name'] = $order->get_formatted_shipping_full_name();
        $shippingAddress['state'] = $order->get_shipping_state();
        $shippingAddress['country'] = $order->get_shipping_country();
        $shippingAddress['city'] = $order->get_shipping_city();
        $shippingAddress['pincode'] = $order->get_shipping_postcode();
        $shippingAddress['address_1'] = $order->get_shipping_address_1();
        $shippingAddress['address_2'] = $order->get_shipping_address_2();
        $shippingAddress['phone'] = $order->get_billing_phone();

        $shippingAddress = shipsySanitizeArray($shippingAddress);

        $result['data'] = array(
            'shipping_address' => $shippingAddress,
            'success' => true
        );
    }
    else {
        $result['error'] = array(
            'message' => 'Invalid Order Id requested',
            'success' => false
        );
    }
}
else {
    $result['error'] = array(
        'message' => 'Invalid Order Id requested',
        'success' => false
    );
}

wp_send_json($result);