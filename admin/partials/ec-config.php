

<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="float-right">
			<button type="submit" form="form-module" data-toggle="tooltip" title="Save" class="btn btn-primary">
					<i class="fa fa-save"></i>   Save Config
				</button>
			</div>
			<h3>Configuration</h3>
		</div>
	</div>

	<div class="container-fluid">
		<div class="card text-center " style="max-width: 98rem;">
			<div class="card-header">
				<h5 class="card-title">
					<i class="fa fa-pencil"></i>Kindly fill details</h5>
			</div>
			<div class="card-body">
				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
					<input type="hidden" name="action" value="on_config_submit" />
					<div class="container" style = "margin-left : 0px">
						<div class="block" style="margin-top:30px;">
							<label for="forward-name" class = "label-font">User Name <span class = "required-text">*</span></label>
							<input type="text" required="true"id="forward-name" name="user-name"  style= "border-radius:0px" required="true">
							<div class="nameErrorText" style = "color : red ; font-size : 10px;display:none">Name is required</div>
						</div>

						<div class="block" style="margin-top:10px;">
							<label for="forward-line-1" class = "label-font">Password <span class = "required-text">*</span></label>
							<input type="password" id="forward-line-1"required="true" name="password"style= "border-radius:0px" required="true">
							<div class="addressErrorText" style = "color : red ; font-size : 10px;display:none">Password is required</div>
						</div>

						<div class="block" style="margin-top:10px;">
							<label for="forward-org-id" class = "label-font">Organisation Id <span class = "required-text">*</span></label>
							<input type="text" required="true" id="forward-org-id" name="org_id"  style= "border-radius:0px" required="true">
							<div class="orgIdErrorText" style = "color : red ; font-size : 10px;display:none">Organisation Id is required</div>
						</div>

						<hr style="margin: 3em 0 0 0"/>

						<div class="card-header">
							<h5 class="card-title">
								<i class="fa fa-pencil"></i>Settings</h5>
						</div>
						<div class="block" style="margin-top:20px; float: left">
							<label style="width: 100%">
								<input type="checkbox" name="download_label_option" value="true" id="download_label_option" checked>
									Enable download shipping label in orders.
							</label>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<style>
	label {
		display: inline-block;
		width: 150px;
		text-align: right;

	  }
</style>
