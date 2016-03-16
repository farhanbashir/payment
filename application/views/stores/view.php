<!-- Main content -->
<section class="content">
    <div class="row  col-xs-12">
        <div class="col-xs-6">

            <p class="lead col-xs-6">Store # <?php echo $store['store_id']; ?></p>


            <a href="<?php echo site_url('admin/stores/delete/' . $store['store_id'] . '/' . (($store['is_active'] == 1) ? '0' : '1') . '/view'); ?>"><button class="btn <?php echo ($store['is_active'] == 1) ? "btn-danger" : "btn-primary"; ?> pull-right status_confirm" style="margin:10px "><?php echo ($store['is_active'] == 1) ? "Deactivate" : "Activate"; ?></button></a>

            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>User:</th>
                            <td><?php echo $store['full_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Store Name:</th>
                            <td><?php echo $store['store_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Store Url:</th>
                            <td><?php echo $store['store_url']; ?></td>
                        </tr>
                        <tr>
                            <th>Comsumer Key:</th>
                            <td><?php echo $store['consumer_key']; ?></td>
                        </tr>
                        <tr>
                            <th>Secret Key:</th>
                            <td><?php echo $store['consumer_secret']; ?></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <?php
                                echo ($store['is_active'] == 1) ? "<span class='label label-success'>Active</span>" : "<span class='label label-danger'>Inactive</span>";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Image:</th>
                            <td>
                            <?php 
                                if(isset($store["store_image_url"]) && $store["store_image_url"] !== "")
                                {
                                    echo "<img width='100' height='100' src='".$store['store_image_url']."' />";
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
