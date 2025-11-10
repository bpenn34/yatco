<?php
/**
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

get_header();

$options = new yatcoConnect_Options();
$template_manager = new yatcoConnect_TemplateManager();

if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $options->getOption('yacht_search_url')) === false) {
	$back_to_search = $options->getOption('yacht_search_url');
} 
elseif (! isset($_SERVER['HTTP_REFERER'])) {
	$back_to_search = $options->getOption('yacht_search_url');
}
else {
	$back_to_search = $_SERVER['HTTP_REFERER'];
}
?>

<main id="site-content" role="main" class="vessel-details-style-container">

	<?php

		if ( have_posts() ) {

			while ( have_posts() ) {
				the_post();
				$data=$post->boss_data;
				$yt_single_template = get_option('yt_single_template');
				$template_manager->display_template_content($yt_single_template, $data, 'single');
				$template_manager->display_template_styles($yt_single_template);
				?>
				<!--
				<div class="container">
					<div class="yt-text-right">
						<?php 
							echo do_shortcode('[yatco-powered-by]');
						?>
					</div>
				</div>
				-->
				<?php 
			}
		}

	?>

</main><!-- #site-content -->

<?php

	include plugin_dir_path(__FILE__) . 'yacht-result-parts/modal-form-for-brokerage-site.php';

	/// include apply_filters('yatco_connect_shortcode_yacht_results_lead_modal_template', $file_to_include);
?>

<?php get_footer();