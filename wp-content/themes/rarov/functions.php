<?php
/**
 * Portland functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Portland
 */

/*update_option( 'siteurl', 'http://rarov.zzz.com.ua/' );
update_option( 'home', 'http://rarov.zzz.com.ua/' );*/

if ( ! function_exists( 'portland_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function portland_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Portland, use a find and replace
	 * to change 'portland' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'portland', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size('shop-thumb',240,240,true);

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Главное меню', 'portland' ),
		'sidebar' => esc_html__( 'Сайдбар меню', 'portland' )
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'portland_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'portland_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function portland_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'portland_content_width', 640 );
}
add_action( 'after_setup_theme', 'portland_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function portland_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'portland' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'portland' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 style="display:none">', //<h2 class="widget-title">
		'after_title'   => '</h2>', //
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar-search', 'portland' ),
		'id'            => 'sidebar-2',
		'description'   => esc_html__( 'Перетащите сюда виджеты, чтобы добавить их в сайдбар.', 'portland' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s mid-menu default">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 style="display:none">', //<h2 class="widget-title">
		'after_title'   => '</h2>', //
	) );
}
add_action( 'widgets_init', 'portland_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function portland_scripts() {
	wp_enqueue_style( 'portland-style', get_stylesheet_uri() );
	wp_enqueue_style( 'bts-cs', get_template_directory_uri() . '/css/bootstrap.min.css');
	/*wp_enqueue_style( 'fantawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');*/
	wp_enqueue_style( 'style', get_template_directory_uri() . '/css/style.css');
	wp_enqueue_style( 'slick', 'http://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css' );

	wp_enqueue_script( 'portland-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'portland-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );
	wp_enqueue_script('jQuery');

	/*wp_enqueue_script('ajax-click', get_stylesheet_directory_uri(). '/js/main-ajax.js',array('jquery'),'1.0',true);
	global $wp_query;
	wp_localize_script('ajax-click','ajaxglobal',array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'templateUrl' => get_bloginfo('url'),
		'query_vars' => json_encode( $wp_query->query )
		));*/

	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js');
	wp_enqueue_script( 'fantawesome-js', 'https://use.fontawesome.com/0a2d6dd2d3.js' );
	wp_enqueue_script( 'global-js', get_template_directory_uri() . '/js/global.js');
	wp_enqueue_script( 'slick-js', 'http://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'portland_scripts' );

function get_cart_totals(){

	return WC()->cart->get_cart_contents_count();
}

function woocommerce_template_loop_product_thumbnail() {
 echo woocommerce_get_product_thumbnail('shop-thumb');
}

function woocommerce_template_loop_product_title() {
	echo '<a href="'.get_permalink().'"><h3>' . get_the_title() . '</h3></a>';
}

add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args' );
function custom_woocommerce_get_catalog_ordering_args( $args ) {
  $orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	if ( 'random_list' == $orderby_value ) {
		$args['orderby'] = 'rand';
		$args['order'] = '';
		$args['meta_key'] = '';
	}
	return $args;
}
add_filter( 'woocommerce_default_catalog_orderby_options', 'custom_woocommerce_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby' );
function custom_woocommerce_catalog_orderby( $sortby ) {
	$sortby['random_list'] = 'Рандом';
	return $sortby;
}


if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}

add_filter( 'woocommerce_product_tabs', 'sb_woo_remove_reviews_tab', 98);
function sb_woo_remove_reviews_tab($tabs) {
 
unset($tabs['reviews']);
 
return $tabs;
}

/*add_action('wp_ajax_nopriv_ajax_cat','my_ajax_cat');
add_action('wp_ajax_ajax_cat','my_ajax_cat');

function my_ajax_cat() {
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') :
		$id = $_POST['cat_id'];
	if ( get_query_var('paged') ) {

		$paged = get_query_var('paged');

		} elseif ( get_query_var('page') ) {

		$paged = get_query_var('page');

		} else {

		   $paged = 1;

		}
				$query = new WP_Query( array(
						'tax_query' => array(
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'id',
								'terms'    => $id
							)
						),
						'post_type' => 'product',
						'posts_per_page'=>6,
						'paged'=>$paged,
						'order_by' => 'rand'
					)
			);
		if($query->have_posts()):
		while($query->have_posts()):
			$query->the_post();
	
			echo '<div class="col-md-4">';
			echo '<div class="device text-center b-color">';
			echo '<a href="'.get_permalink().'" class="portfolio_item_link">'.do_action( 'woocommerce_before_shop_loop_item_title' ).'</a>';
			echo '<h5>'.do_action( 'woocommerce_shop_loop_item_title' ).'</h5>';
			echo '<p class="color">'.$query->get_tags().'</p>';
			echo '<p class="d_price">'.remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating');do_action( 'woocommerce_after_shop_loop_item_title' ).'</p>';
			echo  '<a href="'.get_permalink().'" class="btn btn-blue">Подробнее</a>';
			echo 		'</div>';
			echo 	'</div>';
				endwhile;
			endif;
			wp_pagenavi( array( 'query' => $query ) );
    exit();
	endif;
}*/

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

