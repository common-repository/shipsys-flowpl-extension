<?php
	require_once DTDC_ECONNECT_PATH . 'admin/helper/helper.php';
	$response = dtdcGetVirtualSeries();
if ( array_key_exists( 'data', $response ) && ! empty( $response['data'] ) ) {
	$virtualSeriesArray = $response['data'];
	?>
<div id="content">
	<div class="container-fluid">
		<div class="card" style="max-width: 98rem;">
		  <div class="card-header">
		  <h4 class="card-title"><i class="fa fa-spinner"></i> Virtual Series</h4>
		</div>
	<div class="card-body">
	<div class="table-responsive custom-class">
			<table class="table table-hover"> 
				<thead class="thead-dark">
				<tr> 
					<th scope="col">Service Types</th> 
					<th scope="col">Prefix</th> 
					<th scope="col">Start</th>
					<th scope="col">End</th>
					<th scope="col">Counter</th>
					<th scope="col">Available Count</th>
				</tr> 
				</thead>
				<tbody>
				<?php foreach ( $virtualSeriesArray as $virtualSeries ) : ?>
						<tr scope="row">
							<td><?php echo esc_html( sanitize_text_field( implode( ', ', $virtualSeries['serviceType'] ) ) ); ?></td>
							<td><?php echo esc_html( sanitize_text_field( $virtualSeries['prefix'] ) ); ?></td>
							<td><?php echo esc_html( sanitize_text_field( $virtualSeries['start'] ) ); ?></td>
							<td><?php echo esc_html( sanitize_text_field( $virtualSeries['end'] ) ); ?></td>
							<td><?php echo esc_html( sanitize_text_field( $virtualSeries['counter'] ) ); ?></td>
							<td><?php echo esc_html( sanitize_text_field( $virtualSeries['availableCount'] ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table> 
		</div>
	<?php
} elseif ( array_key_exists( 'data', $response ) && empty( $response['data'] ) ) {
	?>
		<div class="alert alert-warning" role="alert">No virtual series alloted</div>
	<?php
} elseif ( array_key_exists( 'error', $response ) ) {
	?>
		<div class="alert alert-danger" role="alert"><?php echo esc_html( sanitize_text_field( shipsyParseResponseError( $response['error'] ) ) ); ?></div>
	<?php
}
?>
