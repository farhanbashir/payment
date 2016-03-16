<section class="content-header">
    <h1>
        Stores
    </h1>

</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-xs-12">
            <a href="<?php echo site_url('admin/stores/addnew') ?>"><button class="btn btn-info pull-right" style="margin:10px ">Add New</button></a>
        </div>

        <div class="col-xs-12">

            <div class="box">
                <div class="box-header">
                </div><!-- /.box-header -->


                <div class="box-body table-responsive no-padding">

                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Store Name</th>
                            <th>Store Url</th>
                            <th>Comsumer Key</th>
                            <th>Secret Key</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        $i = 0;
                        foreach ($stores as $store) {
                            $i++;
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $store['full_name']; ?></td>
                                <td><?php echo $store['store_name']; ?></td>
                                <td><?php echo $store['store_url']; ?></td>
                                <td><?php echo $store['consumer_key']; ?></td>
                                <td><?php echo $store['consumer_secret']; ?></td>
                                <td>
                                    <?php
                                    echo ($store['is_active'] == 1) ? "<span class='label label-success'>Active</span>" : "<span class='label label-danger'>Inactive</span>";
                                    ?>
                                </td>
                                <td><?php echo $store['last_updated']; ?></td>
                                <td>
                                    <a href="<?php echo base_url(); ?>index.php/admin/stores/view/<?php echo $store['store_id']; ?>">View</a>
                                    &nbsp;&nbsp;&nbsp;
                                    <a href="<?php echo base_url(); ?>index.php/admin/stores/edit/<?php echo $store['store_id']; ?>">Edit</a>
                                    &nbsp;&nbsp;&nbsp;
                                    <a href="<?php echo base_url(); ?>index.php/admin/stores/delete/<?php echo $store['store_id']; ?>/<?php echo ($store['is_active'] == 1) ? '0' : '1'; ?>" class="status_confirm">
                                        <?php
                                        echo ($store['is_active'] == 1) ? "Deactivate" : "Activate";
                                        ?>
                                    </a>
                                    &nbsp;&nbsp;&nbsp;
                                    <a class="delete_anything" href="<?php echo site_url('admin/stores/confirm_delete/' . $store['store_id'] ); ?>">Delete</a>
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
</section><!-- /.content