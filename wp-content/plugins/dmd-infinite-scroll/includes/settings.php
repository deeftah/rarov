<?php
class dmd_settings
{
	public static $settings = array();
	public static $plugin_names = array();
	public static $plugin_slug = array();
	public static $type = array();
	public static $default = array();
	function __construct()
	{
		add_action('admin_menu', array(__CLASS__, 'admin_menu'));
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
	}
	public static function admin_init() {
		if(! empty($_GET['page']) && in_array($_GET['page'], self::$plugin_slug))
		{
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'dmd-project-script_admin', plugins_url( 'settings_assets/dmdui.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ) );

			wp_register_style( 'dmd-project-style_admin', plugins_url( 'settings_assets/dmdui.css', __FILE__ ) );
			wp_enqueue_style( 'dmd-project-style_admin' );

			wp_register_style( 'dmd-project-fa', plugins_url( 'settings_assets/css/font-awesome.min.css', __FILE__ ) );
			wp_enqueue_style( 'dmd-project-fa' );
			if ( function_exists( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            } else {
                wp_enqueue_style( 'thickbox' );
                wp_enqueue_script( 'media-upload' );
                wp_enqueue_script( 'thickbox' );
            }
		}
	}
	public static function correct_data($plugin_name) {
		$current_settings = get_option($plugin_name);
		return $current_settings;
	}
	public static function admin_menu()
	{
		$create_menu = true;
		foreach( self::$plugin_names as $plugin_slug => $plugin_name ) {
			if( $create_menu ) {
				$menu_name = 'DMD Plugins';
				if( count(self::$plugin_names) == 1 ) {
					$menu_name = $plugin_name;
				}
				add_menu_page( $menu_name, $menu_name, 'manage_options', $plugin_slug, '', plugins_url( 'settings_assets/dmdico.png', __FILE__ ) );
				$menu_slug = $plugin_slug;
				$create_menu = false;
			}
			add_submenu_page( $menu_slug, $plugin_name, $plugin_name, 'manage_options', $plugin_slug, array( __CLASS__, 'dmd_option_form' ) );
			register_setting($plugin_slug, $plugin_slug, array(__CLASS__, 'save_settings_callback'));
		}
	}
	public static function save_settings_callback( $input ) {
		return $input;
	}
	public static function dmd_option_form()
	{
		self::echo_settings($_GET['page']);
	}
	public static function add_settings($plugin_name, $plugin_text, $settings)
	{
		self::$plugin_names[$plugin_name] = $plugin_text;
		self::$plugin_slug[] = $plugin_name;
		self::$settings[$plugin_name] = get_option($plugin_name);
		self::set_settings($plugin_name, $settings);
		self::$type[$plugin_name] = $settings;
	}
	public static function update_setting($plugin_name, $option_name, $option_value) {
		$update_setting = get_option($plugin_name);
		$update_setting[$option_name] = $option_value;
		update_option($plugin_name, $update_setting);
	}
	public static function remove_plugin_setting($plugin_name) {
		delete_option($plugin_name);
	}
	public static function set_settings($plugin_name, &$settings)
	{
		$values = array();
		foreach($settings as &$setting)
		{
			if($setting['type'] == 'general' || $setting['type'] == 'block')
			{
				self::set_settings($plugin_name, $setting['data']);
			}
			else
			{
				self::$default[$plugin_name][$setting['name']] = @ $setting['value'];
				if(isset(self::$settings[$plugin_name][$setting['name']]))
				{
					$setting['value'] = self::$settings[$plugin_name][$setting['name']];
				}
				else
				{
					self::$settings[$plugin_name][$setting['name']] = @ $setting['value'];
				}
			}
		}
	}
	public static function get_settings($plugin)
	{
		return self::$settings[$plugin];
	}
	public static function echo_settings($plugin)
	{
		
		echo '<div class="dmd_donation">Your can help to improve plugin functionality and usability with donation.
Also if you need some specific function, please ask with this function in support and if this is possible to add this, then you can donate to speed up adding this functionality.
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="4E5PGYEVW4EAS">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form></div>';
		$type = self::$type[$plugin];
		echo '<form class="dmd_setting_form" method="post" action="options.php">';
		settings_fields( $plugin );
		echo '<div class="dmd_settings">';
		echo '<h3 class="dmd-header">', self::$plugin_names[$plugin], '</h3>';
		echo '<ul class="dmd_menu">';
		$first = true;
		foreach( $type as $general ) {
			if($first) {
				echo '<li class="dmd_'.$general['name'].' dmd_current" data-block="dmd_'.$general['name'].'">'.$general['text'].'</li>';
				$first = false;
			} else {
				echo '<li class="dmd_'.$general['name'].'" data-block="dmd_'.$general['name'].'">'.$general['text'].'</li>';
			}
		}
		echo '</ul>';
		$first = true;
		echo '<div class="dmd_blocks">';
		foreach( $type as $general ) {
			if($first) {
				echo '<div class="dmd_block dmd_'.$general['name'].' dmd_current">';
				$first = false;
			} else {
				echo '<div class="dmd_block dmd_'.$general['name'].'">';
			}
			self::print_settings($general['data'], $plugin);
			echo '</div>';
		}
		?>
		</div>
		<input type="submit" class="button-primary save-button" value="<?php echo __('Save Changes', 'dmd_is'); ?>" />
		</div>
		</form>
		<script>
			var dmd_settings_form = jQuery('.dmd_setting_form');
			var dmd_settings_ajax = false;
			var dmd_save_close = false;
			jQuery('.dmd_setting_form').submit(function(event){
				event.preventDefault();
				var $dmd_form = jQuery(this);
				if( ! dmd_settings_ajax )
				{
					dmd_start_save()
					jQuery.ajax({
						type: 'POST',
						url: dmd_settings_form.attr('action'),
						data: dmd_settings_form.serialize(),
						success: function(data){
							dmd_end_save('ok');
							if( $dmd_form.is('.dmd_reload') ) {
								location.reload();
							}
						},
						error: function(event){
							dmd_end_save('error');
						}
					})
				}
			});
			function dmd_start_save()
			{
				dmd_force_end();
				dmd_settings_ajax = true;
				var html_send = '<div class="dmd_save_settings"><span class="fa fa-spin"></span></div>';
				jQuery('.dmd_setting_form').append(html_send);
			}
			function dmd_end_save(save_satus)
			{
				jQuery('.dmd_save_settings').addClass('dmd_transform');
				setTimeout(function() {
					jQuery('.dmd_save_settings span.fa').removeClass('fa-spin');
					if(save_satus == 'ok')
					{
						jQuery('.dmd_save_settings').removeClass('dmd_transform').addClass('dmd_saved');
					} 
					else
					{
						jQuery('.dmd_save_settings').removeClass('dmd_transform').addClass('dmd_saved_error');
					}						
					dmd_settings_ajax = false;
					dmd_save_close = setTimeout(function() {
						jQuery('.dmd_save_settings').addClass('dmd_hide');
						dmd_save_close = setTimeout(function() {
							jQuery('.dmd_save_settings').remove();
						}, 200);
					}, 4000);
				}, 400);

			}
			function dmd_force_end()
			{
				jQuery('.dmd_save_settings').remove();
				clearTimeout(dmd_save_close);
			}
		</script>
		<?php
	}
	public static function print_settings($settings, $plugin_name)
	{
		$previous_setting = false;
		$modify_name = '';
		if( isset($settings['duplicate']) )
		{
			$modify_name = $settings['duplicate'];
			unset($settings['duplicate']);
		}
		foreach($settings as $setting)
		{
			$setting['name'] = $setting['name'] . $modify_name;
			self::before_setting($setting);
			if($setting['type'] == 'text' || $setting['type'] == 'number')
			{
				echo '<label><strong>', (isset($setting['text']) ? $setting['text'] : ''), '</strong><input name="', $plugin_name, '[', $setting['name'], ']" type="', $setting['type'], '" class="dmdui input ', $setting['name'], '_input" ', (isset($setting['class']) ? $setting['class'] : ''), '" value="', $setting['value'], '" ', (isset($setting['additional']) ? $setting['additional'] : ''), '>', (isset($setting['text_2']) ? $setting['text_2'] : ''), '</label>';
			}
			elseif($setting['type'] == 'textarea')
			{
				echo '<p><strong>', (isset($setting['text']) ? $setting['text'] : ''), '</strong><textarea name="', $plugin_name, '[', $setting['name'], ']" class="dmdui textarea ', $setting['name'], '_input" ', (isset($setting['class']) ? $setting['class'] : ''), '" ', (isset($setting['additional']) ? $setting['additional'] : ''), '>', $setting['value'], '</textarea>', (isset($setting['text_2']) ? $setting['text_2'] : ''), '</p>';
			}
			elseif($setting['type'] == 'checkbox')
			{
				echo '<label><strong>', (isset($setting['text']) ? $setting['text'] : ''), '</strong><input name="', $plugin_name, '[', $setting['name'], ']" type="checkbox" class="dmdui ', $setting['name'], '_input" ', (isset($setting['class']) ? $setting['class'] : ''), '" value="1" ', (isset($setting['additional']) ? $setting['additional'] : ''), ($setting['value'] ? ' checked' : ''), '>', (isset($setting['text_2']) ? $setting['text_2'] : ''), '</label>';
			}
			elseif($setting['type'] == 'select')
			{
				echo '<label><strong>', (isset($setting['text']) ? $setting['text'] : ''), '</strong></label>';
				echo '<select name="', $plugin_name, '[', $setting['name'], ']" class="dmdui ', $setting['name'], '_input" ', (isset($setting['class']) ? $setting['class'] : ''), '">';
				foreach($setting['data'] as $value => $name)
				{
					echo '<option value="', $value, '"', ($value == $setting['value'] ? ' selected' : ''), '>', $name, '</option>';
				}
				echo '</select>';
				echo '<label>', (isset($setting['text_2']) ? $setting['text_2'] : ''), '</label>';
			}
			elseif($setting['type'] == 'image')
			{
				echo '<label class="dmd_input"><strong>', (isset($setting['text']) ? $setting['text'] : ''), '</strong><input style="display:none;" name="', $plugin_name, '[', $setting['name'], ']" type="hidden" class="dmdui ', $setting['name'], '_input" ', (isset($setting['class']) ? $setting['class'] : ''), '" value="', $setting['value'], '" ', (isset($setting['additional']) ? $setting['additional'] : ''), '>', (isset($setting['text_2']) ? $setting['text_2'] : ''), '</label>';
				echo '<div class="dmd_image"><img src="', $setting['value'], '" alt="', (isset($setting['text']) ? $setting['text'] : ''), '"></div>';
				echo '<input type="button" class="dmd_upload_image button" value="'.__('Upload/Select', 'DMD_projects').'"/> ';
				echo '<input type="button" class="dmd_remove_image button" value="'.__('Clear', 'DMD_projects').'"/>';
			}
			elseif($setting['type'] == 'block')
			{
				$setting['data']['duplicate'] = $modify_name;
				self::print_settings($setting['data'], $plugin_name);
			}
			elseif($setting['type'] == 'duplicate')
			{
				$settings_duplicate = array('duplicate' => $setting['name']);
				unset($setting['name'],$setting['type']);
				foreach($setting as $dup_name => $dup_value)
				{
					$previous_setting[$dup_name] = $dup_value;
				}
				$settings_duplicate[] = $previous_setting;
				self::print_settings($settings_duplicate, $plugin_name);
			}
			elseif($setting['type'] == 'style')
			{
				echo '<a class="button dmd_display_style" data-name="', $plugin_name, '_', $setting['name'], '">', (isset($setting['text']) ? $setting['text'] : 'Edit styles'), '</a>';
				echo '<a class="button dmd_reset_style" data-name="', $plugin_name, '_', $setting['name'], '">', (isset($setting['text_2']) ? $setting['text_2'] : 'Set to default'), '</a>';
				echo '<div class="dmd_styler ', $plugin_name, '_', $setting['name'], '" style="display:none;">';
				$close_count = 0;
				if(in_array('margin', $setting['data'])) {
					$close_count++;
					echo '<div class="dmd_margin_block dmd_style_box">
						<span class="dmd_margin_text dmd_style_text">Margin</span>
						
						<input name="', $plugin_name, '[', $setting['name'], '][margin-top]" value="', @ $setting['value']['margin-top'], '" type="text" class="dmd_margin_top dmd_style_hidden dmd_style_input top center">
						<span class="dmd_style_desc dmd_style_hidden top center"><span>Top</span></span>
						<input name="', $plugin_name, '[', $setting['name'], '][margin-right]" value="', @ $setting['value']['margin-right'], '" type="text" class="dmd_margin_right dmd_style_hidden dmd_style_input right middle">
						<span class="dmd_style_desc dmd_style_hidden right middle"><span>Right</span></span>
						<input name="', $plugin_name, '[', $setting['name'], '][margin-bottom]" value="', @ $setting['value']['margin-bottom'], '" type="text" class="dmd_margin_bottom dmd_style_hidden dmd_style_input bottom center">
						<span class="dmd_style_desc dmd_style_hidden bottom center"><span>Bottom</span></span>
						<input name="', $plugin_name, '[', $setting['name'], '][margin-left]" value="', @ $setting['value']['margin-left'], '" type="text" class="dmd_margin_left dmd_style_hidden dmd_style_input left middle">
						<span class="dmd_style_desc dmd_style_hidden left middle"><span>Left</span></span>';
				}
				if(in_array('border', $setting['data']) || in_array('border-radius', $setting['data']) || in_array('border-color', $setting['data'])) {
					$close_count++;
					echo '<div class="dmd_border_block dmd_style_box">
						<span class="dmd_border_text dmd_style_text">Border</span>';
					if(in_array('border', $setting['data'])){
						echo '<input name="', $plugin_name, '[', $setting['name'], '][border-top-width]" value="', @ $setting['value']['border-top-width'], '" type="text" class="dmd_border_top dmd_style_hidden dmd_style_input top center">
							<span class="dmd_style_desc dmd_style_hidden top center"><span>Top</span></span>
							<input name="', $plugin_name, '[', $setting['name'], '][border-right-width]" value="', @ $setting['value']['border-right-width'], '" type="text" class="dmd_border_right dmd_style_hidden dmd_style_input right middle">
							<span class="dmd_style_desc dmd_style_hidden right middle"><span>Right</span></span>
							<input name="', $plugin_name, '[', $setting['name'], '][border-bottom-width]" value="', @ $setting['value']['border-bottom-width'], '" type="text" class="dmd_border_bottom dmd_style_hidden dmd_style_input bottom center">
							<span class="dmd_style_desc dmd_style_hidden bottom center"><span>Bottom</span></span>
							<input name="', $plugin_name, '[', $setting['name'], '][border-left-width]" value="', @ $setting['value']['border-left-width'], '" type="text" class="dmd_border_left dmd_style_hidden dmd_style_input left middle">
							<span class="dmd_style_desc dmd_style_hidden left middle"><span>Left</span></span>';
					}
					if(in_array('border-color', $setting['data'])){
						echo '<input name="', $plugin_name, '[', $setting['name'], '][border-color]" value="', @ $setting['value']['border-color'], '" type="text" class="dmd_color_picker dmd_border_color dmd_style_hidden dmd_style_input top right_25">
							<span class="dmd_style_desc dmd_style_hidden top right_25"><span>Color</span></span>';
					}
					if(in_array('border-radius', $setting['data'])){
						echo '<input name="', $plugin_name, '[', $setting['name'], '][border-top-left-radius]" value="', @ $setting['value']['border-top-left-radius'], '" type="text" class="dmd_border_radius_top_left dmd_style_hidden dmd_style_input top left">
							<span class="dmd_style_desc dmd_style_hidden top left"><span>Top-left corner radius</span></span>
							<input name="', $plugin_name, '[', $setting['name'], '][border-top-right-radius]" value="', @ $setting['value']['border-top-right-radius'], '" type="text" class="dmd_border_radius_top_right dmd_style_hidden dmd_style_input top right">
							<span class="dmd_style_desc dmd_style_hidden top right"><span>Top-right corner radius</span></span>
							<input name="', $plugin_name, '[', $setting['name'], '][border-bottom-right-radius]" value="', @ $setting['value']['border-bottom-right-radius'], '" type="text" class="dmd_border_radius_bottom_right dmd_style_hidden dmd_style_input bottom right">
							<span class="dmd_style_desc dmd_style_hidden bottom right"><span>Bottom-right corner radius</span></span>
							<input name="', $plugin_name, '[', $setting['name'], '][border-bottom-left-radius]" value="', @ $setting['value']['border-bottom-left-radius'], '" type="text" class="dmd_border_radius_bottom_left dmd_style_hidden dmd_style_input bottom left">
							<span class="dmd_style_desc dmd_style_hidden bottom left"><span>Bottom-left corner radius</span></span>';
					}
				}
				if(in_array('padding', $setting['data'])) {
					$close_count++;
					echo '<div class="dmd_padding_block dmd_style_box">
						<span class="dmd_padding_text dmd_style_text">Padding</span>
						<input name="', $plugin_name, '[', $setting['name'], '][padding-top]" value="', @ $setting['value']['padding-top'], '" type="text" class="dmd_padding_top dmd_style_hidden dmd_style_input top center">
						<span class="dmd_style_desc dmd_style_hidden top center"><span>Top</span></span>
						<input name="', $plugin_name, '[', $setting['name'], '][padding-right]" value="', @ $setting['value']['padding-right'], '" type="text" class="dmd_padding_right dmd_style_hidden dmd_style_input right middle">
						<span class="dmd_style_desc dmd_style_hidden right middle"><span>Right</span></span>
						<input name="', $plugin_name, '[', $setting['name'], '][padding-bottom]" value="', @ $setting['value']['padding-bottom'], '" type="text" class="dmd_padding_bottom dmd_style_hidden dmd_style_input bottom center">
						<span class="dmd_style_desc dmd_style_hidden bottom center"><span>Bottom</span></span>
						<input name="', $plugin_name, '[', $setting['name'], '][padding-left]" value="', @ $setting['value']['padding-left'], '" type="text" class="dmd_padding_left dmd_style_hidden dmd_style_input left middle">
						<span class="dmd_style_desc dmd_style_hidden left middle"><span>Left</span></span>';
				}
				if(in_array('width', $setting['data']) || in_array('height', $setting['data']) || in_array('background-color', $setting['data']) || in_array('color', $setting['data'])) {
					$close_count++;
					echo '<div class="dmd_content_block dmd_style_box">
						<span class="dmd_content_text dmd_style_text">Content</span>';
					if(in_array('width', $setting['data'])) {
						echo '<input name="', $plugin_name, '[', $setting['name'], '][width]" value="', @ $setting['value']['width'], '" type="text" class="dmd_content_width dmd_style_hidden dmd_style_input top center">
							<span class="dmd_style_desc dmd_style_hidden top center"><span>Width</span></span>';
					}
					if(in_array('height', $setting['data'])) {
						echo '<input name="', $plugin_name, '[', $setting['name'], '][height]" value="', @ $setting['value']['height'], '" type="text" class="dmd_content_height dmd_style_hidden dmd_style_input right middle">
							<span class="dmd_style_desc dmd_style_hidden right middle"><span>Height</span></span>';
					}
					if(in_array('background-color', $setting['data'])) {
						echo '<input name="', $plugin_name, '[', $setting['name'], '][background-color]" value="', @ $setting['value']['background-color'], '" type="text" class="dmd_color_picker dmd_background_color dmd_style_hidden dmd_style_input_color dmd_style_input bottom center">
							<span class="dmd_style_desc dmd_style_hidden bottom center"><span>Background color</span></span>';
					}
					if(in_array('color', $setting['data'])) {
						echo '<input name="', $plugin_name, '[', $setting['name'], '][color]" value="', @ $setting['value']['color'], '" type="text" class="dmd_color_picker dmd_color dmd_style_hidden dmd_style_input_color dmd_style_input middle left">
							<span class="dmd_style_desc dmd_style_hidden middle left"><span>Font color</span></span>';
					}
				}
				for($i = 0; $i < $close_count; $i++) {
					echo '</div>';
				}
				echo '</div>';
			}
			self::after_setting($setting);
			$previous_setting = $setting;
		}
	}
	public static function before_setting($setting)
	{
		echo '<div class="dmd_each_setting_block ', $setting['name'], '_block"', ( ! empty($setting['style']) ? ' style="' . $setting['style'] . '"' : '' ), '>';
		if( isset($setting['text_header']) )
		{
			echo '<h3>', $setting['text_header'], '</h3>';
		}
		if( isset($setting['text_before']) )
		{
			echo '<div>', $setting['text_before'], '</div>';
		}
	}
	public static function after_setting($setting)
	{
		if( isset($setting['text_after']) )
		{
			echo '<div>', $setting['text_after'], '</div>';
		}
		echo '</div>';
		if( isset($setting['hide']) && is_array($setting['hide']) )
		{
			echo '<script>jQuery(document).on( "change", ".', $setting['hide']['name'], '_input", function(event) {';
			if($setting['hide']['type'] == 'checkbox')
			{
				echo 'if(jQuery(this).prop("checked") == ', ($setting['hide']['show'] ? 'true' : 'false'), ')
				{
					jQuery(".', $setting['name'], '_block").show();
				}
				else
				{
					jQuery(".', $setting['name'], '_block").hide();
				}';
			}
			elseif($setting['hide']['type'] == 'select')
			{
				echo 'if(jQuery.inArray(jQuery(this).val(), ["', implode( '", "', $setting['hide']['data'] ), '"]) ', ($setting['hide']['show'] ? '!=' : '=='), ' -1 )
				{
					jQuery(".', $setting['name'], '_block").show();
				}
				else
				{
					jQuery(".', $setting['name'], '_block").hide();
				}';
			}
			echo '});';
			if($setting['hide']['type'] == 'checkbox')
			{
				echo 'if(jQuery(".', $setting['hide']['name'], '_input").prop("checked") == ', ($setting['hide']['show'] ? 'true' : 'false'), ')
				{
					jQuery(".', $setting['name'], '_block").show();
				}
				else
				{
					jQuery(".', $setting['name'], '_block").hide();
				}';
			}
			elseif($setting['hide']['type'] == 'select')
			{
				echo 'if(jQuery.inArray(jQuery(".', $setting['hide']['name'], '_input").val(), ["', implode( '", "', $setting['hide']['data'] ), '"]) ', ($setting['hide']['show'] ? '!=' : '=='), ' -1 )
				{
					jQuery(".', $setting['name'], '_block").show();
				}
				else
				{
					jQuery(".', $setting['name'], '_block").hide();
				}';
			}
			echo '</script>';
		}
		if( isset($setting['replace']) && is_array($setting['replace']) )
		{
			echo '<script>jQuery(document).on( "change", ".', $setting['replace']['name'], '_input", function(event) {';
			$is_first_replace = true;
			foreach($setting['replace']['values'] as $value => $set_this) {
				if( ! $is_first_replace ) {
					echo 'else ';
				} else {
					$is_first_replace = false;
				}
				echo 'if(jQuery(this).val() == "'.$value.'") {';
				echo 'jQuery(".', $setting['name'], '_input").val("'.$set_this.'");';
				echo '}';
			}
			echo 'else {';
			echo 'jQuery(".', $setting['name'], '_input").val("");';
			echo '}';
			echo '});</script>';
		}
	}
	public static function convert_settings_to_style($style_setting) {
		$size = array('border-top-width', 'border-bottom-width', 'border-left-width', 'border-right-width', 'margin-top', 'margin-bottom', 'margin-left', 'margin-right', 
		'padding-top', 'padding-bottom', 'padding-left', 'padding-right', 'width', 'height', 'font-size', 'top', 'bottom', 'left', 'right',
		'border-top-left-radius', 'border-top-right-radius', 'border-bottom-left-radius', 'border-bottom-right-radius');
		$color = array('color', 'border-color', 'background-color');
		$style_string = '';
		if( !empty($style_setting) && is_array($style_setting) ) {
			foreach($style_setting as $style_name => $style_value) {
				if($style_value !== '') {
					if(in_array($style_name, $size)) {
						if( strpos($style_value,'%') === false && strpos($style_value,'px') === false && strpos($style_value,'em') === false && strpos($style_value,'rem') === false ) {
							$style_value = $style_value.'px';
						}
					} elseif(in_array($style_name, $color)) {
						if( strpos($style_value,'#') === false && strpos($style_value,'rgb(') === false && strpos($style_value,'rgba(') === false ) {
							$style_value = '#'.$style_value;
						}
					}
					$style_string .= $style_name.':'.$style_value.'!important;';
				}
			}
		}
		return $style_string;
	}
}
new dmd_settings;
?>