<?php
$first_name = '';
$last_name = '';
$email = '';
$security_question_id = '';
$security_answer = '';

$userId = getLoggedInUserId();


if(!empty($basicInfoData))
{
	$first_name = $basicInfoData['first_name'];
	$last_name = $basicInfoData['last_name'];
	$email = $basicInfoData['email'];
	$security_question_id = $basicInfoData['security_question'];
	$security_answer = $basicInfoData['security_answer'];
}
?>
<div class="panel-body">
	<h2>Personal Information</h2>
	<div class="col-md-6" style="padding-left: 0px;">
		<?php
		if($this->session->flashdata('successMsgBasicInfo')!='')
		{   
			echo getHTMLForSuccessMessage($this->session->flashdata('successMsgBasicInfo'));
		}
		
		if($this->session->flashdata('errMsgBasicInfo')!='')
		{
			echo getHTMLForErrorMessage($this->session->flashdata('errMsgBasicInfo'));
		}
		?>
		<form role="form" method ="post"action="">
			<div class="form-group">
				<label>First Name</label>
				<input name="first_name" type="text" class="form-control" value="<?php echo $first_name;?>" required="">
			</div>
			<div class="form-group">
				<label>Last Name</label>
				<input name="last_name" type="text" value="<?php echo $last_name;?>" class="form-control" required="">
			</div>
			<div class="form-group">
				<label>Email</label>
				<input name="email" value="<?php echo $email;?>" type="email" class="form-control" required="">
			</div>
			<div class="form-group">
				<label>Password</label>
				<input name="password" type="password" class="form-control" >
			</div>
			<div class="form-group">
				<label>Confirm Password</label>
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
				
               <a onclick="return confirm('Are you sure, you want to delete your account? It can not be reverted. So, please make sure before proceed','<?php echo site_url('auth/deactive_user/'.$userId);?>')" href="<?php echo site_url('auth/deactive_user/'.$userId);?>">
                    Deactivate Now
                </a>
			</p>
			<br /><br />
			<button name="btn-basic-info" value="submit" type="submit" class="btn btn-primary btn-cons">Save</button>
		</form>
	</div>
</div>
<?php
$this->session->set_flashdata('successMsgBasicInfo','');
$this->session->set_flashdata('errMsgBasicInfo','');
?>