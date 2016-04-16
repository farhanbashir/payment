<?php
$first_name = $basic_info[0]['first_name'];
$last_name = $basic_info[0]['last_name'];
$email = $basic_info[0]['email'];
$security_question_id = $basic_info[0]['security_question_id'];
$security_answer = $basic_info[0]['security_answer'];
?>
<div class="panel-body">
	<h2>Personal Information</h2>
	<div class="col-md-6" style="padding-left: 0px;">
		<?php if($this->session->flashdata('ErrorMessageTab1')!='')
	    {?>   
	        <div class="alert alert-danger">
	          <strong>Alert!</strong>&nbsp;&nbsp;<?php echo $this->session->flashdata('ErrorMessageTab1');?>
	        </div>
	        <?php 
	    }?>
	    <?php if($this->session->flashdata('MessageTab1')!='')
	    {?>   
	        <div class="alert alert-success">
	          <strong>Success!</strong>&nbsp;&nbsp;<?php echo $this->session->flashdata('MessageTab1');?>
	        </div>
	        <?php 
	    }?>
		<form role="form" method ="post"action="<?php echo $basic_info_form_url;?>">
			<div class="form-group">
				<label>First Name</label>
				<span class="help">e.g. "Carlos"</span>
				<input name="first_name" type="text" class="form-control" value="<?php echo $first_name;?>" required="">
			</div>
			<div class="form-group">
				<label>Last Name</label>
				<span class="help">e.g. "Brathwaite"</span>
				<input name="last_name" type="text" value="<?php echo $last_name;?>" class="form-control" required="">
			</div>
			<div class="form-group">
				<label>Email</label>
				<span class="help">e.g. "abcd@hotmail.com"</span>
				<input name="email" value="<?php echo $email;?>" type="email" class="form-control" required="">
			</div>
			<div class="form-group">
				<label>Password</label>
				<span class="help">Note. "Make sure your Password is strong"</span>
				<input name="password" type="password" class="form-control" >
			</div>
			<div class="form-group">
				<label>Confirm Password</label>
				<span class="help">Note. "Make sure your Password is strong"</span>
				<input name="confirm_password" type="password" class="form-control" >
			</div>

			<div class="form-group">
				<label>Security Question</label>
				<select name="security_question" class="full-width select2-offscreen" data-init-plugin="select2" tabindex="-1" title="">
					<?php
					foreach ($security_questions as $row) 
					{	if($security_question_id == $row['question_id'])
						{?>	
							<option selected value="<?php echo $row['question_id'];?>"><?php echo $row['question'];?></option>
							<?php
						}else
						{?>
							<option  value="<?php echo $row['question_id'];?>"><?php echo $row['question'];?></option>
							<?php
						}
					}?>
				</select>
			</div>
			
			<div class="form-group">
				<label>Answer</label>
				<input name="security_answer" value= "<?php echo $security_answer;?>" type="text" class="form-control" required="">
			</div>
			
			<br /><br />
			<p>
				<strong>Deactivate Your Account</strong>		  
				<br />
				This will remove all your account information and delete your account completely<br />
				<a href="Javascript: void();">DEACTIVATE NOW</a>
			</p>
			<br /><br />
			<button type="submit" class="btn btn-primary btn-cons">Save</button>
		</form>
	</div>
</div>