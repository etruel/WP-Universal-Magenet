<?php
class MagenetWidget extends WP_Widget {
   
    function __construct() {
		parent::__construct(
			'MagenetWidget', 
			__('WP Magenet Widget', 'wp_universal_magenet'), 
			array(
				'description' => __( 'WP Magenet Widget lets you choose where to display links.', 'wp_universal_magenet' ),
			) 
		);
		
    }
	public static function register_widget() {
		register_widget('MagenetWidget');
	}
    /** @see WP_Widget::widget */
    function widget($args, $instance) {	
		global $magenet;
		$values = get_option('wp_universal_magenet_options_group');
		$values = apply_filters('wp_universal_magenet_options_group_filter', $values);
		if (!empty($values['mn_user'])) {
			if( !defined('_MN_USER')){
				define('_MN_USER', $values['mn_user']);
			}
		}
        extract( $args );
		
		if (!isset($magenet)) {
			$magenet = new Magenet();
		}
		
		$only_url = apply_filters('widget_magenet_only_url', $instance['only_url']);
		if (!empty($only_url)) {
			if ($only_url != $magenet->getPageUrl()) {
				return false;
			}
		}
		
        $title = apply_filters('widget_title', $instance['title']);
		$number_links = apply_filters('widget_magenet_number_links', $instance['number_links']);
		$number_links = intval($number_links); 
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                 
              <?php
				if (empty($values['order_link'][base64_encode($magenet->getPageUrl())])) {
					$values['order_link'][base64_encode($magenet->getPageUrl())] = array();
				}
				$links = $magenet->getPageLinks();
				$order_linsk = magenetGetOrderByUrl($links, $values['order_link'][base64_encode($magenet->getPageUrl())]);
				$magenet->setPageLinks($order_linsk);
				echo $magenet->getLinks($number_links);
				echo $after_widget; 
			  ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number_links'] = $new_instance['number_links'];
		$instance['only_url'] = $new_instance['only_url'];
        return $instance;
    }

    function form($instance) {
		$values = get_option('wp_universal_magenet_options_group');
		$values = apply_filters('wp_universal_magenet_options_group_filter', $values);
		$title = '';
		$number_links = 0;
		$only_url = '';
		if (!empty($instance['title'])) {
			$title = esc_attr($instance['title']);
		}
		if (!empty($instance['number_links'])) {
			$number_links = esc_attr($instance['number_links']);
		}
		if (isset($instance['only_url'])) {
			$only_url = $instance['only_url'];
		}
		
		if( !defined('_MN_USER')){
			define('_MN_USER', $values['mn_user']);
		}
		$magenet = new Magenet();
		$array_links = $magenet->getLinksArray();
		$ordered_link = orderLinksByUrl($array_links);
		$echoSelectUrls = '<select id="'.$this->get_field_id('only_url').'" name="'.$this->get_field_name('only_url').'" style="width: 100%; display: block;">';
		$echoSelectUrls .= '<option value=""'.selected($only_url, '', false).'>'.__('All urls', 'wp_universal_magenet').'</option>';
		foreach ($ordered_link as $url => $ls) {
			$echoSelectUrls .= '<option value="'.$url.'"'.selected($only_url, $url, false).'>'.$url.'</option>';
		}
		$echoSelectUrls .= '</select>';
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('number_links'); ?>"><?php _e('Number of links/ads:', 'wp_universal_magenet'); ?> <input class="widefat" id="<?php echo $this->get_field_id('number_links'); ?>" name="<?php echo $this->get_field_name('number_links'); ?>" type="number" min="0" max="99" style="width: 70px; display: block;" value="<?php echo $number_links; ?>" /></label>  <p class="description"><?php _e('Zero will shows all the links/ads in this widget.', 'wp_universal_magenet'); ?></p> </p>
			<p><label for="<?php echo $this->get_field_id('only_url'); ?>"><?php _e('Only this url:', 'wp_universal_magenet'); echo $echoSelectUrls; ?> </label>  <p class="description"><?php _e('if you select a url links/ads are displayed only in it.', 'wp_universal_magenet'); ?></p> </p>
      
	  
		<?php 
    }

}
?>