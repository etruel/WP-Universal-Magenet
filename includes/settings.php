<?php
if (!class_exists('wp_universal_magenet_settings') ) {
class wp_universal_magenet_settings {
	function __construct() {
		add_action('admin_init', array(__CLASS__, 'register_settings'));
		add_action('admin_menu',  array(__CLASS__, 'options_submenu_page'));
		add_action('admin_print_scripts', array(__CLASS__, 'scripts'));
		add_action('admin_print_styles', array(__CLASS__, 'styles'));
	}
	public static function register_settings() {
		register_setting(
			'wp_universal_magenet_options',  // settings section
			'wp_universal_magenet_options_group' // setting name
		);
		$value = get_option('wp_universal_magenet_options_group', false);

		if ($value===false) {
			$values = array();
			$values['mn_user'] = '';
			
			$values = apply_filters('wp_universal_magenet_options_group_filter', $values);
			update_option('wp_universal_magenet_options_group' , $values);
		} 
		
		
	}
	public static function options_submenu_page() {
		add_submenu_page(
			'options-general.php',          // admin page slug
			  __( 'WP Magenet Settings', 'wp_universal_magenet' ), // page title
			  __( 'WP Magenet Settings', 'wp_universal_magenet' ), // menu title
			  'manage_options',               // capability required to see the page
			  'wp_universal_magenet_options',                // admin page slug, e.g. options-general.php?page=wpars_options
			  array(__CLASS__,'options_page' )           // callback function to display the options page
		);
		
	}
	
	
	
	public static function options_page() {
		
		$values = get_option('wp_universal_magenet_options_group');
		$values = apply_filters('wp_universal_magenet_options_group_filter', $values);
		

		echo '<div class="wrap">'; 

        echo '<h2>'.esc_html(get_admin_page_title()).'</h2>';       

        echo '<div id="poststuff">
				   <div id="post-body">
						<div id="post-body-content">
							<form action="options.php" method="post">';
							settings_fields('wp_universal_magenet_options');
							do_settings_sections('wp_universal_magenet_options');
									
							echo '<table class="form-table">
									  
									<tr valign="top"><th scope="row">'. __( 'MageNet Key:', 'wp_universal_magenet' ) .'</th>
										<td>
											<input type="text" name="wp_universal_magenet_options_group[mn_user]" id="wp_universal_magenet_options_group_mn_user" value="'.$values['mn_user'].'"/>
											<p class="description">'. __( 'Description field...', 'wp_universal_magenet' ) .'</p>
										</td>
									</tr>
								
								  </table>';
								  submit_button();
								if (!empty($values['mn_user'])) {
									if( !defined('_MN_USER')){
										define('_MN_USER', $values['mn_user']);
									}
									$magenet = new Magenet();
									$array_links = $magenet->getLinksArray();
									$ordered_link = orderLinksByUrl($array_links);
									foreach ($ordered_link as $url => $ls) {
										echo '<div class="uc_header">
													<div class="uc_column">'.$url.'</div>
											</div>
											<div id="vsort_'.base64_encode($url).'" class="vsort_urls"> ';
											$order_linsk = magenetGetOrderByUrl($ls, $values['order_link'][base64_encode($url)]);
											
											foreach ($order_linsk as $i => $l) {
												echo '<div id="uc_id_'.$i.'" class="sortitem '.((($i % 2) == 0) ? 'bw' :  'lightblue').'"> 
														<div class="sorthandle"> </div> 
														<div class="uc_column" id="">
															<input type="hidden" name="wp_universal_magenet_options_group[order_link]['.base64_encode($url).'][]" value="'.substr($l, 0, 30).'"/><label>'.$l.'</label>
														</div>
													</div>';
												
											}
								
											echo '</div>';
									}
									
								}
											
								  
							 echo '</form>

						</div> 

				   </div> 

				</div> 

			</div>';   

		
	}
	public static function scripts() {
		wp_enqueue_script( 'jquery-vsort', WP_UNIVERSAL_MAGENET_URL . 'assets/js/jquery.vSort.js', array( 'jquery' ), WP_UNIVERSAL_MAGENET_VERSION, true );
		wp_enqueue_script( 'wp-magenet-settings', WP_UNIVERSAL_MAGENET_URL . 'assets/js/settings.js', array( 'jquery' ), WP_UNIVERSAL_MAGENET_VERSION, true );
	}
	public static function styles() {
		wp_enqueue_style('style-select2',WP_UNIVERSAL_MAGENET_URL .'assets/css/settings.css');	
	}
	
}
}
$wp_universal_magenet_settings = new wp_universal_magenet_settings();

?>