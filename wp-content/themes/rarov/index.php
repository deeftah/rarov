<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Portland
 */

get_header(); 
/*$paged = get_query_var( 'paged', 1 );*/
if ( get_query_var('paged') ) {

$paged = get_query_var('paged');

} elseif ( get_query_var('page') ) {

$paged = get_query_var('page');

} else {

   $paged = 1;

}

$args = array(
					'post_type' => 'product',
					'posts_per_page'=>'2',
					'paged'=>$paged,
					'order_by' => 'rand'
					/*'meta_value'=> array(
						'_featured'=>'yes')*/
				);

$products = new WP_Query($args);
?>
	<div class="row">
		<div class="col-md-3">
			<?php get_sidebar(); ?>
		</div>
		<div class="col-md-9">
			<div class="row">
					<?php if($products->have_posts()):
							while($products->have_posts()):
								$products->the_post(); ?>
					<div class="col-md-4">
							<div class="device text-center b-color">
								<a href="<?php the_permalink();?>"><?php 
									do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
								</a>
								<h5><?php do_action( 'woocommerce_shop_loop_item_title' ); ?></h5>
								<p class="color"><?php echo $product->get_tags(); ?></p>
								<p class="d_price"><?php 
								remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating');
								do_action( 'woocommerce_after_shop_loop_item_title' ); ?></p>
								<a href="<?php the_permalink();?>" class="btn btn-blue">Подробнее</a>
							</div>
					</div>
				<?php endwhile;
				endif; ?>
				<?php wp_pagenavi( array( 'query' => $products ) ); ?>
			</div>
		</div>
	</div>
	


<?php
get_footer();
