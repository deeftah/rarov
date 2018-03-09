<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Portland
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon.ico" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'portland' ); ?></a>

	<header>
		<div class="main-menu">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
							<div class="menu">
								<a href="<?=bloginfo('url')?>"><img src="<?=get_template_directory_uri()?>/images/Vitali_Rarov.png" alt=""></a>
						</div>
					</div>
				</div>
				<!-- <a href="<?=bloginfo('url').'/cart'?>">
				<div class="shop-menu pull-right">
					<i class="fa fa-shopping-cart"></i>
					<p class="cart"><span><?php echo get_cart_totals(); ?></span></p>
					<a href="<?php echo wp_registration_url(); ?>" class="btn btn-sign" role="button">РЕЕСТРАЦИЯ</a>
					<a href="<?php echo wp_login_url( $redirect ); ?>" class="btn btn-sign btn-sign1" role="button">ВХОД</a>
					
				</div>
				</a> -->
				<div class="row">
				<div class="col-md-12 text-center">
						<div class="nav-menu">
							<nav class="text-center">
								<?php wp_nav_menu(['menu'=>'primary','menu_class'=>'list-inline list-unstyled']);?>
							</nav>
					</div>
				</div>
				</div>
			</div>
		</div>	
		<?php if(is_shop() || is_home() || is_front_page()):
			$args = array(
				'post_type'=>'product',
				'posts_per_page'=>5,
				'order_by'=>'rand',
				/*'order'=>'desc',*/
				'meta_value'=>array(
						'_featured'=>'yes'
					)
			);
			$featured_products = new WP_Query($args); ?>
		<div class="black-block">	
			<div class="container" style="position:relative">
				<div class="row slider">
				<?php 
					if($featured_products->have_posts()):
						while($featured_products->have_posts()):
							$featured_products->the_post();
				 ?>
				<div>
						<div class="col-md-5 col-md-offset-1 col-sm-12 col-xs-5">
							<h1><?php the_title(); ?></h1>
							<p><?php echo $GLOBALS['product']->get_tags(); ?></p>
							<!-- <p><?php echo strip_tags(get_the_excerpt()); ?></p> -->
							<p class="link"><a href="<?php the_permalink();?>" class="btn btn-blue">Подробнее</a></p>
						</div>
						<div class="col-md-6 col-sm-12 col-xs-12">
						<?php the_post_thumbnail(); ?>						
						</div>
				</div>
				<?php 
						endwhile;
					endif;
				 ?>
			</div>
			<a href="#" class="next-block arrows">
					<img src="<?php echo get_template_directory_uri()?>/images/Nextphone.png" alt="">
				</a>
				<a href="#" class="prev-block arrows">
					<img src="<?php echo get_template_directory_uri()?>/images/Prevphone.png" alt="">
				</a>
			</div>
		</div>
	<?php endif; ?>
 	</header>
	
	<section class="shop-menu">
	<div id="content" class="site-content container">
