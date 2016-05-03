<?php global $_logged_in_role_id, $_logged_in_merchant_user_id; ?>
<nav class="page-sidebar" data-pages="sidebar">
      <!-- BEGIN SIDEBAR MENU TOP TRAY CONTENT-->
      <div class="sidebar-overlay-slide from-top" id="appMenu">
        <div class="row">
          <div class="col-xs-6 no-padding">
            <a href="#" class="p-l-40"><img src="<?php echo asset_url('img/demo/social_app.svg');?>" alt="socail">
            </a>
          </div>
          <div class="col-xs-6 no-padding">
            <a href="#" class="p-l-10"><img src="<?php echo asset_url('img/demo/email_app.svg');?>" alt="socail">
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-6 m-t-20 no-padding">
            <a href="#" class="p-l-40"><img src="<?php echo asset_url('img/demo/calendar_app.svg');?>" alt="socail">
            </a>
          </div>
          <div class="col-xs-6 m-t-20 no-padding">
            <a href="#" class="p-l-10"><img src="<?php echo asset_url('img/demo/add_more.svg');?>" alt="socail">
            </a>
          </div>
        </div>
      </div>
      <!-- END SIDEBAR MENU TOP TRAY CONTENT-->
      <!-- BEGIN SIDEBAR MENU HEADER-->
      <div class="sidebar-header">
        <img src="<?php echo asset_url('img/logo_white.png');?>" alt="logo" class="brand" data-src="<?php echo asset_url('img/logo_white.png');?>" data-src-retina="<?php echo asset_url('img/logo_white_2x.png');?>" height="22">
        <!-- <div class="sidebar-header-controls">
          <button type="button" class="btn btn-xs sidebar-slide-toggle btn-link m-l-20" data-pages-toggle="#appMenu"><i class="fa fa-angle-down fs-16"></i>
          </button>
          <button type="button" class="btn btn-link visible-lg-inline" data-toggle-pin="sidebar"><i class="fa fs-12"></i>
          </button>
        </div> -->
      </div>
      <!-- END SIDEBAR MENU HEADER-->
      <!-- START SIDEBAR MENU -->
      <div class="sidebar-menu">
        <!-- BEGIN SIDEBAR MENU ITEMS-->
		
        <ul class="menu-items">
		
			<?php
				if($_logged_in_role_id == CONST_ROLE_ID_SUPER_ADMIN)
				{
					?>
						<li class="m-t-30 ">
							<a href="<?php echo site_url('admin/users/index') ?>">
							  <span class="title">Merchants</span>
							</a>
							<span class="<?php echo ($this->uri->segment(2) == 'users')&&($this->uri->segment(3) == 'index') ? 'bg-success' : '';?> icon-thumbnail"><i class="fa fa-user"></i></span>
							<a href="<?php echo site_url('admin/users/bankstatus') ?>">
							  <span class="title">Bank Status</span>
							</a>
							<span class="<?php echo ($this->uri->segment(2) == 'users')&&($this->uri->segment(3) == 'bankstatus') ? 'bg-success' : '';?> icon-thumbnail"><i class="fa fa-bookmark"></i></span>
						</li>

					<?php
				}
			?>
		  
		  <?php
			 if( ($_logged_in_role_id == CONST_ROLE_ID_BUSINESS_ADMIN) || $_logged_in_merchant_user_id)
			 {
				?>
					<li class="m-t-30 ">
						<a href="<?php echo site_url('admin/dashboard') ?>" class="detailed">
						  <span class="title">Dashboard</span>
						</a>
						<span class="<?php echo ($this->uri->segment(2) == 'dashboard') ? 'bg-success' : '';?> icon-thumbnail"><i class="fa fa-dashboard"></i></span>
					  </li>					  
					  
					  <li class="">
						<a href="<?php echo site_url('admin/categories');?>">
						  <span class="title">Categories</span>
						</a>
						<span class="<?php echo ($this->uri->segment(2) == 'categories')||($this->uri->segment(3) == 'add_category') ? 'bg-success' : '';?> icon-thumbnail">
						  <i class="fa fa-tags"></i>
						</span>
					  </li>
					 
					  <li class="">
						<a href="<?php echo site_url('admin/products/index');?>">
						  <span class="title">Products</span>
						</a>
						<span class="<?php echo ($this->uri->segment(2) == 'products')&&(($this->uri->segment(3) == 'index')||$this->uri->segment(3) == 'add_product')? 'bg-success' : '';?> icon-thumbnail">
						  <i class="fa fa-th"></i>
						</span>
					  </li>
					  
					  <li class="">
						<a href="<?php echo site_url('admin/sales/transactions') ?>">
						  <span class="title">Transactions</span>
						</a>
						<span class="<?php echo ($this->uri->segment(3) == 'transactions') ? 'bg-success' : '';?> icon-thumbnail">
						  <i class="fa fa-dollar"></i>
						</span>
					  </li>
					  
					  <li class="">
						<a href="<?php echo site_url('admin/reports');?>">
						  <span class="title">Reports</span>
						</a>
						<span class="<?php echo ($this->uri->segment(2) == 'reports') ? 'bg-success' : '';?> icon-thumbnail">
						  <i class="fa fa-list"></i>
						</span>
					  </li>
					  
					  <li class="">
						<a href="<?php echo site_url('admin/settings') ?>">
						  <span class="title">Settings</span>
						</a>
						<span class="<?php echo ($this->uri->segment(2) == 'settings') ? 'bg-success' : '';?> icon-thumbnail"><i class="fa fa-edit"></i></span>
					  </li>
				<?php
			 }
		  ?>
		  
		  <li class="">
            <a href="<?php echo site_url('auth/logout') ?>">
              <span class="title">Logout</span>
            </a>
            <span class="icon-thumbnail"><i class="fa pg-power"></i></span>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
      <!-- END SIDEBAR MENU -->
    </nav>