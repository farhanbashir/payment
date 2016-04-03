<!--Content Header (Page header) -->
<section class="content-header">
    <h1>
        Dashboard
        <small>Control panel</small>
    </h1>
    
</section>

<!-- Main content -->
<section class="content">

    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>
                        <?php echo $total_users;?>
                    </h3>
                    <p>
                        Total Users
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>
                        <?php echo $total_stores;?>
                    </h3>
                    <p>
                        Total Stores
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
        </div><!-- ./col -->
    </div><!-- /.row -->

    <div class="row">
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Latest Users</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <table class="table table-condensed">
                                        <tbody><tr>
                                            <th style="width: 10px">#</th>
                                            <th>Email</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                        </tr>
                                        <?php
                                        $i=0;
                                        foreach($latest_five_users as $user)
                                        {
                                            $i++;
                                        ?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $user['username'];?></td>
                                            <td><?php echo $user['first_name'];?></td>
                                            <td><?php echo $user['last_name'];?></td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody></table>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->


                        </div><!-- /.col -->

                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Latest Stores</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <table class="table table-condensed">
                                        <tbody><tr>
                                            <th style="width: 10px">#</th>
                                            <th>User</th>
                                            <th>Store Url</th>
                                        </tr>
                                        <?php
                                        $i=0;
                                        foreach($latest_five_stores as $store)
                                        {
                                            $i++;
                                        ?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $store['full_name'];?></td>
                                            <td><?php echo $store['store_url'];?></td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody></table>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->


                        </div><!-- /.col -->
                        
                    </div>

    <!-- Main row -->


</section>
<!-- /.content