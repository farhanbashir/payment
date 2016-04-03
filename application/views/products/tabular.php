<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!--<section class="content-header">
          <h1>
            General UI
            <small>Preview of UI elements</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">UI</a></li>
            <li class="active">General</li>
          </ol>
        </section>-->

        <!-- Main content -->
        <section class="content">
          
          <!-- END ALERTS AND CALLOUTS -->
          <!-- START CUSTOM TABS -->
          <h2 class="page-header">Products</h2>
          <div class="row">
            <div class="col-md-12">
              <!-- Custom Tabs -->
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_1" data-toggle="tab">Products</a></li>
                  <li><a href="#tab_2" data-toggle="tab">Categories</a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    <?php $this->load->view('products/products'); ?>
                  </div><!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_2">
                    <?php $this->load->view('products/categories'); ?>
                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- nav-tabs-custom -->
            </div><!-- /.col -->

            
          </div> <!-- /.row -->
          <!-- END CUSTOM TABS -->
         


      

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->