<!-- <section class="content-header">
    <h1>
        Mechunds
    </h1>

</section> -->
<div class="content ">
  <!-- START CONTAINER FLUID -->
  <div class="container-fluid container-fixed-lg bg-white">

    <div class="panel panel-transparent">
        <div class="panel-heading">
            <div class="panel-title"><h1>Merchants</h1></div>
            <div class="pull-right">
                <div class="col-xs-12">
                    <input type="text" id="search-table" class="form-control pull-right" placeholder="Search">
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <div id="tableWithSearch_wrapper" class="dataTables_wrapper form-inline no-footer">
                <div class="table-responsive_UJ">
                    <table class="table table-hover demo-table-search_UJ dataTable no-footer" id="custom-datatable" role="grid" aria-describedby="tableWithSearch_info">
                        <thead>
                            <tr role="row">
                                <th width="5%">#</th>
                                <th width="15%">Email</th>
                                <th width="15%">Fisrt Name</th>
                                <th width="15%">Last Name</th>
                                <th width="10%">Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr role="row" class="odd">
                                <?php $i=0; 
                                foreach ($users as $user) 
                                { 
                                    $i=$i+1;?>
                                    
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><?php echo $i;?></p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><?php echo $user['email'];?></p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><?php echo $user['first_name'];?></p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p><?php echo $user['last_name'];?></p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>
                                            <?php
                                            echo ($user['status'] == 1) ? "<span class='label label-success'>Active</span>" : "<span class='label label-danger'>Inactive</span>";
                                            ?>
                                        </p>
                                    </td>
                                    <td class="v-align-middle semi-bold sorting_1">
                                        <p>
                                            <a href="Javascript: void();" class="btn btn-primary ">Edit</a>
											
                                            <a href="Javascript: void();" class="btn btn-danger">Delete</a>
											
											<a href="<?php echo site_url('admin/users/login_merchant/'.$user['user_id']);?>" class="btn btn-warning">Log-In as this Merchant</a>
                                        </p>
                                    </td>
                                </tr>
                                <?php
                                }?>
                            </tbody>
                        </table>
                    </div>
                    <!-- <div class="row">
                        <div>
                            <div class="dataTables_paginate paging_simple_numbers" id="tableWithSearch_paginate">
                                <ul class="">
                                    <li class="paginate_button previous disabled" id="tableWithSearch_previous">
                                        <a href="#" aria-controls="tableWithSearch" data-dt-idx="0" tabindex="0">
                                            <i class="pg-arrow_left"></i>
                                        </a>
                                    </li>
                                    <li class="paginate_button active">
                                        <a href="#" aria-controls="tableWithSearch" data-dt-idx="1" tabindex="0">1</a>
                                    </li>
                                    <li class="paginate_button ">
                                        <a href="#" aria-controls="tableWithSearch" data-dt-idx="2" tabindex="0">2</a>
                                    </li>
                                    <li class="paginate_button ">
                                        <a href="#" aria-controls="tableWithSearch" data-dt-idx="3" tabindex="0">3</a>
                                    </li>
                                    <li class="paginate_button next" id="tableWithSearch_next">
                                        <a href="#" aria-controls="tableWithSearch" data-dt-idx="4" tabindex="0"><i class="pg-arrow_right"></i></a>
                                    </li>
                                </ul>
                            </div> -->
                            <!-- <div class="dataTables_info" id="tableWithSearch_info" role="status" aria-live="polite">Showing <b>1 to 5</b> of 12 entries
                        </div> -->
                    <!-- </div>
                </div> -->
            </div>
        </div>
    </div>

</div>
<!-- END CONTAINER FLUID -->
</div>

<!-- Main content -->

