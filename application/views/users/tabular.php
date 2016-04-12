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
            <div class="panel-title">Merchunds List
            </div>
            <div class="pull-right">
                <div class="col-xs-12">
                    <input type="text" id="search-table" class="form-control pull-right" placeholder="Search">
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <div id="tableWithSearch_wrapper" class="dataTables_wrapper form-inline no-footer">
                <div class="table-responsive">
                    <table class="table table-hover demo-table-search dataTable no-footer" id="tableWithSearch" role="grid" aria-describedby="tableWithSearch_info">
                        <thead>
                            <tr role="row">
                                <th >#</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Places: activate to sort column ascending" style="width: 221px;">Email</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Activities: activate to sort column ascending" style="width: 245px;">Fisrt Name</th>
                                <th class="sorting" tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 138px;">Last Name</th>
                                <th tabindex="0" aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 185px;">Status</th>
                                <th aria-controls="tableWithSearch" rowspan="1" colspan="1" aria-label="Last Update: activate to sort column ascending" style="width: 185px;">Actions</th>
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
                                            <a href="<?php echo base_url(); ?>index.php/admin/users/view/<?php echo $user['user_id']; ?>">View</a>
                                            &nbsp;&nbsp;&nbsp;
                                            <a href="<?php echo base_url(); ?>index.php/admin/users/edit/<?php echo $user['user_id']; ?>">Edit</a>
                                            &nbsp;&nbsp;&nbsp;
                                            <a href="<?php echo base_url(); ?>index.php/admin/users/deactive/<?php echo $user['user_id']; ?>/<?php echo ($user['status'] == 1) ? '0' : '1'; ?>" class="status_confirm">
                                                <?php
                                                echo ($user['status'] == 1) ? "Deactivate" : "Activate";
                                                ?>
                                            </a> 
                                            &nbsp;&nbsp;&nbsp;
                                            <a class="delete_anything" href="#">Delete</a>
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

