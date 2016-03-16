<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-xs-12">
            <p class="lead"><?php echo $user['username']; ?></p>

            <div class="col-xs-12">
                <div class="col-xs-6">
                    <div class="table-responsive">

                        <div class="box box-primary">

                            <!-- form start -->
                            <form name="user" id="user" onsubmit="return check_fields();" action="<?php echo base_url(); ?>index.php/admin/users/update" method="POST"  enctype="multipart/form-data">
                                <input name="is_submit" id="is_submit" value="1" type="hidden" />
                                <input name="user_id" id="uniqid" value="<?php echo $user['user_id']; ?>" type="hidden" />
                                <div class="box-body">



                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" id="username" class="form-control" name="username" placeholder="Enter ..." value="<?php echo $user['username']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="course_date">First Name</label>
                                        <input id="first_name" class="form-control" name="first_name" placeholder="Enter ..." value="<?php echo $user['first_name']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="course_date">Last Name</label>
                                        <input id="last_name" class="form-control" name="last_name" placeholder="Enter ..." value="<?php echo $user['last_name']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label><a href="javascript:void" onclick="togglePassword();">Change Password</a></label>
                                    </div>

                                    <div class="form-group" id="password_div" style="display:none;">
                                        <label>Password</label>
                                        <input id="password" type="password" class="form-control" name="password" placeholder="Enter ..." value="">
                                    </div>

                                    <div class="form-group">
                                        <label for="course_date">Current Image</label><br>
                                        <img width="200" height="200" src="<?php echo $user['image'];?>" />
                                    </div>

                                    <div class="form-group">
                                        <label for="course_date">New Image</label><br>
                                        <input type="file" class="form-control" name="file" >
                                    </div>

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary">Edit</button>
                                    </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">

            </div>
        </div>
    </div>

</section><!-- /.content -->
<script>
function togglePassword()
{
    $('#password_div').toggle();
}
function check_fields()
{
    var count = 0;

    if($('#username').val() == '')
    {
        count++;
        $('#username').parent().addClass('has-error');
    }
    else
    {
        $('#username').parent().removeClass('has-error');   
    }
    if($('#first_name').val() == '')// || !isUrl($('#store_url').val())
    {
        count++;
        $('#first_name').parent().addClass('has-error');
    }
    else
    {
        $('#first_name').parent().removeClass('has-error');   
    }
    if($('#last_name').val() == '')
    {
        count++;
        $('#last_name').parent().addClass('has-error');
    }
    else
    {
        $('#last_name').parent().removeClass('has-error');   
    }
    
    if(count == 0)
        return true;
    else
        return false;
}
</script>