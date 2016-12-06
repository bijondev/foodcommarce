<?php
//add_action( 'admin_menu', 'my_admin_menu' );

function my_admin_menu() {
	add_menu_page( 'Food Commerce', 'Food Commerce', 'manage_options', 'fc-order-dashboad', 'bms_fc_dashboard', 'dashicons-tickets', 6  );
	//add_submenu_page( 'fc-order-dashboad', 'setting', 'Setting', 'manage_options', 'fc-order-page', 'bms_fc_setting', 5 ); 
}

function bms_fc_dashboard(){
	?>
	<div class="wrap">
		<h2>Food Commerce</h2>
	</div>
	<?php
}
function bms_fc_setting(){
	?>
	<div class="wrap">
		<h2>Restaurant Setting</h2>
	</div>
	<?php
}
?>