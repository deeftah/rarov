<?php
/**
 * Plugin Name: DMD Infinite Scroll
 * Plugin URI: 
 * Description: Infinite Scroll for WordPress and WooCommerce
 * Version: 0.9.1
 * Author: Dead-MustDie
 * Author URI: http://dead-mustdie.ru
 */

define( "DMD_is_path", plugin_dir_path( __FILE__ ) . "templates/" );
require_once dirname( __FILE__ ) . '/includes/settings.php';
require_once dirname( __FILE__ ) . '/includes/functions.php';

class DMD_infinite_scroll {
	public static $info = array(
		'version' =>	'0.9.1',
		'name'	=>		'DMD Infinite Scroll',
		'slug'	=>		'dmd_is'
	);
	public static $settings;
	function __construct() {
		$options = dmd_settings::correct_data(self::$info['slug']);
		$selectors_count = 1;
		if( !empty($options) && ! empty($options['selectors_setting_amount']) ) {
			$selectors_count = $options['selectors_setting_amount'];
		}
		$selectors_setting_number = array();
		for($i = 0; $i < $selectors_count; $i++) {
			$selectors_setting_number[$i] = __('Settings set', 'dmd_is').' '.($i + 1);
		}
		$selectors_amount_data = array();
		for($i = 1; $i < 11; $i++) {
			$selectors_amount_data[$i] = $i;
		}
		$data_tabs = array(
			array(
				'text' 			=> __('Sets count', 'dmd_is'),
				'type'			=> 'select',
				'name'			=> 'selectors_setting_amount',
				'value' 		=> 0,
				'data' 			=> $selectors_amount_data,
				'text_after' 	=> '<button type="button" class="button dmd_save_and_reload">'.__('Save and reload', 'dmd_is').'</button>',
			),
			array(
				'text' 	=> __('Current set', 'dmd_is'),
				'type'	=> 'select',
				'name'	=> 'selectors_setting_number',
				'value' => 0,
				'data' 	=> $selectors_setting_number,
			),
		);
		for($i = 0; $i < $selectors_count; $i++) {
			$data_tab = array(
				'type' 			=> 'block',
				'name' 			=> $i.'selectors_setting',
				'hide' 			=> array( 'type' => 'select', 'show' => true, 'name' => 'selectors_setting_number', 'data' => array($i) ),
				'data'			=> array(
					array(
						'text_header'	=> __('Setting set', 'dmd_is').' '.($i + 1),
						'type' 			=> 'block',
						'name' 			=> $i.'default_block_type',
						'style'			=> 'border-top: 2px solid #888888; margin-top: 1em;',
						'data'			=> array(
							array(
								'text' 			=> __('Presets', 'dmd_is'),
								'type' 			=> 'select',
								'name' 			=> $i.'preset_selectors',
								'value' 		=> 'none',
								'style'			=> 'border-top: 2px solid #888888; margin-top: 1em;',
								'data' 			=> array(
									'none' 				=> __('--Other--', 'dmd_is'),
									'twenty_seventeen' 	=> __('Twenty Seventeen', 'dmd_is'),
									'twenty_sixteen' 	=> __('Twenty Sixteen', 'dmd_is'),
									'twenty_fifteen' 	=> __('Twenty Fifteen', 'dmd_is'),
									'twenty_fourteen' 	=> __('Twenty Fourteen', 'dmd_is'),
									'storefront' 		=> __('Storefront', 'dmd_is'),
								),
							),
							array(
								'text_header' => __('Selectors', 'dmd_is'),
								'text' 	=> __('Posts selector', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'products_selector',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array(
									'twenty_seventeen' 	=> '.site-main',
									'twenty_sixteen' 	=> '.site-main',
									'twenty_fifteen' 	=> '.site-main',
									'twenty_fourteen' 	=> '.site-main',
									'storefront' 		=> '.site-main',
								) ),
								'value' => '.site-main'
							),
							array(
								'text' 	=> __('Post selector', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'product_selector',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array(
									'twenty_seventeen' 	=> 'article.post',
									'twenty_sixteen' 	=> '.post',
									'twenty_fifteen' 	=> '.post',
									'twenty_fourteen' 	=> '.post',
									'storefront' 		=> '.post',
								) ),
								'value' => '.post'
							),
							array(
								'text' 	=> __('Pagination selector', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'pagination_selector',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array(
									'twenty_seventeen' 	=> '.navigation.pagination',
									'twenty_sixteen' 	=> '.navigation.pagination',
									'twenty_fifteen' 	=> '.navigation.pagination',
									'twenty_fourteen' 	=> '.navigation.paging-navigation',
									'storefront' 		=> '.storefront-pagination',
								) ),
								'value' => '.navigation.pagination'
							),
							array(
								'text' 	=> __('Next page selector', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'next_page_selector',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array(
									'twenty_seventeen' 	=> '.navigation.pagination a.next',
									'twenty_sixteen' 	=> '.navigation.pagination a.next',
									'twenty_fifteen' 	=> '.navigation.pagination a.next',
									'twenty_fourteen' 	=> '.navigation.paging-navigation a.next',
									'storefront' 		=> '.storefront-pagination a.next',
								) ),
								'value' => '.navigation.pagination a.next'
							),
							array(
								'text' 	=> __('Previous page selector', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'prev_page_selector',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array(
									'twenty_seventeen' 	=> '.navigation.pagination a.prev',
									'twenty_sixteen' 	=> '.navigation.pagination a.prev',
									'twenty_fifteen' 	=> '.navigation.pagination a.prev',
									'twenty_fourteen' 	=> '.navigation.paging-navigation a.prev',
									'storefront' 		=> '.storefront-pagination a.prev',
								) ),
								'value' => '.navigation.pagination a.prev'
							),
							array(
								'text' 	=> __('Pagination pages selector', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'pages_selector',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array(
									'twenty_seventeen' 	=> '.navigation.pagination a.page-numbers',
									'twenty_sixteen' 	=> '.navigation.pagination a.page-numbers',
									'twenty_fifteen' 	=> '.navigation.pagination a.page-numbers',
									'twenty_fourteen' 	=> '.navigation.paging-navigation a.page-numbers',
									'storefront' 		=> '.storefront-pagination a',
								) ),
								'value' => '.navigation.pagination a.page-numbers'
							),
							array(
								'text' 	=> __('Selectors to replace', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'other_selectors',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array('none' => '') ),
								'value' => ''
							),
							array(
								'text_header'	=> __('Load Settings', 'dmd_is'),
								'text' 			=> __('Lazy Load XT', 'dmd_is'),
								'text_2' 		=> __('Load images with lazy load plugin', 'dmd_is'),
								'type' 			=> 'checkbox',
								'name' 			=> $i.'lazy_load',
								'value' 		=> false,
								'style'			=> 'border-top: 2px solid #888888; margin-top: 1em;'
							),
							array(
								'text' 			=> __('Lazy Load animation', 'dmd_is'),
								'type' 			=> 'select',
								'name' 			=> $i.'lazy_load_anim',
								'value' 		=> '',
								'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'lazy_load' ),
								'data' 			=> get_dmd_animation_classes(),
							),
							array(
								'text' 			=> __('Load products type', 'dmd_is'),
								'type' 			=> 'select',
								'name' 			=> $i.'load_type',
								'value' 		=> 'infinite_scroll',
								'data' 			=> array(
									'infinite_scroll' 	=> __('Infinite Scroll', 'dmd_is'),
									'load_button' 		=> __('Load Button', 'dmd_is'),
									'pagination' 		=> __('Pagination', 'dmd_is'),
								),
							),
							array(
								'text_header'	=> __('Infinite Scroll Settings', 'dmd_is'),
								'type' 			=> 'block',
								'name' 			=> $i.'is_setup_block',
								'hide' => array( 'type' => 'select', 'show' => true, 'name' => $i.'load_type', 'data' => array('infinite_scroll') ),
								'data'			=> array(
									array(
										'text' 			=> __('Few Pages', 'dmd_is'),
										'text_2' 		=> __('Use only few pages before current', 'dmd_is'),
										'type' 			=> 'checkbox',
										'name' 			=> $i.'is_only_few_pages',
										'value' 		=> false,
									),
									array(
										'type' 			=> 'block',
										'name' 			=> $i.'is_few_pages_enable_block',
										'hide' => array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'is_only_few_pages' ),
										'data'			=> array(
											array(
												'text' 			=> __('Page count', 'dmd_is'),
												'type' 			=> 'number',
												'name' 			=> $i.'is_few_page_count',
												'value' 		=> '1',
											),
										),
									),
								),
							),
							array(
								'text_header'	=> __('Load Button Settings', 'dmd_is'),
								'type' 			=> 'block',
								'name' 			=> $i.'lb_setup_block',
								'hide' => array('type' => 'select', 'show' => true, 'name' => $i.'load_type', 'data' => array('load_button') ),
								'data'			=> array(
									array(
										'text' 			=> __('Replace products', 'dmd_is'),
										'text_2' 		=> __('Replace current products with new one', 'dmd_is'),
										'type' 			=> 'checkbox',
										'name' 			=> $i.'lb_replace_products',
										'value' 		=> false,
									),
									array(
										'type' 			=> 'block',
										'name' 			=> $i.'lb_more_pages_block',
										'hide' => array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'lb_replace_products' ),
										'data'			=> array(
											array(
												'text' 			=> __('More pages', 'dmd_is'),
												'text_2' 		=> __('Leave more then one pages', 'dmd_is'),
												'type' 			=> 'checkbox',
												'name' 			=> $i.'lb_only_few_pages',
												'value' 		=> false,
											),
											array(
												'type' 			=> 'block',
												'name' 			=> $i.'lb_few_pages_enable_block',
												'hide' => array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'lb_only_few_pages' ),
												'data'			=> array(
													array(
														'text' 			=> __('Page count', 'dmd_is'),
														'type' 			=> 'number',
														'name' 			=> $i.'lb_few_page_count',
														'value' 		=> '1',
													),
												),
											),
										),
									),
								),
							),
							array(
								'type' 			=> 'block',
								'name' 			=> $i.'hide_lazy_load',
								'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'lazy_load' ),
								'data'			=> array(
									array(
										'text' 			=> __('Hide Lazy Load animation', 'dmd_is'),
										'text_2' 		=> __('Works only when products will be replaced', 'dmd_is'),
										'type' 			=> 'select',
										'name' 			=> $i.'hide_lazy_load_anim',
										'value' 		=> '',
										'hide' 			=> array('type' => 'select', 'show' => true, 'name' => $i.'load_type', 'data' => array('load_button', 'pagination') ),
										'data' 			=> get_dmd_animation_classes(),
									),
								),
							),
							array(
								'text_header'	=> __('Previous page button', 'dmd_is'),
								'type' 			=> 'block',
								'name' 			=> $i.'previous_page_block',
								'hide' 			=> array('type' => 'select', 'show' => true, 'name' => $i.'load_type', 'data' => array('infinite_scroll', 'load_button') ),
								'data'			=> array(
									array(
										'text' 			=> __('Previous page button', 'dmd_is'),
										'text_2' 		=> __('Display button to load previous page, when products loaded not from first page', 'dmd_is'),
										'type' 			=> 'checkbox',
										'name' 			=> $i.'previous_page_button',
										'value' 		=> false,
									),
									array(
										'type' 			=> 'block',
										'name' 			=> $i.'previous_page_load_block',
										'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'previous_page_button' ),
										'data'			=> array(
											array(
												'text' 			=> __('Previous page load', 'dmd_is'),
												'type' 			=> 'select',
												'name' 			=> $i.'previous_load_type',
												'value' 		=> 'before',
												'data' 			=> array(
													'button' 		=> __('Button before products', 'dmd_is'),
													'scroll' 		=> __('Infinite scroll', 'dmd_is'),
												),
											),
											array(
												'text' 			=> __('Lazy Load animation', 'dmd_is'),
												'text_2' 		=> __('Lazy Load animation for previous products', 'dmd_is'),
												'type' 			=> 'select',
												'name' 			=> $i.'prev_lazy_load_anim',
												'value' 		=> '',
												'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'lazy_load' ),
												'data' 			=> get_dmd_animation_classes(),
											),
										),
									),
								),
							),
						),
					),
					array(
						'text' 			=> __('Mobile device', 'dmd_is'),
						'text_2' 		=> __('Use different pagination for mobile devices', 'dmd_is'),
						'type' 			=> 'checkbox',
						'name' 			=> $i.'mobile_other',
						'value' 		=> false,
					),
					array(
						'type' 			=> 'block',
						'name' 			=> $i.'mobile_block_type',
						'hide' 			=> array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'mobile_other' ),
						'style'			=> 'border:2px solid #888888; padding: 5px;',
						'data'			=> array(
							array(
								'text' 			=> __('Maximum width', 'dmd_is'),
								'text_2' 		=> __('Maximum window width when this settings will be used', 'dmd_is'),
								'type' 			=> 'number',
								'name' 			=> $i.'mobile_maximum_width',
								'value' 		=> '640',
							),
							array(
								'text' 			=> __('Lazy Load XT', 'dmd_is'),
								'text_2' 		=> __('Load images with lazy load plugin', 'dmd_is'),
								'type' 			=> 'checkbox',
								'name' 			=> $i.'lazy_load-mobile',
								'value' 		=> false,
							),
							array(
								'text' 			=> __('Lazy Load animation', 'dmd_is'),
								'type' 			=> 'select',
								'name' 			=> $i.'lazy_load_anim-mobile',
								'value' 		=> '',
								'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'lazy_load-mobile' ),
								'data' 			=> get_dmd_animation_classes(),
							),
							array(
								'text' 			=> __('Load products type', 'dmd_is'),
								'type' 			=> 'select',
								'name' 			=> $i.'load_type-mobile',
								'value' 		=> 'infinite_scroll',
								'data' 			=> array(
									'infinite_scroll' 	=> __('Infinite Scroll', 'dmd_is'),
									'load_button' 		=> __('Load Button', 'dmd_is'),
									'pagination' 		=> __('Pagination', 'dmd_is'),
								),
							),
							array(
								'text_header'	=> __('Infinite Scroll Settings', 'dmd_is'),
								'type' 			=> 'block',
								'name' 			=> $i.'is_setup_block-mobile',
								'hide' => array( 'type' => 'select', 'show' => true, 'name' => $i.'load_type-mobile', 'data' => array('infinite_scroll') ),
								'data'			=> array(
									array(
										'text' 			=> __('Few Pages', 'dmd_is'),
										'text_2' 		=> __('Use only few pages before current', 'dmd_is'),
										'type' 			=> 'checkbox',
										'name' 			=> $i.'is_only_few_pages-mobile',
										'value' 		=> false,
									),
									array(
										'type' 			=> 'block',
										'name' 			=> $i.'is_few_pages_enable_block-mobile',
										'hide' => array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'is_only_few_pages-mobile' ),
										'data'			=> array(
											array(
												'text' 			=> __('Page count', 'dmd_is'),
												'type' 			=> 'number',
												'name' 			=> $i.'is_few_page_count-mobile',
												'value' 		=> '1',
											),
										),
									),
								),
							),
							array(
								'text_header'	=> __('Load Button Settings', 'dmd_is'),
								'type' 			=> 'block',
								'name' 			=> $i.'lb_setup_block-mobile',
								'hide' => array('type' => 'select', 'show' => true, 'name' => $i.'load_type-mobile', 'data' => array('load_button') ),
								'data'			=> array(
									array(
										'text' 			=> __('Replace products', 'dmd_is'),
										'text_2' 		=> __('Replace current products with new one', 'dmd_is'),
										'type' 			=> 'checkbox',
										'name' 			=> $i.'lb_replace_products-mobile',
										'value' 		=> false,
									),
									array(
										'type' 			=> 'block',
										'name' 			=> $i.'lb_more_pages_block-mobile',
										'hide' => array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'lb_replace_products-mobile' ),
										'data'			=> array(
											array(
												'text' 			=> __('More pages', 'dmd_is'),
												'text_2' 		=> __('Leave more then one pages', 'dmd_is'),
												'type' 			=> 'checkbox',
												'name' 			=> $i.'lb_only_few_pages-mobile',
												'value' 		=> false,
											),
											array(
												'type' 			=> 'block',
												'name' 			=> $i.'lb_few_pages_enable_block-mobile',
												'hide' => array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'lb_only_few_pages-mobile' ),
												'data'			=> array(
													array(
														'text' 			=> __('Page count', 'dmd_is'),
														'type' 			=> 'number',
														'name' 			=> $i.'lb_few_page_count-mobile',
														'value' 		=> '1',
													),
												),
											),
										),
									),
								),
							),
							array(
								'text_header'	=> __('Previous page button', 'dmd_is'),
								'type' 			=> 'block',
								'name' 			=> $i.'previous_page_block-mobile',
								'hide' 			=> array('type' => 'select', 'show' => true, 'name' => $i.'load_type-mobile', 'data' => array('infinite_scroll', 'load_button') ),
								'data'			=> array(
									array(
										'text' 			=> __('Previous page button', 'dmd_is'),
										'text_2' 		=> __('Display button to load previous page, when products loaded not from first page', 'dmd_is'),
										'type' 			=> 'checkbox',
										'name' 			=> $i.'previous_page_button-mobile',
										'value' 		=> false,
									),
									array(
										'type' 			=> 'block',
										'name' 			=> $i.'previous_page_load_block-mobile',
										'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'previous_page_button-mobile' ),
										'data'			=> array(
											array(
												'text' 			=> __('Previous page load', 'dmd_is'),
												'type' 			=> 'select',
												'name' 			=> $i.'previous_load_type-mobile',
												'value' 		=> 'before',
												'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'previous_page_button-mobile' ),
												'data' 			=> array(
													'button' 		=> __('Button before products', 'dmd_is'),
													'scroll' 		=> __('Infinite scroll', 'dmd_is'),
												),
											),
											array(
												'text' 			=> __('Lazy Load animation', 'dmd_is'),
												'text_2' 		=> __('Lazy Load animation for previous products', 'dmd_is'),
												'type' 			=> 'select',
												'name' 			=> $i.'prev_lazy_load_anim-mobile',
												'value' 		=> '',
												'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'lazy_load-mobile' ),
												'data' 			=> get_dmd_animation_classes(),
											),
										),
									),
								),
							),
						),
					),
					array(
						'text' 			=> __('Threshold in px for infinite scroll', 'dmd_is'),
						'type' 			=> 'number',
						'name' 			=> $i.'infinite_scroll_threshold',
						'value' 		=> '200',
						'style'			=> 'border-top:2px solid #888888; margin-top: 1em;',
					),
					array(
						'text' 			=> __('Scroll to the top', 'dmd_is'),
						'text_2' 		=> __('Scroll to the top of the page, when products was replaced with new one', 'dmd_is'),
						'type' 			=> 'checkbox',
						'name' 			=> $i.'scroll_top',
						'value' 		=> false,
					),
					array(
						'text' 			=> __('Threshold in px for scroll top from top of products block', 'dmd_is'),
						'type' 			=> 'number',
						'name' 			=> $i.'scroll_top_threshold',
						'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'scroll_top' ),
						'value' 		=> '-50',
					),
				),
			);
			$data_tabs[] = $data_tab;
		}
		self::$settings = array();
		self::$settings[] = array(
			'type' => 'general',
			'text' => __('General', 'dmd_is'),
			'name' => 'general_block',
			'data' => $data_tabs,
		);
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			$i = 'w';
			$data_tab = array(
				'type' 			=> 'block',
				'name' 			=> $i.'selectors_setting',
				'data'			=> array(
					array(
						'text' 			=> __('Products per page', 'dmd_is'),
						'type' 			=> 'number',
						'name' 			=> 'wc_products_per_page',
						'value' 		=> '',
						'additional' 	=> 'min="1" max="200"'
					),
					array(
						'text_header'	=> __('WooCommerce set', 'dmd_is'),
						'type' 			=> 'block',
						'name' 			=> $i.'default_block_type',
						'style'			=> 'border-top: 2px solid #888888; margin-top: 1em;',
						'data'			=> array(
							array(
								'text' 			=> __('Presets', 'dmd_is'),
								'type' 			=> 'select',
								'name' 			=> $i.'preset_selectors',
								'value' 		=> 'none',
								'style'			=> 'border-top: 2px solid #888888; margin-top: 1em;',
								'data' 			=> array(
									'none' 					=> __('--Other--', 'dmd_is'),
									'woocommerce' 			=> __('WooCommerce Default', 'dmd_is'),
									'woocommerce2' 			=> __('WooCommerce Alternative', 'dmd_is'),
									'woocommerce_universal' => __('WooCommerce Universal', 'dmd_is'),
								),
							),
							array(
								'text_header' => __('Selectors', 'dmd_is'),
								'text' 	=> __('Products selector', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'products_selector',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array(
									'woocommerce' 			=> 'ul.products',
									'woocommerce2' 			=> 'div.products',
									'woocommerce_universal'	=> '.products',
								) ),
								'value' => 'ul.products'
							),
							array(
								'text' 	=> __('Product selector', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'product_selector',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array(
									'woocommerce' 			=> 'li.product',
									'woocommerce2' 			=> 'div.product',
									'woocommerce_universal'	=> '.product',
								) ),
								'value' => 'li.product'
							),
							array(
								'text' 	=> __('Pagination selector', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'pagination_selector',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array(
									'woocommerce' 			=> '.woocommerce-pagination',
									'woocommerce2' 			=> '.woocommerce-pagination',
									'woocommerce_universal'	=> '.woocommerce-pagination, .woocommerce .pagination',
								) ),
								'value' => '.woocommerce-pagination'
							),
							array(
								'text' 	=> __('Next page selector', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'next_page_selector',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array(
									'woocommerce' 		=> '.woocommerce-pagination a.next',
									'woocommerce2' 			=> '.woocommerce-pagination a.next',
									'woocommerce_universal'	=> '.woocommerce-pagination a.next, .woocommerce .pagination a.next',
								) ),
								'value' => '.woocommerce-pagination a.next'
							),
							array(
								'text' 	=> __('Previous page selector', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'prev_page_selector',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array(
									'woocommerce' 		=> '.woocommerce-pagination a.prev',
									'woocommerce2' 			=> '.woocommerce-pagination a.prev',
									'woocommerce_universal'	=> '.woocommerce-pagination a.prev, .woocommerce .pagination a.prev',
								) ),
								'value' => '.woocommerce-pagination a.prev'
							),
							array(
								'text' 	=> __('Pagination pages selector', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'pages_selector',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array(
									'woocommerce' 		=> '.woocommerce-pagination a',
									'woocommerce2' 			=> '.woocommerce-pagination a',
									'woocommerce_universal'	=> '.woocommerce-pagination a, .woocommerce .pagination a',
								) ),
								'value' => '.woocommerce-pagination a'
							),
							array(
								'text' 	=> __('WooCommerce result count selector', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'wc_result_count',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array(
									'woocommerce' 			=> '.woocommerce-result-count',
									'woocommerce2' 			=> '.woocommerce-result-count',
									'woocommerce_universal'	=> '.woocommerce-result-count',
								) ),
								'value' => '.woocommerce-result-count'
							),
							array(
								'text' 	=> __('Selectors to replace', 'dmd_is'),
								'type' 	=> 'text',
								'name' 	=> $i.'other_selectors',
								'replace' => array( 'name' => $i.'preset_selectors', 'values' => array('none' => '') ),
								'value' => ''
							),
							array(
								'text_header'	=> __('Load Settings', 'dmd_is'),
								'text' 			=> __('Lazy Load XT', 'dmd_is'),
								'text_2' 		=> __('Load images with lazy load plugin', 'dmd_is'),
								'type' 			=> 'checkbox',
								'name' 			=> $i.'lazy_load',
								'value' 		=> false,
								'style'			=> 'border-top: 2px solid #888888; margin-top: 1em;'
							),
							array(
								'text' 			=> __('Lazy Load animation', 'dmd_is'),
								'type' 			=> 'select',
								'name' 			=> $i.'lazy_load_anim',
								'value' 		=> '',
								'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'lazy_load' ),
								'data' 			=> get_dmd_animation_classes(),
							),
							array(
								'text' 			=> __('Load products type', 'dmd_is'),
								'type' 			=> 'select',
								'name' 			=> $i.'load_type',
								'value' 		=> 'infinite_scroll',
								'data' 			=> array(
									'infinite_scroll' 	=> __('Infinite Scroll', 'dmd_is'),
									'load_button' 		=> __('Load Button', 'dmd_is'),
									'pagination' 		=> __('Pagination', 'dmd_is'),
								),
							),
							array(
								'text_header'	=> __('Infinite Scroll Settings', 'dmd_is'),
								'type' 			=> 'block',
								'name' 			=> $i.'is_setup_block',
								'hide' => array( 'type' => 'select', 'show' => true, 'name' => $i.'load_type', 'data' => array('infinite_scroll') ),
								'data'			=> array(
									array(
										'text' 			=> __('Few Pages', 'dmd_is'),
										'text_2' 		=> __('Use only few pages before current', 'dmd_is'),
										'type' 			=> 'checkbox',
										'name' 			=> $i.'is_only_few_pages',
										'value' 		=> false,
									),
									array(
										'type' 			=> 'block',
										'name' 			=> $i.'is_few_pages_enable_block',
										'hide' => array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'is_only_few_pages' ),
										'data'			=> array(
											array(
												'text' 			=> __('Page count', 'dmd_is'),
												'type' 			=> 'number',
												'name' 			=> $i.'is_few_page_count',
												'value' 		=> '1',
											),
										),
									),
								),
							),
							array(
								'text_header'	=> __('Load Button Settings', 'dmd_is'),
								'type' 			=> 'block',
								'name' 			=> $i.'lb_setup_block',
								'hide' => array('type' => 'select', 'show' => true, 'name' => $i.'load_type', 'data' => array('load_button') ),
								'data'			=> array(
									array(
										'text' 			=> __('Replace products', 'dmd_is'),
										'text_2' 		=> __('Replace current products with new one', 'dmd_is'),
										'type' 			=> 'checkbox',
										'name' 			=> $i.'lb_replace_products',
										'value' 		=> false,
									),
									array(
										'type' 			=> 'block',
										'name' 			=> $i.'lb_more_pages_block',
										'hide' => array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'lb_replace_products' ),
										'data'			=> array(
											array(
												'text' 			=> __('More pages', 'dmd_is'),
												'text_2' 		=> __('Leave more then one pages', 'dmd_is'),
												'type' 			=> 'checkbox',
												'name' 			=> $i.'lb_only_few_pages',
												'value' 		=> false,
											),
											array(
												'type' 			=> 'block',
												'name' 			=> $i.'lb_few_pages_enable_block',
												'hide' => array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'lb_only_few_pages' ),
												'data'			=> array(
													array(
														'text' 			=> __('Page count', 'dmd_is'),
														'type' 			=> 'number',
														'name' 			=> $i.'lb_few_page_count',
														'value' 		=> '1',
													),
												),
											),
										),
									),
								),
							),
							array(
								'type' 			=> 'block',
								'name' 			=> $i.'hide_lazy_load',
								'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'lazy_load' ),
								'data'			=> array(
									array(
										'text' 			=> __('Hide Lazy Load animation', 'dmd_is'),
										'text_2' 		=> __('Works only when products will be replaced', 'dmd_is'),
										'type' 			=> 'select',
										'name' 			=> $i.'hide_lazy_load_anim',
										'value' 		=> '',
										'hide' 			=> array('type' => 'select', 'show' => true, 'name' => $i.'load_type', 'data' => array('load_button', 'pagination') ),
										'data' 			=> get_dmd_animation_classes(),
									),
								),
							),
							array(
								'text_header'	=> __('Previous page button', 'dmd_is'),
								'type' 			=> 'block',
								'name' 			=> $i.'previous_page_block',
								'hide' 			=> array('type' => 'select', 'show' => true, 'name' => $i.'load_type', 'data' => array('infinite_scroll', 'load_button') ),
								'data'			=> array(
									array(
										'text' 			=> __('Previous page button', 'dmd_is'),
										'text_2' 		=> __('Display button to load previous page, when products loaded not from first page', 'dmd_is'),
										'type' 			=> 'checkbox',
										'name' 			=> $i.'previous_page_button',
										'value' 		=> false,
									),
									array(
										'type' 			=> 'block',
										'name' 			=> $i.'previous_page_load_block',
										'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'previous_page_button' ),
										'data'			=> array(
											array(
												'text' 			=> __('Previous page load', 'dmd_is'),
												'type' 			=> 'select',
												'name' 			=> $i.'previous_load_type',
												'value' 		=> 'before',
												'data' 			=> array(
													'button' 		=> __('Button before products', 'dmd_is'),
													'scroll' 		=> __('Infinite scroll', 'dmd_is'),
												),
											),
											array(
												'text' 			=> __('Lazy Load animation', 'dmd_is'),
												'text_2' 		=> __('Lazy Load animation for previous products', 'dmd_is'),
												'type' 			=> 'select',
												'name' 			=> $i.'prev_lazy_load_anim',
												'value' 		=> '',
												'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'lazy_load' ),
												'data' 			=> get_dmd_animation_classes(),
											),
										),
									),
								),
							),
						),
					),
					array(
						'text' 			=> __('Mobile device', 'dmd_is'),
						'text_2' 		=> __('Use different pagination for mobile devices', 'dmd_is'),
						'type' 			=> 'checkbox',
						'name' 			=> $i.'mobile_other',
						'value' 		=> false,
					),
					array(
						'type' 			=> 'block',
						'name' 			=> $i.'mobile_block_type',
						'hide' 			=> array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'mobile_other' ),
						'style'			=> 'border:2px solid #888888; padding: 5px;',
						'data'			=> array(
							array(
								'text' 			=> __('Maximum width', 'dmd_is'),
								'text_2' 		=> __('Maximum window width when this settings will be used', 'dmd_is'),
								'type' 			=> 'number',
								'name' 			=> $i.'mobile_maximum_width',
								'value' 		=> '640',
							),
							array(
								'text' 			=> __('Lazy Load XT', 'dmd_is'),
								'text_2' 		=> __('Load images with lazy load plugin', 'dmd_is'),
								'type' 			=> 'checkbox',
								'name' 			=> $i.'lazy_load-mobile',
								'value' 		=> false,
							),
							array(
								'text' 			=> __('Lazy Load animation', 'dmd_is'),
								'type' 			=> 'select',
								'name' 			=> $i.'lazy_load_anim-mobile',
								'value' 		=> '',
								'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'lazy_load-mobile' ),
								'data' 			=> get_dmd_animation_classes(),
							),
							array(
								'text' 			=> __('Load products type', 'dmd_is'),
								'type' 			=> 'select',
								'name' 			=> $i.'load_type-mobile',
								'value' 		=> 'infinite_scroll',
								'data' 			=> array(
									'infinite_scroll' 	=> __('Infinite Scroll', 'dmd_is'),
									'load_button' 		=> __('Load Button', 'dmd_is'),
									'pagination' 		=> __('Pagination', 'dmd_is'),
								),
							),
							array(
								'text_header'	=> __('Infinite Scroll Settings', 'dmd_is'),
								'type' 			=> 'block',
								'name' 			=> $i.'is_setup_block-mobile',
								'hide' => array( 'type' => 'select', 'show' => true, 'name' => $i.'load_type-mobile', 'data' => array('infinite_scroll') ),
								'data'			=> array(
									array(
										'text' 			=> __('Few Pages', 'dmd_is'),
										'text_2' 		=> __('Use only few pages before current', 'dmd_is'),
										'type' 			=> 'checkbox',
										'name' 			=> $i.'is_only_few_pages-mobile',
										'value' 		=> false,
									),
									array(
										'type' 			=> 'block',
										'name' 			=> $i.'is_few_pages_enable_block-mobile',
										'hide' => array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'is_only_few_pages-mobile' ),
										'data'			=> array(
											array(
												'text' 			=> __('Page count', 'dmd_is'),
												'type' 			=> 'number',
												'name' 			=> $i.'is_few_page_count-mobile',
												'value' 		=> '1',
											),
										),
									),
								),
							),
							array(
								'text_header'	=> __('Load Button Settings', 'dmd_is'),
								'type' 			=> 'block',
								'name' 			=> $i.'lb_setup_block-mobile',
								'hide' => array('type' => 'select', 'show' => true, 'name' => $i.'load_type-mobile', 'data' => array('load_button') ),
								'data'			=> array(
									array(
										'text' 			=> __('Replace products', 'dmd_is'),
										'text_2' 		=> __('Replace current products with new one', 'dmd_is'),
										'type' 			=> 'checkbox',
										'name' 			=> $i.'lb_replace_products-mobile',
										'value' 		=> false,
									),
									array(
										'type' 			=> 'block',
										'name' 			=> $i.'lb_more_pages_block-mobile',
										'hide' => array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'lb_replace_products-mobile' ),
										'data'			=> array(
											array(
												'text' 			=> __('More pages', 'dmd_is'),
												'text_2' 		=> __('Leave more then one pages', 'dmd_is'),
												'type' 			=> 'checkbox',
												'name' 			=> $i.'lb_only_few_pages-mobile',
												'value' 		=> false,
											),
											array(
												'type' 			=> 'block',
												'name' 			=> $i.'lb_few_pages_enable_block-mobile',
												'hide' => array( 'type' => 'checkbox', 'show' => true, 'name' => $i.'lb_only_few_pages-mobile' ),
												'data'			=> array(
													array(
														'text' 			=> __('Page count', 'dmd_is'),
														'type' 			=> 'number',
														'name' 			=> $i.'lb_few_page_count-mobile',
														'value' 		=> '1',
													),
												),
											),
										),
									),
								),
							),
							array(
								'text_header'	=> __('Previous page button', 'dmd_is'),
								'type' 			=> 'block',
								'name' 			=> $i.'previous_page_block-mobile',
								'hide' 			=> array('type' => 'select', 'show' => true, 'name' => $i.'load_type-mobile', 'data' => array('infinite_scroll', 'load_button') ),
								'data'			=> array(
									array(
										'text' 			=> __('Previous page button', 'dmd_is'),
										'text_2' 		=> __('Display button to load previous page, when products loaded not from first page', 'dmd_is'),
										'type' 			=> 'checkbox',
										'name' 			=> $i.'previous_page_button-mobile',
										'value' 		=> false,
									),
									array(
										'type' 			=> 'block',
										'name' 			=> $i.'previous_page_load_block-mobile',
										'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'previous_page_button-mobile' ),
										'data'			=> array(
											array(
												'text' 			=> __('Previous page load', 'dmd_is'),
												'type' 			=> 'select',
												'name' 			=> $i.'previous_load_type-mobile',
												'value' 		=> 'before',
												'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'previous_page_button-mobile' ),
												'data' 			=> array(
													'button' 		=> __('Button before products', 'dmd_is'),
													'scroll' 		=> __('Infinite scroll', 'dmd_is'),
												),
											),
											array(
												'text' 			=> __('Lazy Load animation', 'dmd_is'),
												'text_2' 		=> __('Lazy Load animation for previous products', 'dmd_is'),
												'type' 			=> 'select',
												'name' 			=> $i.'prev_lazy_load_anim-mobile',
												'value' 		=> '',
												'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'lazy_load-mobile' ),
												'data' 			=> get_dmd_animation_classes(),
											),
										),
									),
								),
							),
						),
					),
					array(
						'text' 			=> __('Threshold in px for infinite scroll', 'dmd_is'),
						'type' 			=> 'number',
						'name' 			=> $i.'infinite_scroll_threshold',
						'value' 		=> '200',
						'style'			=> 'border-top:2px solid #888888; margin-top: 1em;',
					),
					array(
						'text' 			=> __('Scroll to the top', 'dmd_is'),
						'text_2' 		=> __('Scroll to the top of the page, when products was replaced with new one', 'dmd_is'),
						'type' 			=> 'checkbox',
						'name' 			=> $i.'scroll_top',
						'value' 		=> false,
					),
					array(
						'text' 			=> __('Threshold in px for scroll top from top of products block', 'dmd_is'),
						'type' 			=> 'number',
						'name' 			=> $i.'scroll_top_threshold',
						'hide' 			=> array('type' => 'checkbox', 'show' => true, 'name' => $i.'scroll_top' ),
						'value' 		=> '-50',
					),
				),
			);
			self::$settings[] = array(
				'type' => 'general',
				'text' => __('WooCommerce', 'dmd_is'),
				'name' => 'woocommerce_block',
				'data' => array($data_tab),
			);
		}
		self::$settings[] = array(
			'type' => 'general',
			'text' => __('Styles', 'dmd_is'),
			'name' => 'style_block',
			'data' => array(
				array(
					'text_header' 	=> __('Load image', 'dmd_is'),
					'type' 			=> 'image',
					'name' 			=> 'load_image',
					'value' 		=> '',
				),
				array(
					'text_header' 	=> __('Next button style', 'dmd_is'),
					'text' 			=> __('Edit styles', 'dmd_is'),
					'text_2' 		=> __('Set to default', 'dmd_is'),
					'type' 			=> 'style',
					'name' 			=> 'dmd_next_page',
					'value' 		=> '',
					'data' 			=> array('margin', 'border', 'border-radius', 'border-color', 'padding', 'width', 'height', 'color', 'background-color'),
				),
				array(
					'text_header' 	=> __('Previous button style', 'dmd_is'),
					'text' 			=> __('Edit styles', 'dmd_is'),
					'text_2' 		=> __('Set to default', 'dmd_is'),
					'type' 			=> 'style',
					'name' 			=> 'dmd_prev_page',
					'value' 		=> '',
					'data' 			=> array('margin', 'border', 'border-radius', 'border-color', 'padding', 'width', 'height', 'color', 'background-color'),
				),
			)
		);
		self::$settings[] = array(
			'type' => 'general',
			'text' => __('JavaScript Callback', 'dmd_is'),
			'name' => 'js_callback_block',
			'data' => array(
				array(
					'text' 			=> __('JavaScript before products load', 'dmd_is'),
					'type' 			=> 'textarea',
					'name' 			=> 'js_before_load',
					'value' 		=> ''
				),
				array(
					'text' 			=> __('JavaScript after products load', 'dmd_is'),
					'type' 			=> 'textarea',
					'name' 			=> 'js_after_load',
					'value' 		=> '',
				),
			)
		);
		dmd_settings::add_settings(self::$info['slug'], self::$info['name'], self::$settings);
		register_uninstall_hook(__FILE__, array( __CLASS__, 'delete_plugin_options' ) );
		add_action( 'init', array( __CLASS__, 'init' ) );
	}

	public static function init()
	{
		add_action( "wp_ajax_save_dmd_selectors", array ( __CLASS__, 'save_dmd_selectors' ) );
		if( current_user_can( 'manage_options' ) && ! empty($_GET['dmd_is']) && $_GET['dmd_is'] == 'selectors' ) {
			wp_enqueue_script( self::$info['slug'].'-script2', plugins_url( 'assets/dmd_selectors.js', __FILE__ ), array( 'jquery' ), self::$info['version'] );

			wp_register_style( self::$info['slug'].'-style2', plugins_url( 'assets/dmd_selectors.css', __FILE__ ), "", self::$info['version'] );
			wp_enqueue_style( self::$info['slug'].'-style2' );
			$js_settings = array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			);
			wp_localize_script(
				self::$info['slug'].'-script2',
				self::$info['slug'].'_data',
				$js_settings
			);
		} else {
			$settings = dmd_settings::get_settings(self::$info['slug']);
			wp_enqueue_script( self::$info['slug'].'-script', plugins_url( 'assets/dmd_infinite_scroll.js', __FILE__ ), array( 'jquery' ), self::$info['version'] );

			wp_register_style( self::$info['slug'].'-style', plugins_url( 'assets/dmd_infinite_scroll.css', __FILE__ ), "", self::$info['version'] );
			wp_enqueue_style( self::$info['slug'].'-style' );

			wp_register_script( 'dmd-load_more-script', plugins_url( 'assets/jquery.lazyloadxt.min.js', __FILE__ ), array( 'jquery' ), self::$info['version'] );
			wp_register_style( 'dmd-animation_css-style', plugins_url( 'assets/animate.min.css', __FILE__ ), "", self::$info['version'] );

			$all_selectors = array();
			$selector_name_list = array();
			$selectors_count = 1;
			if( ! empty($settings['selectors_setting_amount']) ) {
				$selectors_count = $settings['selectors_setting_amount'];
			}
			if( $selectors_count < 1 ) {
				$selectors_count = 1;
			}
			$selectors_i = array();
			for($i = 0; $i < $selectors_count; $i++) {
				$selectors_i[] = $i;
			}
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				$selectors_i[] = 'w';
				if( ! empty($settings['wc_products_per_page']) ) {
					add_filter( 'loop_shop_per_page', array( __CLASS__, 'loop_shop_per_page' ), 90000 );
				}
			}
			foreach($selectors_i as $i) {
				$all_selector = array(
					'replaced_setup'			=> array(
						'load_type' 				=> $settings[$i.'load_type'],
					),
					'products_selector' 		=> $settings[$i.'products_selector'],
					'product_selector' 			=> $settings[$i.'product_selector'],
					'pagination_selector' 		=> $settings[$i.'pagination_selector'],
					'next_page_selector' 		=> $settings[$i.'next_page_selector'],
					'prev_page_selector' 		=> $settings[$i.'prev_page_selector'],
					'pages_selector' 			=> $settings[$i.'pages_selector'],
					'wc_result_count' 			=> @ $settings[$i.'wc_result_count'],
					'other_selectors' 			=> $settings[$i.'other_selectors'],
					'mobile_other' 				=> $settings[$i.'mobile_other'],
					'scroll_top' 				=> $settings[$i.'scroll_top'],
					'scroll_top_threshold' 		=> @ (int)$settings[$i.'scroll_top_threshold'],
					'infinite_scroll_threshold' => @ (int)$settings[$i.'infinite_scroll_threshold'],
				);
				if( $settings[$i.'lazy_load'] ) {
					wp_enqueue_script('dmd-load_more-script');
				}
				if( $settings[$i.'lazy_load_anim'] ) {
					wp_enqueue_style( 'dmd-animation_css-style' );
				}
				if($settings[$i.'load_type'] == 'infinite_scroll') {
					$all_selector['replaced_setup']['is_only_few_pages'] = $settings[$i.'is_only_few_pages'];
					$all_selector['replaced_setup']['is_few_page_count'] = ( ((int)$settings[$i.'is_few_page_count']) > 0 ? ((int)$settings[$i.'is_few_page_count']) : 1 );
					$all_selector['replaced_setup']['previous_page_button'] = $settings[$i.'previous_page_button'];
					$all_selector['replaced_setup']['previous_load_type'] = $settings[$i.'previous_load_type'];
				} elseif($settings[$i.'load_type'] == 'load_button') {
					$all_selector['replaced_setup']['lb_replace_products'] = $settings[$i.'lb_replace_products'];
					$all_selector['replaced_setup']['lb_only_few_pages'] = $settings[$i.'lb_only_few_pages'];
					$all_selector['replaced_setup']['lb_few_page_count'] = ( ((int)$settings[$i.'lb_few_page_count']) > 0 ? ((int)$settings[$i.'lb_few_page_count']) : 1 );
					$all_selector['replaced_setup']['previous_page_button'] = $settings[$i.'previous_page_button'];
					$all_selector['replaced_setup']['previous_load_type'] = $settings[$i.'previous_load_type'];
				} elseif($settings[$i.'load_type'] == 'pagination') {
					
				}
				$all_selector['replaced_setup']['lazy_load'] = $settings[$i.'lazy_load'];
				$all_selector['replaced_setup']['lazy_load_anim'] = $settings[$i.'lazy_load_anim'];
				$all_selector['replaced_setup']['prev_lazy_load_anim'] = $settings[$i.'prev_lazy_load_anim'];
				$all_selector['replaced_setup']['hide_lazy_load_anim'] = $settings[$i.'hide_lazy_load_anim'];
				if( @$settings[$i.'mobile_other'] ) {
					$mobile = array(
						'load_type' 			=> $settings[$i.'load_type-mobile'],
						'mobile_maximum_width' 	=> $settings[$i.'mobile_maximum_width'],
					);
					if($settings[$i.'load_type-mobile'] == 'infinite_scroll') {
						$mobile['is_only_few_pages'] = $settings[$i.'is_only_few_pages-mobile'];
						$mobile['is_few_page_count'] = ( ((int)$settings[$i.'is_few_page_count-mobile']) > 0 ? ((int)$settings[$i.'is_few_page_count-mobile']) : 1 );
						$mobile['previous_page_button'] = $settings[$i.'previous_page_button-mobile'];
						$mobile['previous_load_type'] = $settings[$i.'previous_load_type-mobile'];
					} elseif($settings[$i.'load_type-mobile'] == 'load_button') {
						$mobile['lb_replace_products'] = $settings[$i.'lb_replace_products-mobile'];
						$mobile['lb_only_few_pages'] = $settings[$i.'lb_only_few_pages-mobile'];
						$mobile['lb_few_page_count'] = ( ((int)$settings[$i.'lb_few_page_count-mobile']) > 0 ? ((int)$settings[$i.'lb_few_page_count-mobile']) : 1 );
						$mobile['previous_page_button'] = $settings[$i.'previous_page_button-mobile'];
						$mobile['previous_load_type'] = $settings[$i.'previous_load_type-mobile'];
					} elseif($settings[$i.'load_type-mobile'] == 'pagination') {
						
					}
					$mobile['lazy_load'] = $settings[$i.'lazy_load-mobile'];
					$mobile['lazy_load_anim'] = $settings[$i.'lazy_load_anim-mobile'];
					$mobile['prev_lazy_load_anim'] = $settings[$i.'prev_lazy_load_anim-mobile'];
					$mobile['hide_lazy_load_anim'] = $settings[$i.'hide_lazy_load_anim-mobile'];
					$all_selector['mobile'] = $mobile;
				}
				$all_selectors['selector'.$i] = $all_selector;
				$selector_name_list[] = 'selector'.$i;
			}
			$current_selectors = $all_selectors['selector0'];
			$js_settings = array(
				'current'					=> $current_selectors,
				'selectors'					=> $all_selectors,
				'selectors_names'			=> $selector_name_list,
				'load_image' 				=> ($settings['load_image'] ? $settings['load_image'] : plugin_dir_url( __FILE__ ) . "assets/load.gif"),
				'js_before_load'			=> @ $settings['js_before_load'],
				'js_after_load'				=> @ $settings['js_after_load'],
			);
			wp_localize_script(
				self::$info['slug'].'-script',
				self::$info['slug'].'_data',
				$js_settings
			);
			add_action( 'wp_head', array( __CLASS__, 'wp_head' ) );
			add_action( 'woocommerce_before_shop_loop', array( __CLASS__, 'woocommerce_before_shop_loop' ) );
		}
	}

	public static function wp_head()
	{
		$settings = dmd_settings::get_settings(self::$info['slug']);
		echo '<style>';
		echo '.dmd_next_page a{
			display: inline-block!important;
			border: initial!important;
			border-style: solid!important;';
		echo dmd_settings::convert_settings_to_style($settings['dmd_next_page']);
		echo '}';
		echo '.dmd_prev_page a{
			display: inline-block!important;
			border: initial!important;
			border-style: solid!important;';
		echo dmd_settings::convert_settings_to_style($settings['dmd_prev_page']);
		echo '}';
		echo '</style>';
	}

	public static function woocommerce_before_shop_loop()
	{
		global $wp_query;
		$paged    = max( 1, $wp_query->get( 'paged' ) );
		$per_page = $wp_query->get( 'posts_per_page' );
		$total    = $wp_query->found_posts;
		$first    = ( $per_page * $paged ) - $per_page + 1;
		$last     = min( $total, $wp_query->get( 'posts_per_page' ) * $paged );
		echo '<span class="dmd_product_result_count" style="display: none;" data-text="';
		if ( $total <= $per_page || -1 === $per_page ) {
			/* translators: %d: total results */
			printf( _n( 'Showing the single result', 'Showing all %d results', $total, 'woocommerce' ), $total );
		} else {
			/* translators: 1: first result 2: last result 3: total results */
			printf( _nx( 'Showing the single result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'woocommerce' ), -1, -2, $total );
		}
		echo '" data-first="', $first, '" data-last="', $last, '"></span>';
	}

	public static function loop_shop_per_page()
	{
		$settings = dmd_settings::get_settings(self::$info['slug']);
		return $settings['wc_products_per_page'];
	}

	public static function delete_plugin_options()
	{
		dmd_settings::remove_plugin_setting(self::$info['slug']);
	}
	public static function save_dmd_selectors() {
		if( current_user_can( 'manage_options' ) ) {
			$products = @$_POST['products'];
			$product = @$_POST['product'];
			$pagination = @$_POST['pagination'];
			$next = @$_POST['next'];
			$next = $pagination.' '.$next;
			$prev = @$_POST['prev'];
			$prev = $pagination.' '.$prev;
			$pages = @$_POST['pages'];
			$pages = $pagination.' '.$pages;
			dmd_settings::update_setting(self::$info['slug'], '0products_selector', $products);
			dmd_settings::update_setting(self::$info['slug'], '0product_selector', $product);
			dmd_settings::update_setting(self::$info['slug'], '0pagination_selector', $pagination);
			dmd_settings::update_setting(self::$info['slug'], '0next_page_selector', $next);
			dmd_settings::update_setting(self::$info['slug'], '0prev_page_selector', $prev);
			dmd_settings::update_setting(self::$info['slug'], '0pages_selector', $pages);
			echo admin_url( 'admin.php?page='.self::$info['slug'] );
			wp_die();
		}
	}
}

new DMD_infinite_scroll;