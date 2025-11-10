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
$api = new yatcoConnect_ApiToBoss();

$template = new yatcoConnect_Yachts_Template(); 
$template_manager = new yatcoConnect_TemplateManager();

if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $options->getOption('charter_search_url')) === false) {
	$back_to_search = $options->getOption('charter_search_url');
} 
elseif (! isset($_SERVER['HTTP_REFERER'])) {
	$back_to_search = $options->getOption('charter_search_url');
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

				//echo "<pre>"; print_r($data);exit;

				$yt_single_template_forCharter = get_option('yt_single_template_forCharter');

				$template_manager->process_template_data( $yt_single_template_forCharter, $data, 'single', 'charter' );

				$template_manager->display_template_styles($yt_single_template_forCharter);
			}
		}

	?>

</main><!-- #site-content -->

<?php get_footer();
