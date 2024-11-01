<?php
	require_once DTDC_ECONNECT_PATH . 'admin/helper/helper.php';
	$response = dtdcGetAddresses();
if ( array_key_exists( 'data', $response ) && ! empty( $response['data'] ) ) {
	$allAddresses             = $response['data'];
	$forwardAddress           = ( array_key_exists( 'forwardAddress', $allAddresses ) ) ? $allAddresses['forwardAddress'] : array();
	$reverseAddress           = ( array_key_exists( 'reverseAddress', $allAddresses ) ) ? $allAddresses['reverseAddress'] : array();
	$exceptionalReturnAddress = ( array_key_exists( 'exceptionalReturnAddress', $allAddresses ) ) ? $allAddresses['exceptionalReturnAddress'] : array();
	$returnAddress            = ( array_key_exists( 'returnAddress', $allAddresses ) ) ? $allAddresses['returnAddress'] : array();

	?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="float-right">
			</div>
			<h3>Setup</h3>
		</div>
	</div>   
	<div class="container-fluid">
		<div class="card" style="max-width: 78rem;">
			<div class="card-header">
				<h5 class="card-title">
					<i class="fa fa-pencil"></i>Kindly fill details for Setup</h5>
			</div>
			<div class="card-body">
			<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
			<input type="hidden" name="action" value="on_setup_submit" />
				<div class="form-group" id="forward-address" style = "padding  : 15px">
				<div class  ="header-style" style = "width : 90% !important">
					<span class = "header-font">Forward Details</span>
				</div>
			<div class="container" style = "margin-left : 0px">
			<div class = "row">
				<div class = "col-sm-4">
					<label for="forward-name" class = "label-font">Name <span class = "required-text">*</span></label>
					<input type="text" required="true" id="forward-name" name="forward-name" class="form-control  " style= "border-radius:0px" value="<?php echo esc_attr( $forwardAddress['name'] ?? '' ); ?>" required>
					<div class="nameErrorText" style = "color : red ; font-size : 10px;display:none">Name is required</div>
				</div> 
				<div class = "col-sm-3">
					<label for="forward-phone" class = "label-font">Phone Number <span class = "required-text">*</span></label>
					<input type="tel" pattern="\d*" required="true" id="forward-phone" name="forward-phone" style= "border-radius:0px"class="form-control  " value="<?php echo esc_attr( $forwardAddress['phone'] ?? '' ); ?>">
					<div class="phoneErrorText" style = "color : red ; font-size : 10px;display:none">Phone Number is required</div>
				</div> 
				<div class = "col-sm-3">
					<label for="forward-alt-phone" class = "label-font">Alternate Phone Number</label>
					<input type="tel" pattern="\d*" id="forward-alt-phone" name="forward-alt-phone" style= "border-radius:0px"  class="form-control  " value="<?php echo esc_attr( $forwardAddress['alternate_phone'] ?? '' ); ?>">
				</div> 
				<div class = "col-sm-2"></div> 
			</div>
			<div class = "row mt-4">
				<div class = "col-sm-5">
					<label for="forward-line-1" class = "label-font">Address Line 1 <span class = "required-text">*</span></label>
					<input type="text" required="true" id="forward-line-1" name="forward-line-1"style= "border-radius:0px"  class="form-control  " value="<?php echo esc_attr( $forwardAddress['address_line_1'] ?? '' ); ?>">
					<div class="addressErrorText" style = "color : red ; font-size : 10px;display:none">Address is required</div>
				</div>
				<div class = "col-sm-5"> 
					<label for="forward-line-2" class = "label-font">Address Line 2</label>
					 <input type="text" id="forward-line-2" name="forward-line-2"style= "border-radius:0px"  class="form-control  " value="<?php echo esc_attr( $forwardAddress['address_line_2'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-2"> </div>
			</div>
			<div class  ="row mt-4">
				<div class = "col-sm-2">
					<label for="forward-city" class = "label-font">City</label>
					 <input type="text" id="forward-city" name="forward-city"style= "border-radius:0px"  class="form-control  " value="<?php echo esc_attr( $forwardAddress['city'] ?? '' ); ?>">
					</div>
				<div class = "col-sm-3">
					<label for="forward-state" class = "label-font">State</label>
					 <input type="text" id="forward-state" name="forward-state"style= "border-radius:0px"  class="form-control  " value="<?php echo esc_attr( $forwardAddress['state'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-2">
					<label for="forward-country" class = "label-font">Country <span class = "required-text">*</span></label>
					<input type="text" required="true" id="forward-country" name="forward-country"style= "border-radius:0px"  class="form-control  " value="<?php echo esc_attr( $forwardAddress['country'] ?? '' ); ?>">
					<div class="countryErrorText" style = "color : red ; font-size : 10px;display:none">Country is required</div>
				</div>
				<div class = "col-sm-3">
					<label for="forward-pincode" class = "label-font">Pincode</label>
					<input type="text" id="forward-pincode" name="forward-pincode"style= "border-radius:0px"  class="form-control  " value="<?php echo esc_attr( $forwardAddress['pincode'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-2"></div>
			</div>
		</div>

	</div>
	<div class="form-group" id="reverse-address" style = "padding : 15px">
		<div class  ="header-style">
			<span class = "header-font">Reverse Details</span>
		</div>

		<div class="container-fluid" style = "margin-left : 0px">
			<div class = "row">
				<div class = "col-sm-4">
					<label for="reverse-name" class = "label-font">Name <span class = "required-text">*</span></label>
					<input type="text" required="true" id="reverse-name" name="reverse-name" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $reverseAddress['name'] ?? '' ); ?>">
					<div class="rNameErrorText" style = "color : red ; font-size : 10px;display:none">Name is required</div>

				</div> 
				<div class = "col-sm-3">

					 <label for="reverse-phone" class = "label-font">Phone Number <span class = "required-text">*</span></label>
					<input type="tel" pattern="\d*" required="true" id="reverse-phone" name="reverse-phone" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $reverseAddress['phone'] ?? '' ); ?>">
					<div class="rPhoneErrorText" style = "color : red ; font-size : 10px;display:none">Phone number is required</div>

				</div> 
				<div class = "col-sm-3">
					<label for="reverse-alt-phone"class = "label-font">Alternate Phone Number</label>
					<input type="tel" pattern="\d*" id="reverse-alt-phone" name="reverse-alt-phone" style = "border-radius:0px"class="form-control  " value="<?php echo esc_attr( $reverseAddress['alternate_phone'] ?? '' ); ?>">
				</div> 
				<div class = "col-sm-2"></div> 
			</div>
			<div class = "row mt-4">
				<div class = "col-sm-5">
					<label for="reverse-line-1" class = "label-font">Address Line 1 <span class = "required-text">*</span></label>
					<input type="text" required="true" id="reverse-line-1" name="reverse-line-1" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $reverseAddress['address_line_1'] ?? '' ); ?>">
					<div class="rAddressErrorText" style = "color : red ; font-size : 10px;display:none">Address is required</div>

				</div>
				<div class = "col-sm-5"> 
					<label for="reverse-line-2" class = "label-font">Address Line 2</label>
					<input type="text" id="reverse-line-2" name="reverse-line-2" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $reverseAddress['address_line_2'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-2"> </div>
			</div>
			<div class  ="row mt-4">
				<div class = "col-sm-2">
					<label for="reverse-city" class = "label-font">City</label>
					 <input type="text" id="reverse-city" name="reverse-city" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $reverseAddress['city'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-3">
						<label for="reverse-state" class = "label-font"> State</label>
						<input type="text" id="reverse-state" name="reverse-state" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $reverseAddress['state'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-2">
					<label for="reverse-country" class = "label-font">Country <span class = "required-text">*</span></label>
					 <input type="text"  required="true" id="reverse-country" name="reverse-country" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $reverseAddress['country'] ?? '' ); ?>">
					 <div class="rCountryErrorText" style = "color : red ; font-size : 10px;display:none">Country is required</div>

				</div>
				<div class = "col-sm-3">
					<label for="reverse-pincode" class = "label-font"> Pincode</label>
					 <input type="text" id="reverse-pincode" name="reverse-pincode"  style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $reverseAddress['pincode'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-2"></div>
			</div>
		</div>
	</div>

	<?php if ( getOrgId() == '1' ) { ?>
	<div class="form-group" id="return-address" style = "padding  : 15px">
		<div class  ="header-style">
			<span class = "header-font">Return Details</span>
		</div>
		<div class="container-fluid" style = "margin-left : 0px">
			<div class = "row">
				<div class = "col-sm-4">
					<label for="return-name" class = "label-font">Name <span class = "required-text">*</span></label>
					<input type="text" required="true" id="return-name" name="return-name" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $returnAddress['name'] ?? '' ); ?>">
					<div class="reNameErrorText" style = "color : red ; font-size : 10px;display:none">Name is required</div>
				</div> 
				<div class = "col-sm-3">
					<label for="return-phone" class = "label-font">Phone Number <span class = "required-text">*</span></label>
					<input type="tel" pattern="\d*" required="true"  id="return-phone" name="return-phone" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $returnAddress['phone'] ?? '' ); ?>">
					<div class="rePhoneErrorText" style = "color : red ; font-size : 10px;display:none">Phone number is required</div>

				</div> 
				<div class = "col-sm-3">
					<label for="return-alt-number" class = "label-font">Alternate Phone Number</label>
					<input type="tel" pattern="\d*" id="return-alt-phone" name="return-alt-phone" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $returnAddress['alternate_phone'] ?? '' ); ?>">
				</div> 
				<div class = "col-sm-2"></div> 
			</div>
			<div class = "row mt-4">
				<div class = "col-sm-5">
					<label for="return-line-1" class = "label-font">Address Line 1 <span class = "required-text">*</span></label>
					<input type="text" required="true" id="return-line-1" name="return-line-1" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $returnAddress['address_line_1'] ?? '' ); ?>">
					<div class="reAddressErrorText" style = "color : red ; font-size : 10px;display:none">Address is required</div>
				</div>
				<div class = "col-sm-5"> 
					<label for="return-line-2" class = "label-font">Address Line 2</label>
					<input type="text" id="return-line-2" name="return-line-2" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $returnAddress['address_line_2'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-2"> </div>
			</div>
			<div class  ="row mt-4">
				<div class = "col-sm-2">
					<label for="return-city" class = "label-font">City</label>
					<input type="text" id="return-city" name="return-city" style = "border-radius:0px"class="form-control  " value="<?php echo esc_attr( $returnAddress['city'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-3">
					<label for="return-state" class = "label-font">State</label>
					<input type="text" id="return-state" name="return-state" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $returnAddress['state'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-2">
					<label for="return-country" class = "label-font">Country <span class = "required-text">*</span></label>
					 <input type="text" required="true" id="return-country" name="return-country" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $returnAddress['country'] ?? '' ); ?>">
					 <div class="reCountryErrorText" style = "color : red ; font-size : 10px;display:none">Country is required</div>
					</div>
				<div class = "col-sm-3">
					<label for="return-pincode" class = "label-font">Pincode</label>
					<input type="text" id="return-pincode" name="return-pincode" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $returnAddress['pincode'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-2"></div>
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="form-group" id="exp-reverse-address" style = "padding  : 15px">
		<div class  ="header-style">
			<span class = "header-font">Exceptional Return Details</span>
		</div>
		<div class="container-fluid" style = "margin-left : 0px">
			<div class = "row">
				<div class = "col-sm-4">
					<label for="exp-return-name" class = "label-font">Name <span class = "required-text">*</span></label>
					<input type="text" required="true" id="exp-return-name" name="exp-return-name" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $exceptionalReturnAddress['name'] ?? '' ); ?>">
					<div class="eNameErrorText" style = "color : red ; font-size : 10px;display:none">Name is required</div>
				</div> 
				<div class = "col-sm-3">
					<label for="exp-return-phone" class = "label-font">Phone Number <span class = "required-text">*</span></label>
					<input type="tel" pattern="\d*" required="true"  id="exp-return-phone" name="exp-return-phone" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $exceptionalReturnAddress['phone'] ?? '' ); ?>">
					<div class="ePhoneErrorText" style = "color : red ; font-size : 10px;display:none">Phone number is required</div>

				</div> 
				<div class = "col-sm-3">
					<label for="exp-return-alt-number" class = "label-font">Alternate Phone Number</label>
					<input type="tel" pattern="\d*" id="exp-return-alt-phone" name="exp-return-alt-phone" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $exceptionalReturnAddress['alternate_phone'] ?? '' ); ?>">
				</div> 
				<div class = "col-sm-2"></div> 
			</div>
			<div class = "row mt-4">
				<div class = "col-sm-5">
					<label for="exp-return-line-1" class = "label-font">Address Line 1 <span class = "required-text">*</span></label>
					<input type="text" required="true" id="exp-return-line-1" name="exp-return-line-1" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $exceptionalReturnAddress['address_line_1'] ?? '' ); ?>">
					<div class="eAddressErrorText" style = "color : red ; font-size : 10px;display:none">Address is required</div>
				</div>
				<div class = "col-sm-5"> 
					<label for="exp-return-line-2" class = "label-font">Address Line 2</label>
					<input type="text" id="exp-return-line-2" name="exp-return-line-2" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $exceptionalReturnAddress['address_line_2'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-2"> </div>
			</div>
			<div class  ="row mt-4">
				<div class = "col-sm-2">
					<label for="exp-return-city" class = "label-font">City</label>
					<input type="text" id="exp-return-city" name="exp-return-city" style = "border-radius:0px"class="form-control  " value="<?php echo esc_attr( $exceptionalReturnAddress['city'] ?? '' ); ?>">

				</div>
				<div class = "col-sm-3">
					<label for="exp-return-state" class = "label-font">State</label>
					<input type="text" id="exp-return-state" name="exp-return-state" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $exceptionalReturnAddress['state'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-2">
					<label for="exp-return-country" class = "label-font">Country <span class = "required-text">*</span></label>
					 <input type="text" required="true" id="exp-return-country" name="exp-return-country" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $exceptionalReturnAddress['country'] ?? '' ); ?>">
					 <div class="eCountryErrorText" style = "color : red ; font-size : 10px;display:none">Country is required</div>
					</div>
				<div class = "col-sm-3">
					<label for="exp-return-pincode" class = "label-font">Pincode</label>
					<input type="text" id="exp-return-pincode" name="exp-return-pincode" style = "border-radius:0px" class="form-control  " value="<?php echo esc_attr( $exceptionalReturnAddress['pincode'] ?? '' ); ?>">
				</div>
				<div class = "col-sm-2"></div>
			</div>
		</div>
	</div>
	<div class="form-group" style = "padding : 15px;margin-left : 3px">
		<div class=" container-fluid">
			<div class  ="row">
				<div class = " form-check col-sm-3">
				<input class="form-check-input"  style = "border-radius : 0px" type="checkbox" name="useForwardCheck" value="true" id="useForwardCheck">
				<label class="form-check-label" for="useForwardCheck" style = "margin-left:10px">Use Forward Address for Reverse</label>

				</div>
				<div class = "col-sm-9"></div>
			</div>
		</div>
	</div>
	<div style = "padding:15px" >
		<div class = "container-fluid" style = "margin-left:0px">
			<div class = "row">
				<div class = "col-sm-11" id = "setup-form" >
					<button type="submit" name="Submit" id = "setupSubmitButton">Submit Details</button>
				</div>
			</div>
		</div>
</form>


 
	<?php
} elseif ( array_key_exists( 'error', $response ) ) {
	?>
		<div class="alert alert-danger" role="alert"><?php echo esc_html( shipsyParseResponseError( $response['error'] ) ); ?></div>
	<?php
}
?>
