<?php
// Block direct requests
if ( !defined('ABSPATH') ) {
	exit();
}

class SCI_GoogleMaps_Widget extends WP_Widget {

	function __construct() {
		$params = array(
			'name' 			=> 'Simple Google Map',
			'description' 	=> __('Displays a Google Map based on the address that you entered in the settings.', 'simple-contact-info'),
		);

		parent::__construct('SCI_GoogleMaps_Widget', '', $params);
	}

	public function form($instance) {
		extract($instance);
		$options = array('dynamic', 'static');
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e("Title", 'simple-contact-info'); ?>:</label>
			<input 
				class="wadfat"
				name="<?php echo $this->get_field_name( 'title' ); ?>" 
				id="<?php echo $this->get_field_id( 'title' ); ?>"
				value="<?php if(!empty($title)) { echo esc_attr($title); } else {echo '';} ?>"
				type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e("Type", 'simple-contact-info'); ?>:</label>
			<select name="<?php echo $this->get_field_name( 'type' ); ?>" id="<?php echo $this->get_field_id( 'type' ); ?>">
			<option value=""><?php _e("Select type"); ?></option>
				<?php foreach($options as $option) : 
					echo '<option value="' . esc_attr( $option ) . '" ' . ($option == $type ? 'selected="Selected"' : '') .  '>'.  ucfirst($option) .'</option>'; 
				endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e("Width", 'simple-contact-info'); ?>:</label>
			<input 
				class="wadfat"
				name="<?php echo $this->get_field_name( 'width' ); ?>" 
				id="<?php echo $this->get_field_id( 'width' ); ?>"
				value="<?php if(!empty($width)) {echo esc_attr($width);} else {echo '';} ?>"
				type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e("Height", 'simple-contact-info'); ?>:</label>
			<input 
				class="wadfat"
				name="<?php echo $this->get_field_name( 'height' ); ?>" 
				id="<?php echo $this->get_field_id( 'height' ); ?>"
				value="<?php if(!empty($height)) {echo esc_attr($height);} else {echo '';} ?>"
				type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'zoom' ); ?>"><?php _e("Zoom", 'simple-contact-info'); ?>:</label>
			<input 
				class="wadfat"
				name="<?php echo $this->get_field_name( 'zoom' ); ?>" 
				id="<?php echo $this->get_field_id( 'zoom' ); ?>"
				value="<?php if(!empty($zoom)) {echo esc_attr($zoom);} else {echo '';} ?>"
				type="text" />
		</p>
		<?php
	}


	public function widget($args, $instance) {
    	$country 	= get_option('qs_contact_country');
		$state 		= get_option('qs_contact_state');
		$city		= get_option('qs_contact_city');
		$street 	= get_option('qs_contact_street');
		$zip 		= get_option('qs_contact_zip');

		$address = $country.' '.$state.' '.$city.' '.$street; 

		extract($instance);

		if (empty($type)) {
			$type = 'dynamic';
		}

		if (empty($zoom)) {
			$zoom = 15;
		}

		if (empty($width)) {
			$width = 250;
		}

		if (empty($height)) {
			$height = 250;
		}

		if ($type == 'dynamic') {
			wp_enqueue_style('google-style', 'http://code.google.com/apis/maps/documentation/javascript/examples/default.css'); 

			wp_register_script( 'googleapis', 'https://maps.googleapis.com/maps/api/js?sensor=false', false, '1.0' );
			wp_enqueue_script( 'googleapis', 'https://maps.googleapis.com/maps/api/js?sensor=false', '1.0');

			wp_register_script( 'sci-googlemap', SCI_URL.'js/contact-info-googlemap.js', false, '1.0' );
			wp_enqueue_script( 'sci-googlemap', SCI_URL.'js/contact-info-googlemap.js', '1.0');
		} else {
			$address = str_replace(' ', '+', $address);
		}

		extract($args);
		?>
		<?php echo $before_widget; ?>
			<div class="sci-google-widget">
				<?php if (!empty($title)) {
					echo $before_title . $title . $after_title;
				}?>
				<?php if ($type == 'dynamic') { ?>
					<script>
						var sci_google_zoom 	= "<?php echo $zoom; ?>";
						var sci_google_address 	= "<?php echo $address; ?>";
					</script>
					<div id="sci-google-map" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;">
						<!-- map here -->
					</div>
				<?php } elseif($type == 'static') { ?>
					<img src="http://maps.googleapis.com/maps/api/staticmap?
					center=<?php echo $address; ?>&
					zoom=<?php echo $zoom; ?>&
					size=<?php echo $width; ?>x<?php echo $height; ?>&
					maptype=roadmap&
					markers=color:red%7Clabel:S%7C<?php echo $address; ?>&
					sensor=false">
				<?php } ?>
			</div>
		<?php echo $after_widget; ?>
		<?php
	}
}

// Load the widget on widgets_init
function sci_widget_googlemap_init() {
	register_widget('SCI_GoogleMaps_Widget');
}
add_action('widgets_init', 'sci_widget_googlemap_init');