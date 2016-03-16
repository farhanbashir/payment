<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-xs-12">
            <p class="lead">Change Password</p>
            <?php
            if($error !== "")
            {
            ?>
            <div class="callout callout-<?php echo $result;?>">
                <?php echo $error;?>
            </div>
            <?php
            }
            ?>
            <div class="col-xs-12">
                <div class="col-xs-6">
                    <div class="table-responsive">

                        <div class="box box-primary">

                            <!-- form start -->
                            <form name="user" id="user" onsubmit="return check_fields();" action="<?php echo base_url(); ?>index.php/admin/dashboard/change_password_submit" method="POST"  enctype="multipart/form-data">
                                <input name="is_submit" id="is_submit" value="1" type="hidden" />
                                <div class="box-body">



                                    <div class="form-group">
                                        <label>Old Password</label>
                                        <input type="password" id="old_password" class="form-control" name="old_password" placeholder="Enter ..." value="">
                                    </div>

                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input id="password" type="password" class="form-control" name="password" placeholder="Enter ..." value="">
                                    </div>

                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input id="confirm_password" type="password" class="form-control" name="confirm_password" placeholder="Enter ..." value="">
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

    if($('#old_password').val() == '')
    {
        count++;
        $('#old_password').parent().addClass('has-error');
    }
    else
    {
        $('#old_password').parent().removeClass('has-error');   
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
    
    
    if(count == 0)
        return true;
    else
        return false;
}
</script>