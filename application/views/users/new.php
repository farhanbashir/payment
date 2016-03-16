<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-xs-12">
            <p class="lead">Add New User</p>

            <div class="col-xs-12">
                <div class="col-xs-6">
                    <div class="table-responsive">

                        <div class="box box-primary">

                            <!-- form start -->
                            <form name="user" id="user" onsubmit="return check_fields();" action="<?php echo base_url(); ?>index.php/admin/users/submit" method="POST"  enctype="multipart/form-data">
                                <input name="is_submit" id="is_submit" value="1" type="hidden" />
                                <div class="box-body">



                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" id="username" class="form-control" name="username" placeholder="Enter ..." value="">
                                    </div>

                                    <div class="form-group">
                                        <label>Password</label>
                                        <input id="password" type="password" class="form-control" name="password" placeholder="Enter ..." value="">
                                    </div>

                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input id="confirm_password" type="password" class="form-control" name="confirm_password" placeholder="Enter ..." value="">
                                    </div>

                                    <div class="form-group">
                                        <label for="course_date">First Name</label>
                                        <input id="first_name" class="form-control" name="first_name" placeholder="Enter ..." value="">
                                    </div>

                                    <div class="form-group">
                                        <label for="course_date">Last Name</label>
                                        <input id="last_name" class="form-control" name="last_name" placeholder="Enter ..." value="">
                                    </div>

                                    <div class="form-group">
                                        <label for="course_date">Image</label><br>
                                        <input type="file" class="form-control" name="file" >
                                    </div>

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
    if($('#password').val() == '')
    {
        count++;
        $('#password').parent().addClass('has-error');
    }
    else
    {
        $('#password').parent().removeClass('has-error');   
    }
    if($('#confirm_password').val() == '')
    {
        count++;
        $('#confirm_password').parent().addClass('has-error');
    }
    else
    {
        $('#confirm_password').parent().removeClass('has-error');   
    }
    if(($('#confirm_password').val() !== '' && $('#password').val() !== ''))
    {
        if($('#confirm_password').val() !== $('#password').val())
        {
            count++;
            $('#password').parent().addClass('has-error');
            $('#confirm_password').parent().addClass('has-error');
        }
        else
        {
            $('#password').parent().removeClass('has-error');   
            $('#confirm_password').parent().removeClass('has-error');   
        }    
        
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