<div class="content ">
  <!-- START CONTAINER FLUID -->
  <div class="container-fluid container-fixed-lg">
    <div class="row">
      <div class="col-md-12">
    
        <div class="panel">
          <ul class="nav nav-tabs nav-tabs-linetriangle" role="tablist" data-init-reponsive-tabs="collapse">
            <li class="active"><a href="#basic-info" data-toggle="tab" role="tab" aria-expanded="true">Basic Info</a>
            </li>
            <li class=""><a href="#business-info" data-toggle="tab" role="tab" aria-expanded="false">Business Info</a>
            </li>
            <li class=""><a href="#bank-info" data-toggle="tab" role="tab" aria-expanded="false">Bank Info</a>
            </li>
			<li class=""><a href="#receipt-designer" data-toggle="tab" role="tab" aria-expanded="false">Receipt Designer</a>
            </li>
          </ul><div class="panel-group visible-xs" id="WzDfD-accordion"></div>
          <div class="tab-content">
            <div class="tab-pane active" id="basic-info">
              <div class="row column-seperation">
                <div class="col-md-12">
                  <?php $this->load->view('profile/basic_info_form');?>
                </div>
              </div>
              </div>
              <div class="tab-pane" id="business-info">
                <div class="row">
                   <div class="col-md-12">
                      <?php $this->load->view('profile/business_info_form');?>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="bank-info">
                  <div class="row">
                    <div class="col-md-12">
                      <?php $this->load->view('profile/bank_info_form');?>
                    </div>
                  </div>
                </div>
				<div class="tab-pane" id="receipt-designer">
                  <div class="row">
                    <div class="col-md-12">
                      <?php $this->load->view('profile/receipt_designer');?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END CONTAINER FLUID -->
    </div>