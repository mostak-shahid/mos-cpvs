<?php
function mos_cpvs_settings_init() {
	register_setting( 'mos_cpvs', 'mos_cpvs_options' );
	add_settings_section('mos_cpvs_section_top_nav', '', 'mos_cpvs_section_top_nav_cb', 'mos_cpvs');
	add_settings_section('mos_cpvs_section_dash_start', '', 'mos_cpvs_section_dash_start_cb', 'mos_cpvs');
	add_settings_section('mos_cpvs_section_dash_end', '', 'mos_cpvs_section_end_cb', 'mos_cpvs');
	
	add_settings_section('mos_cpvs_section_scripts_start', '', 'mos_cpvs_section_scripts_start_cb', 'mos_cpvs');
	//add_settings_field( 'field_jquery', __( 'JQuery', 'mos_cpvs' ), 'mos_cpvs_field_jquery_cb', 'mos_cpvs', 'mos_cpvs_section_scripts_start', [ 'label_for' => 'jquery', 'class' => 'mos_cpvs_row', 'mos_cpvs_custom_data' => 'custom', ] );
	//add_settings_field( 'field_bootstrap', __( 'Bootstrap', 'mos_cpvs' ), 'mos_cpvs_field_bootstrap_cb', 'mos_cpvs', 'mos_cpvs_section_scripts_start', [ 'label_for' => 'bootstrap', 'class' => 'mos_cpvs_row', 'mos_cpvs_custom_data' => 'custom', ] );
	add_settings_section('mos_cpvs_section_scripts_end', '', 'mos_cpvs_section_end_cb', 'mos_cpvs');

}
add_action( 'admin_init', 'mos_cpvs_settings_init' );

function get_mos_cpvs_active_tab () {
	$output = array(
		'option_prefix' => admin_url() . "/options-general.php?page=mos_cpvs_settings&tab=",
		//'option_prefix' => "?post_type=p_file&page=mos_cpvs_settings&tab=",
	);
	if (isset($_GET['tab'])) $active_tab = $_GET['tab'];
	elseif (isset($_COOKIE['plugin_active_tab'])) $active_tab = $_COOKIE['plugin_active_tab'];
	else $active_tab = 'dashboard';
	$output['active_tab'] = $active_tab;
	return $output;
}
function mos_cpvs_section_top_nav_cb( $args ) {
	$data = get_mos_cpvs_active_tab ();
	?>
    <ul class="nav nav-tabs">
        <li class="tab-nav <?php if($data['active_tab'] == 'dashboard') echo 'active';?>"><a data-id="dashboard" href="<?php echo $data['option_prefix'];?>dashboard">All at Once</a></li>
        <li class="tab-nav <?php if($data['active_tab'] == 'scripts') echo 'active';?>"><a data-id="scripts" href="<?php echo $data['option_prefix'];?>scripts">One by One</a></li>
    </ul>
	<?php
}
function mos_cpvs_section_dash_start_cb( $args ) {
	$data = get_mos_cpvs_active_tab ();
  global $mos_cpvs_options;
	?>
	<div id="mos-cpvs-dashboard" class="tab-con <?php if($data['active_tab'] == 'dashboard') echo 'active';?>">
		<?php //var_dump($mos_cpvs_options) ?>

		<?php wp_nonce_field( 'change_all_products_action', 'change_all_products_field' ); ?>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row">
						<label for="new_price">New Price</label>
					</th>
					<td>
						<select name="new_price" id="new_price">
							<option value="min">Minimum</option>
							<option value="max">Maxumum</option>
							<option selected="selected" value="default">As Defined</option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="defined_price">Defined Price</label>
					</th>
					<td>
						<input name="defined_price" type="text" id="defined_price" aria-describedby="defined_price_description" value="0" class="regular-text ltr">
						<!-- <p class="description" id="defined_price_description">This address is used for admin purposes. If you change this, we will send you an email at your new address to confirm it. <strong>The new address will not become active until confirmed.</strong></p> -->
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" id="change_all_at_once" class="button button-primary" value="Convert Products"></p>

	<?php
}
function mos_cpvs_section_scripts_start_cb( $args ) {
	$data = get_mos_cpvs_active_tab ();
	?>
	<div id="mos-cpvs-scripts" class="tab-con <?php if($data['active_tab'] == 'scripts') echo 'active';?>">
        <?php 
            global $wpdb;
            $allposts = $wpdb->get_results( "SELECT DISTINCT post_parent FROM {$wpdb->prefix}posts WHERE post_type='product_variation'");
            foreach ($allposts as $key => $value) {            
                $posts[] = $value->post_parent;            
            }
            //var_dump($allposts);
            $args = array(
                'post_type' => 'product',
                'post__in' => $posts,
                'posts_per_page' => -1,
                'post_status' => 'publish',
            );
            $output = [];
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) :
				wp_nonce_field( 'change_one_by_one_products_action', 'change_one_by_one_products_field' )?>
				<table class="form-table" role="presentation"><tbody>
				<tr>
				<th>Product Name</th>
				<th>New Price</th>
				<th>Defined Price</th>
				</tr>
				
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<tr>
					<td><?php echo get_the_title()?></td>
					<td>
					<select name="products[<?php echo get_the_ID()?>][new_price]" id="new_price_<?php echo get_the_ID()?>">
							<option value="min">Minimum</option>
							<option value="max">Maxumum</option>
							<option selected="selected" value="default">As Defined</option>
						</select>
					</td>
					<td><input name="products[<?php echo get_the_ID()?>][defined_price]" type="text" id="defined_price_<?php echo get_the_ID()?>" value="0" class="regular-text ltr"></td>
					</tr>
				<?php endwhile; ?>
				</table></tbody>
				<p class="submit"><input type="submit" id="change_one_by_one" class="button button-primary" value="Convert Products"></p>
			<?php endif;
            wp_reset_postdata();    
            //var_dump($output);
        ?>
	<?php
}

function mos_cpvs_section_end_cb( $args ) {
	$data = get_mos_cpvs_active_tab ();
	?>
	</div>
	<?php
}


function mos_cpvs_options_page() {
	//add_menu_page( 'WPOrg', 'WPOrg Options', 'manage_options', 'mos_cpvs', 'mos_cpvs_options_page_html' );
	add_submenu_page( 'options-general.php', 'Settings', 'Variable to Simple', 'manage_options', 'mos_cpvs_settings', 'mos_cpvs_admin_page' );
}
add_action( 'admin_menu', 'mos_cpvs_options_page' );

function mos_cpvs_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error( 'mos_cpvs_messages', 'mos_cpvs_message', __( 'Settings Saved', 'mos_cpvs' ), 'updated' );
	}
	settings_errors( 'mos_cpvs_messages' );
	?>
	<div class="wrap mos-cpvs-wrapper">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form method="post">
		<?php
		settings_fields( 'mos_cpvs' );
		do_settings_sections( 'mos_cpvs' );
		//submit_button( 'Save Settings' );
		?>
		</form>
	</div>
	<?php
}