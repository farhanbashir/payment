<section class="sidebar">
    <!-- Sidebar user panel -->
    <!--<div class="user-panel">
        <div class="pull-left image">
            <img src="<?php echo asset_img('avatar3.png'); ?>" class="img-circle" alt="User Image" />
        </div>
        <div class="pull-left info">
            <p>Hello, Admin</p>

            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
    </div>-->
    <!-- search form -->
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
       <!-- <li class="treeview">
            <a href="#">
                <i class="fa fa-edit"></i> <span>Menu</span>
                <i class="fa pull-right fa-angle-left"></i>
            </a>
            <ul class="treeview-menu" style="display: none;">
                <li><a href="<?php echo site_url('admin/users') ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>Users</a></li>
                <li><a href="<?php echo site_url('admin/stores') ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>Stores</a></li>
            </ul>
        </li> -->
		
		<li><a href="<?php echo site_url('admin/dashboard') ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>Dashboard</a></li>
		<li><a href="<?php echo site_url('admin/users') ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>Users</a></li>
		<li><a href="<?php echo site_url('admin/stores') ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>User's Stores</a></li>
        <li><a href="<?php echo site_url('admin/dashboard/change_password') ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>Change Password</a></li>
		<li><a href="<?php echo site_url('auth/logout') ?>" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>Sign Out</a></li>
    </ul>
</section>