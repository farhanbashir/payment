<?php
	global $_logged_in_role_id, $_logged_in_name, $_logged_in_email;
	global $_logged_in_merchant_user_id, $_logged_in_merchant_name, $_logged_in_merchant_email;
	
	$_logged_in_role_id = 0;
	$_logged_in_name = '';
	$_logged_in_email = '';
	
	$_logged_in_merchant_user_id = 0;
	
	$sess_logged_in = $this->session->userdata('logged_in');	
	if($sess_logged_in)
	{				
		$_logged_in_role_id = @$sess_logged_in['role_id'];
		
		$_logged_in_name = trim(@$sess_logged_in['first_name'].' '.@$sess_logged_in['last_name']);
		
		$_logged_in_email = trim(@$sess_logged_in['email']);
	}
	
	$sess_logged_in_merchant = $this->session->userdata('logged_in_merchant');
	if($sess_logged_in_merchant)
	{				
		$_logged_in_merchant_user_id = @$sess_logged_in_merchant['user_id'];
		
		$_logged_in_merchant_name = trim(@$sess_logged_in_merchant['first_name'].' '.@$sess_logged_in_merchant['last_name']);
		
		$_logged_in_merchant_email = trim(@$sess_logged_in_merchant['email']);
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>iCannPay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
    <link rel="apple-touch-icon" href="<?php echo pages_url('ico/60.png');?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo pages_url('ico/76.png');?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo pages_url('ico/120.png');?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo pages_url('ico/152.png');?>">
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />

    <link href="<?php echo asset_url('plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset_url('plugins/pace/pace-theme-flash.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset_url('plugins/boostrapv3/css/bootstrap.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset_url('plugins/font-awesome/css/font-awesome.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset_url('plugins/jquery-scrollbar/jquery.scrollbar.css');?>" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo asset_url('plugins/bootstrap-select2/select2.css');?>" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo asset_url('plugins/switchery/css/switchery.min.css');?>" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo asset_url('plugins/nvd3/nv.d3.min.css');?>" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo asset_url('plugins/mapplic/css/mapplic.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset_url('plugins/rickshaw/rickshaw.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset_url('plugins/datatables-responsive/css/datatables.responsive.css');?>" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" href="<?php echo asset_url('css/jquery.minicolors.css');?>">

    <link href="<?php echo asset_url('plugins/bootstrap-datepicker/css/datepicker3.css');?>" rel="stylesheet" type="text/css" media="screen">
    <link href="<?php echo asset_url('plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css');?>" rel="stylesheet" type="text/css" media="screen">
    <link href="<?php echo asset_url('plugins/jquery-metrojs/MetroJs.css');?>" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo pages_url('css/pages-icons.css');?>" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="<?php echo pages_url('css/pages.css');?>" rel="stylesheet" type="text/css" />
	<link class="main-stylesheet" href="<?php echo pages_url('css/custom.css');?>" rel="stylesheet" type="text/css" />
 

    <!--[if lte IE 9]>
    <link href="assets/plugins/codrops-dialogFx/dialog.ie.css" rel="stylesheet" type="text/css" media="screen" />
    <![endif]-->
  </head>
  <body class="fixed-header dashboard">
    <!-- BEGIN SIDEBPANEL-->
    <?php $this->load->view('sidebar'); ?>
    <!-- END SIDEBAR -->
    <!-- END SIDEBPANEL-->
    <!-- START PAGE-CONTAINER -->
    <div class="page-container ">
      <!-- START HEADER -->
      <?php $this->load->view('header'); ?>
      <!-- END HEADER -->
      <!-- START PAGE CONTENT WRAPPER -->
      <div class="page-content-wrapper ">
        <!-- START PAGE CONTENT -->
        <?php echo $content;?>
        <!-- END PAGE CONTENT -->
        <!-- START COPYRIGHT -->
        <!-- START CONTAINER FLUID -->
        <!-- START CONTAINER FLUID -->
        <?php $this->load->view('footer'); ?>
        <!-- END COPYRIGHT -->
      </div>
      <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTAINER -->
    <!--START QUICKVIEW -->
    <?php //$this->load->view('right'); ?>
    <!-- END QUICKVIEW-->
    <!-- START OVERLAY -->
    <?php //$this->load->view('search'); ?>
    <!-- END OVERLAY -->
    <!-- BEGIN VENDOR JS -->
    <script src="<?php echo asset_url('plugins/pace/pace.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery/jquery-1.11.1.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/modernizr.custom.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery-ui/jquery-ui.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/boostrapv3/js/bootstrap.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery/jquery-easy.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery-unveil/jquery.unveil.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery-bez/jquery.bez.min.js');?>"></script>
    <script src="<?php echo asset_url('plugins/jquery-ios-list/jquery.ioslist.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery-actual/jquery.actual.min.js');?>"></script>
    <script src="<?php echo asset_url('plugins/jquery-scrollbar/jquery.scrollbar.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo asset_url('plugins/bootstrap-select2/select2.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo asset_url('plugins/classie/classie.js');?>"></script>
    <script src="<?php echo asset_url('plugins/switchery/js/switchery.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/nvd3/lib/d3.v3.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/nvd3/nv.d3.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/nvd3/src/utils.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/nvd3/src/tooltip.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/nvd3/src/interactiveLayer.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/nvd3/src/models/axis.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/nvd3/src/models/line.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/nvd3/src/models/lineWithFocusChart.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/mapplic/js/hammer.js');?>"></script>
    <script src="<?php echo asset_url('plugins/mapplic/js/jquery.mousewheel.js');?>"></script>
    <script src="<?php echo asset_url('plugins/mapplic/js/mapplic.js');?>"></script>
    <!-- <script src="<?php echo asset_url('plugins/rickshaw/rickshaw.min.js');?>"></script>-->
    <script src="<?php echo asset_url('plugins/jquery-metrojs/MetroJs.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery-sparkline/jquery.sparkline.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/skycons/skycons.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>" type="text/javascript"></script>	
    <script src="<?php echo asset_url('plugins/jquery-datatable/media/js/jquery.dataTables.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery-datatable/media/js/dataTables.bootstrap.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/datatables-responsive/js/datatables.responsive.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('plugins/datatables-responsive/js/lodash.min.js');?>" type="text/javascript"></script>	
	
	
	<script src="<?php echo asset_url('plugins/bootstrap-daterangepicker/daterangepicker.js');?>" type="text/javascript"></script>
	<script src="<?php echo asset_url('plugins/bootstrap-timepicker/bootstrap-timepicker.min.js');?>" type="text/javascript"></script>
	<script src="<?php echo asset_url('plugins/jquery-autonumeric/autoNumeric.js');?>" type="text/javascript"></script>
	<script src="<?php echo asset_url('plugins/dropzone/dropzone.min.js');?>" type="text/javascript"></script>
	<script src="<?php echo asset_url('plugins/bootstrap-tag/bootstrap-tagsinput.min.js');?>" type="text/javascript"></script>
	<script src="<?php echo asset_url('plugins/summernote/js/summernote.min.js');?>" type="text/javascript"></script>
	<script src="<?php echo asset_url('plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js');?>" type="text/javascript"></script>
	<script src="<?php echo asset_url('plugins/jquery-inputmask/jquery.inputmask.min.js');?>" type="text/javascript"></script>

    <script src="<?php echo asset_url('js/jquery.minicolors.js');?>"></script>
	
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="<?php echo pages_url('js/pages.min.js');?>"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
	
	<script src="<?php echo asset_url('js/form_elements.js');?>" type="text/javascript"></script>
           
    <!--<script src="<?php echo asset_url('js/dashboard.js');?>" type="text/javascript"></script>-->
    <script src="<?php echo asset_url('js/scripts.js');?>" type="text/javascript"></script>
	
	<script type="text/javascript">
		jQuery(document).ready(function(){
			
			var table = $('#custom-datatable');

			var settings = {
				"sDom": "<'table-responsive't><'row'<p i>>",
				"destroy": true,
				"scrollCollapse": true,
				"oLanguage": {
					"sLengthMenu": "_MENU_ ",
					"sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
				},
				"iDisplayLength": 5,
				
				 "aoColumns": [
                                 { "bSortable": false, "bSearchable": false },
                                 { "sType": "html" },
                                 { "sType": "html" },
                                 { "sType": "html" },
                                 { "sType": "html", "bSortable": false, "bSearchable": false },
                                 { "sType": "html", "bSortable": false, "bSearchable": false },                                
                               ]
				
			};

			table.dataTable(settings);

			// search box for table
			$('#search-table').keyup(function() {
				table.fnFilter($(this).val());
			});			
		});
	</script>
	
    <script src="<?php echo asset_url('js/demo.js');?>" type="text/javascript"></script>
    <script src="<?php echo asset_url('js/datatables.js');?>" type="text/javascript"></script>
	
	<script type="text/javascript">
		function openPopupForOrderDetails(order_id)
		{
			jQuery.ajax({
				url: '<?php echo site_url('admin/sales/popup_order/') ?>/'+order_id,
				success: function(data)
				{
					if(data)
					{
						$("#modal-wrapper").html(data);
						$('#modal').modal('show');
					}
				}
			});
			
			return false;
		}

        function openPopupForProductDetails(productId)
        {   
            var popupUrl = '<?php echo site_url('admin/products/popup_product/') ?>/'+productId;
           
            jQuery.ajax({
                url:popupUrl,
                success: function(data)
                {
                    if(data)
                    {
                        $("#modal-wrapper").html(data);
                        $('#modal').modal('show');
                    }
                }
            });
            
            return false;
        }

        function closeModal()
        {   
            $('#modal').modal('hide');
            
            return false;
        }
	</script>


     
    <script>
    $(document).ready( function() {

        $('.demo').each( function() {
            //
            // Dear reader, it's actually very easy to initialize MiniColors. For example:
            //
            //  $(selector).minicolors();
            //
            // The way I've done it below is just for the demo, so don't get confused
            // by it. Also, data- attributes aren't supported at this time. Again,
            // they're only used for the purposes of this demo.
            //
            $(this).minicolors({
                control: $(this).attr('data-control') || 'hue',
                defaultValue: $(this).attr('data-defaultValue') || '',
                format: $(this).attr('data-format') || 'hex',
                keywords: $(this).attr('data-keywords') || '',
                inline: $(this).attr('data-inline') === 'true',
                letterCase: $(this).attr('data-letterCase') || 'lowercase',
                opacity: $(this).attr('data-opacity'),
                position: $(this).attr('data-position') || 'bottom left',
                swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
                change: function(hex, opacity) {
                    var log;
                    try {
                        log = hex ? hex : 'transparent';
                        if( opacity ) log += ', ' + opacity;
                        console.log(log);
                    } catch(e) {}
                },
                theme: 'default'
            });
        });
    });
    </script>
	
	
    <!-- END PAGE LEVEL JS -->
  </body>
</html>