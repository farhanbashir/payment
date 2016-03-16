<!-- Main content -->
<section class="content">
    <div class="row  col-xs-12">
        <div class="col-xs-6">

            <p class="lead col-xs-6"><?php echo $user['username']; ?></p>


            <a href="<?php echo site_url('admin/users/delete/' . $user['user_id'] . '/' . (($user['is_active'] == 1) ? '0' : '1') . '/view'); ?>"><button class="btn <?php echo ($user['is_active'] == 1) ? "btn-danger" : "btn-primary"; ?> pull-right status_confirm" style="margin:10px "><?php echo ($user['is_active'] == 1) ? "Deactivate" : "Activate"; ?></button></a>

            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>Username:</th>
                            <td><?php echo $user['username']; ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?php echo $user['email']; ?></td>
                        </tr>
                        <tr>
                            <th>First Name:</th>
                            <td><?php echo $user['first_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Last Name:</th>
                            <td><?php echo $user['last_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <?php
                                echo ($user['is_active'] == 1) ? "<span class='label label-success'>Active</span>" : "<span class='label label-danger'>Inactive</span>";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Image:</th>
                            <td>
                            <?php 
                                if($user["image"] !== "")
                                {
                                    echo "<img width='100' height='100' src='".$user['image']."' />";
                                }    
                            ?>
                            </td>
                        </tr>
                        
                    </tbody></table>



            </div>
        </div>
        <div class="col-md-6">
            

        </div>
    </div>
</section><!-- /.content -->
