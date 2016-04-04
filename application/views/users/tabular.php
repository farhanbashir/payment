<section class="content-header">
    <h1>
        Users
    </h1>

</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-xs-12">
            <a href="<?php echo site_url('admin/users/addnew') ?>"><button class="btn btn-info pull-right" style="margin:10px ">Add New</button></a>
        </div>

        <div class="col-xs-12">

            <div class="box">
                <div class="box-header">
                </div><!-- /.box-header -->


                <div class="box-body table-responsive no-padding">

                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <!-- <th>Email</th> -->
                            <th>First Name</th>
                            <th>Last Name</th>
                            <!-- <th>Image</th> -->
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        $i = 0;
                        foreach ($users as $user) {
                            $i++;
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $user['username']; ?></td>
                                <!-- <td><?php echo $user['email']; ?></td> -->
                                <td><?php echo $user['first_name']; ?></td>
                                <td><?php echo $user['last_name']; ?></td>
                                <!-- <td><?php echo $user['image']; ?></td> -->
                                <td>
                                    <?php
                                    echo ($user['is_active'] == 1) ? "<span class='label label-success'>Active</span>" : "<span class='label label-danger'>Inactive</span>";
                                    ?>
                                </td>

                                <td>
                                    <a href="<?php echo base_url(); ?>index.php/admin/users/view/<?php echo $user['user_id']; ?>">View</a>
                                    &nbsp;&nbsp;&nbsp;
                                    <a href="<?php echo base_url(); ?>index.php/admin/users/edit/<?php echo $user['user_id']; ?>">Edit</a>
                                    &nbsp;&nbsp;&nbsp;
                                    <a href="<?php echo base_url(); ?>index.php/admin/users/delete/<?php echo $user['user_id']; ?>/<?php echo ($user['is_active'] == 1) ? '0' : '1'; ?>" class="status_confirm">
                                        <?php
                                        echo ($user['is_active'] == 1) ? "Deactivate" : "Activate";
                                        ?>
                                    </a>
                                    &nbsp;&nbsp;&nbsp;
                                    <a class="delete_anything" href="<?php echo site_url('admin/users/confirm_delete/' . $user['user_id'] ); ?>">Delete</a>
                                </td> 
                            </tr>
                            <?php
                        }
                        ?>

                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12"><div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                <?php echo $links; ?>
            </div></div>
    </div>
</section>
