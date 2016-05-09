<div class="header ">
        <!-- START MOBILE CONTROLS -->
        <div class="container-fluid relative">
          <!-- LEFT SIDE -->
          <div class="pull-left full-height visible-sm visible-xs">
            <!-- START ACTION BAR -->
            <div class="header-inner">
              <a href="#" class="btn-link toggle-sidebar visible-sm-inline-block visible-xs-inline-block padding-5" data-toggle="sidebar">
                <span class="icon-set menu-hambuger"></span>
              </a>
            </div>
            <!-- END ACTION BAR -->
          </div>
          <div class="pull-center hidden-md hidden-lg">
            <div class="header-inner">
              <div class="brand inline">
                <a href="<?php echo site_url('admin/dashboard') ?>"><img src="<?php echo asset_url('img/logo.png');?>" alt="logo" data-src="<?php echo asset_url('img/logo.png');?>" data-src-retina="<?php echo asset_url('img/logo_2x.png');?>" height="22" /></a>
              </div>
            </div>
          </div>
          <!-- RIGHT SIDE -->
        </div>
        <!-- END MOBILE CONTROLS -->
        <div class=" pull-left sm-table hidden-xs hidden-sm">
          <div class="header-inner">
            <div class="brand inline">
              <img src="<?php echo asset_url('img/logo.png');?>" alt="logo" data-src="<?php echo asset_url('img/logo.png');?>" data-src-retina="<?php echo asset_url('img/logo_2x.png');?>" height="22" />
            </div>
            <!-- START NOTIFICATION LIST -->
            <?php //$this->load->view('notifications'); ?>
            <!-- END NOTIFICATIONS LIST -->
            <!-- <a href="#" class="search-link" data-toggle="search"><i class="pg-search"></i>Type anywhere to <span class="bold">search</span></a> --> </div>
        </div>
        <div class=" pull-right">
          <!-- <div class="header-inner">
            <a href="#" class="btn-link icon-set menu-hambuger-plus m-l-20 sm-no-margin hidden-sm hidden-xs" data-toggle="quickview" data-toggle-element="#quickview"></a>
          </div> -->
        </div>
		
		<?php 
			global $_logged_in_role_id, $_logged_in_name, $_logged_in_email;
			global $_logged_in_merchant_user_id, $_logged_in_merchant_name, $_logged_in_merchant_email;
		?>
		
		<div class=" pull-right">
			<div class="visible-lg visible-md m-t-10" >
				<div class="pull-left p-r-10 p-t-10 fs-16 font-heading">
					<small>You are Logged in as <strong><?php echo $_logged_in_name; ?></strong> (<?php echo $_logged_in_email; ?>)</small>
					<?php
						if($_logged_in_merchant_user_id)
						{
							?><small> &rarr; <strong>Merchant:</strong> <?php echo $_logged_in_merchant_name; ?> (<?php echo $_logged_in_merchant_email; ?>)</small><?php
						}
					?>
					
					<span class="small">
						| <a href='<?php echo site_url('auth/logout') ?>' class="small" style="color: #3a8fc8">Logout</a>
					</span>
				</div>
			</div>
		</div>
		
        <div class=" pull-right" style="display: none;">
          <!-- START User Info-->
          <div class="visible-lg visible-md m-t-10" >
            <div class="pull-left p-r-10 p-t-10 fs-16 font-heading">
              <span class="semi-bold">David</span> <span class="text-master">Nest</span>
            </div>
            <div class="dropdown pull-right">
              <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="thumbnail-wrapper d32 circular inline m-t-5">
                <img src="<?php echo asset_url('img/profiles/avatar.jpg');?>" alt="" data-src="<?php echo asset_url('img/profiles/avatar.jpg');?>" data-src-retina="<?php echo asset_url('img/profiles/avatar_small2x.jpg');?>" width="32" height="32">
            </span>
              </button>
              <ul class="dropdown-menu profile-dropdown" role="menu">
                <li><a href="<?php echo site_url('admin/users/accounts') ?>"><i class="pg-settings_small"></i> Settings</a>
                </li>
                <!-- <li><a href="#"><i class="pg-outdent"></i> Feedback</a>
                </li>
                <li><a href="#"><i class="pg-signals"></i> Help</a>
                </li> -->
                <li class="bg-master-lighter">
                  <a href="<?php echo site_url('auth/logout') ?>" class="clearfix">
                    <span class="pull-left">Logout</span>
                    <span class="pull-right"><i class="pg-power"></i></span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <!-- END User Info-->
        </div>
      </div>