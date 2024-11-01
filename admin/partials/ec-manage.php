<?php

	require_once DTDC_ECONNECT_PATH . 'admin/helper/helper.php';

if ( ! isset( $_GET['synced_orders'] ) ) {
	?>
		<div class="alert alert-danger" role="alert"><?php echo esc_html( 'No Synced orders found!' ); ?></div>
		<?php
		return;
} elseif ( ! isset( $_COOKIE['org_id'] ) ) {
	?>
		<div class="alert alert-danger" role="alert"><?php echo esc_html( 'Organisation Id not found!' ); ?></div>
		<?php
		return;
}

	$synced_orders   = shipsySanitizeArray( wp_unslash( $_GET['synced_orders'] ) ); //phpcs:ignore
	$response        = dtdcGetAwbNumber( $synced_orders );
	$organisation_id = sanitize_text_field( wp_unslash( $_COOKIE['org_id'] ) );
	$shopUrl         = dtdcGetShopUrl();
if ( array_key_exists( 'data', $response ) && ! empty( $response['data'] ) ) {
	$orderDetails = $response['data'];

	?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<h4>Manage Orders</h4>
		</div>
	</div>   
<div class="table-responsive">
	<table class="table table-hover"> 
		<thead class="thead-dark">
		<tr> 
			<th scope="col">#</th> 
			<th scope="col">AWB Number</th> 
			<th scope="col">Type</th>
			<th scope="col">Status</th>
			<th scope="col">Pieces</th>
			<th scope="col">Shipping Label</th>
			<th scope="col">Cancel</th>
		</tr> 
		</thead>
		<tbody>
		<?php foreach ( $orderDetails as $magentoOrderNumber => $singleOrderDetail ) : ?>
				<?php foreach ( $singleOrderDetail as $key => $data ) : ?>
				<tr scope="row">
					<td><?php echo esc_html( $magentoOrderNumber ); ?></td>
					<td><?php echo esc_html( $data['reference_number'] ); ?></td>
					<td><?php echo esc_html( $data['consignment_type'] ); ?></td>
					<td><?php echo esc_html( $data['status'] ); ?></td>
					<td><?php echo esc_html( $data['num_pieces'] ); ?></td>


				
					<?php if ( $data['status'] === 'cancelled' ) { ?>
					<td><button type="button"  id="<?php echo esc_attr( $data['reference_number'] ); ?>" onclick="getShippingLabel('<?php echo esc_html( $data['reference_number'] ); ?>','<?php echo esc_html( $shopUrl ); ?>','<?php echo esc_html( $data['reference_number'] ); ?>');" class="btn btn-primary">GET</button></td>
					<?php } else { ?>
					<td><button type="button"  id="<?php echo esc_attr( $data['reference_number'] ); ?>" onclick="getShippingLabel('<?php echo esc_html( $data['reference_number'] ); ?>','<?php echo esc_html( $shopUrl ); ?>','<?php echo esc_html( $data['reference_number'] ); ?>');" class="btn btn-primary">GET</button></td>
					<?php } ?>
					<?php if ( $data['status'] === 'cancelled' ) { ?>
						<td><a data-toggle="tooltip"  class="btn btn-danger" disabled>Cancelled</a></td>
					<?php } else { ?>
					<td><button type="button" id="cancel<?php echo esc_attr( $data['reference_number'] ); ?>" class="btn btn-danger" onclick="cancelOrderOnClick('<?php echo esc_html( $data['reference_number'] ); ?>','<?php echo esc_html( $shopUrl ); ?>','cancel<?php echo esc_html( $data['reference_number'] ); ?>');">Cancel</button></td>
					<?php } ?>
  
				</tr>
			<?php endforeach; ?>
			<?php endforeach; ?>
		</tbody>
	</table> 
</div>
<div id="popup-modal" style="display:none;">
	<h3> Are you sure you want to cancel the order ? </h3>
</div>
 
	<?php
} elseif ( array_key_exists( 'data', $response ) && empty( $response['data'] ) ) {
	?>
		<div class="alert alert-danger" role="alert">No AWB Numbers found</div>
	<?php
} elseif ( array_key_exists( 'error', $response ) ) {
	?>
		<div class="alert alert-danger" role="alert"><?php echo esc_html( shipsyParseResponseError( $response['error'] ) ); ?></div>
	<?php
}
?>
