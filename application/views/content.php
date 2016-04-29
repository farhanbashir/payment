<script type="text/javascript" src="<?php echo asset_url('js/loader.js');?>"></script>
   <script type="text/javascript" src="https://www.google.com/jsapi"></script>
<div class="content ">
	<!-- START CONTAINER FLUID -->
	<div class="container-fluid container-fixed-lg">

		<!-- START PANEL -->
		<div class="panel">
			
			<div class="panel-body">              
				<div class="row">
						<?php $this->load->view('graph.php');?>
						<!-- Dashboard will show here! -->
				</div>	
				
				<!-- END ROW -->
			</div>
		</div>
	</div>
	<!-- END CONTAINER FLUID -->
</div>