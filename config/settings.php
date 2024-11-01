<?php

// This setting file should contain the base url of the backend application.

require 'base.php';

$BASE_URL = 'https://app.shipsy.in';

foreach ( $ENDPOINTS as $API => $URL ) {
	$ENDPOINTS[ $API ] = $BASE_URL . $URL;
}


/*
Unset the local variables after use, or else they will leak into the files where
we include this file
*/
unset( $API );
unset( $URL );
