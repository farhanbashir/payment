<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-xs-12">
            <p class="lead col-xs-6">Store # <?php echo $store['store_id']; ?></p>

            <div class="col-xs-12">
                <div class="col-xs-6">
                    <div class="table-responsive">

                        <div class="box box-primary">

                            <!-- form start -->
                            <form name="user" id="user" onsubmit="return check_fields();" action="<?php echo base_url(); ?>index.php/admin/stores/update" method="POST"  enctype="multipart/form-data">
                                <input name="is_submit" id="is_submit" value="1" type="hidden" />
                                <input name="store_id" id="uniqid" value="<?php echo $store['store_id']; ?>" type="hidden" />
                                <div class="box-body">

                                    <div class="form-group">
                                        <label>User</label>
                                        <select id="user_id" class="form-control valid" name="user_id" aria-required="true" aria-invalid="false">
                                        <?php
                                        foreach($users as $user)
                                        {
                                        ?>
                                        <option <?php if($user["user_id"] == $store["user_id"]){echo "selected='selected'";}?> value="<?php echo $user["user_id"];?>"><?php echo ucwords($user["first_name"]." ".$user["last_name"]);?></option>
                                        <?php    
                                        } 
                                        ?>
                                        </select>

                                    </div>

                                    <div class="form-group">
                                        <label>Store Name</label>
                                        <input id="store_name" type="text" class="form-control" name="store_name" placeholder="Enter ..." value="<?php echo $store['store_name']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Store Url</label>
                                        <input id="store_url" type="text" class="form-control" name="store_url" placeholder="Enter ..." value="<?php echo $store['store_url']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="course_date">Consumer Key</label>
                                        <input id="consumer_key" class="form-control" name="consumer_key" placeholder="Enter ..." value="<?php echo $store['consumer_key']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="course_date">Secret Key</label>
                                        <input id="consumer_secret" class="form-control" name="consumer_secret" placeholder="Enter ..." value="<?php echo $store['consumer_secret']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="course_date">Current Image</label><br>
                                        <?php 
                                            if(isset($store["store_image_url"]) && $store["store_image_url"] !== "")
                                            {
                                                echo "<img width='200' height='200' src='".$store['store_image_url']."' />";
                                            }   
                                            else
                                            {
                                                echo "No Image";
                                            } 
                                        ?>
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
function check_fields()
{
    var count = 0;

    if($('#user_id').val() == '')
    {
        count++;
        $('#user_id').parent().addClass('has-error');
    }
    else
    {
        $('#user_id').parent().removeClass('has-error');   
    }
    if($('#store_url').val() == '')// || !isUrl($('#store_url').val())
    {
        count++;
        $('#store_url').parent().addClass('has-error');
    }
    else
    {
        $('#store_url').parent().removeClass('has-error');   
    }
    if($('#store_name').val() == '')
    {
        count++;
        $('#store_name').parent().addClass('has-error');
    }
    else
    {
        $('#store_name').parent().removeClass('has-error');   
    }
    if($('#consumer_key').val() == '')
    {
        count++;
        $('#consumer_key').parent().addClass('has-error');
    }
    else
    {
        $('#consumer_key').parent().removeClass('has-error');   
    }
    if($('#consumer_secret').val() == '')
    {
        count++;
        $('#consumer_secret').parent().addClass('has-error');
    }
    else
    {
        $('#consumer_secret').parent().removeClass('has-error');   
    }
    if(count == 0)
        return true;
    else
        return false;
}
</script>