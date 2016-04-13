
 
    <div class="panel-body">
      <h2>Business Information</h2>
	  <div class="col-md-6" style="padding-left: 0px;">
		<form role="form">
			<div class="form-group">
			  <label>Logo</label>
			  <input type="file" class="form-control" required="">
			  <br /><br />
			  <img src="<?php echo asset_url('img/company-logo.jpg');?>" width="150" />
			</div>
			<div class="form-group">
			  <label>Business Name</label>
			  <input type="text" class="form-control" required="">
			</div>
			<div class="form-group">
			  <label>Description</label>
			  <textarea type="text" class="form-control no-resize" rows="8"></textarea>
			</div>
			<div class="form-group">
			  <label>Email</label>
			  <span class="help">e.g. "abcd@hotmail.com"</span>
			  <input type="email" class="form-control" required="">
			</div>
			<div class="form-group">
			  <label>Phone</label>
			  <input type="text" class="form-control" required="">
			</div>
			<div class="form-group">
			  <label>Address</label>
			  <input type="text" class="form-control">
			</div>
			<div class="form-group">
			  <label>Facebook</label>
			  <input type="text" class="form-control">
			</div>
			<div class="form-group">
			  <label>Twitter</label>
			  <input type="text" class="form-control">
			</div>
			<div class="form-group">
			  <label>Website</label>
			  <input type="text" class="form-control">
			</div>
			<br /><br />
			<button type="button" class="btn btn-primary btn-cons">Save</button>
		  </form>
	  </div>
      
    </div>