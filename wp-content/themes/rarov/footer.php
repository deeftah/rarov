<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Portland
 */

?>
	
	</div><!-- #content -->
	</section>

	<footer>
		<div class="container">
			<div class="bot-menu">
				<a href="<?=bloginfo('url')?>"><img src="<?=get_template_directory_uri()?>/images/Vitali_Rarov_white.png" alt=""></a>
				<div class="text-center ul-menu">
					<nav class="text-center">
								<?php wp_nav_menu(['menu'=>'primary','menu_class'=>'list-inline list-unstyled']);?>
							</nav>
				</div>
				<div class="links">
				<?php 
					if (have_rows('socials','options')):
						while (have_rows('socials','options')):
							the_row();
							echo "<a href='".get_sub_field('link')."'><i class='fa ".get_sub_field('fa')."'></i></a>";
						endwhile;
					endif;
				?>
				</div>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
