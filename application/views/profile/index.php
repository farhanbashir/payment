<div class="content ">
  <!-- START CONTAINER FLUID -->
  <div class="container-fluid container-fixed-lg">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-info visible-xs m-r-5 m-l-5" role="alert">
          <button class="close" data-dismiss="alert"></button>
          <strong>Info: </strong> On mobile the tab will be come a Accorian by using data-init-reponsive-tabs="collapse"
        </div>
        <div class="panel">
          <ul class="nav nav-tabs nav-tabs-simple hidden-xs" role="tablist" data-init-reponsive-tabs="collapse">
            <li class="active"><a href="#tab2hellowWorld" data-toggle="tab" role="tab" aria-expanded="true">Basic Info</a>
            </li>
            <li class=""><a href="#tab2FollowUs" data-toggle="tab" role="tab" aria-expanded="false">Business Info</a>
            </li>
            <li class=""><a href="#tab2Inspire" data-toggle="tab" role="tab" aria-expanded="false">Bank Info</a>
            </li>
          </ul><div class="panel-group visible-xs" id="WzDfD-accordion"></div>
          <div class="tab-content hidden-xs">
            <div class="tab-pane active" id="tab2hellowWorld">
              <div class="row column-seperation">
                <div class="col-md-12">
                  <?php $this->load->view('profile/basic_info_form');?>
                </div>
              </div>
              </div>
              <div class="tab-pane" id="tab2FollowUs">
                <div class="row">
                   <div class="col-md-12">
                      <?php $this->load->view('profile/business_info_form');?>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="tab2Inspire">
                  <div class="row">
                    <div class="col-md-12">
                      <?php $this->load->view('profile/bank_info_form');?>
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