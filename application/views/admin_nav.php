    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">
		  <img src="<?php echo base_url(); ?>assets/img/logo.png" height="21" width="142">
			</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
			<li <?php echo $current_page[0]; ?>><?php echo anchor('/admin/index/', 'Order List'); ?></li>
			<li <?php echo $current_page[1]; ?>><?php echo anchor('/admin/user_list/', 'Staff &amp; Client Management'); ?></li>
			<li <?php echo $current_page[2]; ?>><?php echo anchor('/admin/app_setting/', 'App Setting'); ?></li>
			<li <?php echo $current_page[3]; ?>><?php echo anchor('/admin/archive/', 'Archive'); ?></li>
			<li <?php echo $current_page[4]; ?>><?php echo anchor('/admin/manage_template_order/', 'Manage Template'); ?></li>			
			<li><?php echo anchor('/auth/logout/', 'Logout'); ?></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
