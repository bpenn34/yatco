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

				//var_dump($data);

				$charter_tenders = $data->charter_tenders;

				$charter_toys = $data->charter_toys;

				$charter_amenities = $data->charter_amenities;

				?>

				<!-- <div class="yt-pre-container"> -->
					<div class="yt-container">
						<div id="top-links">
							<ul class="breadcrumb yt-pull-left">
								<li class="breadcrumb-item">
									<a href="<?= get_site_url(); ?>">Home</a>
								</li>

								<li class="breadcrumb-item">
									<a href="<?= $back_to_search ?>">Charter Search</a>
								</li>

								<li class="breadcrumb-item">
									<a href="">
										<?= $data->VesselName ?>
									</a>
								</li>
							</ul>

							<a href="" class="yt-pull-right">
								&lt; Back to search
							</a>

							<div class="yt-clearfix"></div>
						</div>
					</div>
				<!-- </div> -->
				
				<!-- Top Slider Gallery -->				
				<div id="yt-light-slider-gallery" class="">
					<?php 
						$count = intval(8);
						$gallery = '';

						if (is_array($data->Photos) && count($data->Photos) > 0) {

							foreach($data->Photos as $img) {
								$image_url=htmlentities($img->largeImageURL);
								$medium_image_url=htmlentities($img->mediumImageURL);

								echo '<div class="carousel-cell">';
									echo sprintf('<img data-flickity-lazyload-srcset="%s 650w, %s 320w" sizes="(min-width: 650px) 650px, 320px" data-flickity-lazyload-src="%s" alt="%s" class="pic" />',
										$image_url,
										$medium_image_url,
										( wp_is_mobile() )?$medium_image_url:$image_url,
										htmlentities($img->Caption)
									);
									
									/*echo sprintf('<img data-flickity-lazyload-src="%s" alt="%s" class="pic" />',
										$image_url,
										htmlentities($img->Caption)
									);*/
								echo '</div>';
								
								$count--;
								
								if ($count === 0) {
									break;
								}
							}
						}
						else {
							echo '<div class="carousel-cell">';
								echo sprintf('<img data-flickity-lazyload-srcset="%s 650w, %s 320w" sizes="(min-width: 650px) 650px, 320px" data-flickity-lazyload-src="%s" alt="%s" class="pic" />',
									$data->imageURL,
									$data->imageURL,
									$data->imageURL,
									htmlentities($data->VesselName)
								);
								
								/*echo sprintf('<img data-flickity-lazyload-src="%s" alt="%s" class="pic" />',
									$image_url,
									htmlentities($img->Caption)
								);*/
							echo '</div>';

						}
					?>
				</div>

				<!-- HELLO FROM PLUGIN -->

				<?php if ($options->is_brokerage_site) : ?>
					<div id="single-yacht-top-nav" class="yt-hidden-sm yt-hidden-md yt-pre-container">
						<div class="yt-container">
							<div class="yt-row">
								<div class="yt-col-12 nav-links" style="justify-content: space-between;">
									<a href="#overview">Overview</a>
									<a href="#features">Features</a>
									<a href="#gallery">Gallery</a>
									<a href="#single-yacht-modal-share" data-modal="#single-yacht-modal-share">Share</a>
								</div>
							</div>
						</div>
					</div>
				<?php else : ?>
					<div id="single-yacht-top-nav" class="yt-hidden-sm yt-hidden-md yt-pre-container">
						<div class="yt-container">
							<div class="yt-row">
								<div class="yt-col-lg-8 nav-links" style="justify-content: space-between;">
									<a href="#overview">Overview</a>
									<a href="#features">Features</a>
									<a href="#gallery">Gallery</a>
									<a href="#single-yacht-modal-share" data-modal="#single-yacht-modal-share">Share</a>
								</div>

								<div class="yt-col-lg-4 nav-btns">
									<div>
										<!-- <a href="#" onclick="OpenShareModal();" class="yt-btn yt-btn-alt-white">SHARE</a> -->
										
										<a data-modal="#single-charter-modal-call-0" class="yt-btn">Call Broker</a>

										<a data-modal="#single-charter-modal-email-0" class="yt-btn">Email Broker</a>
									
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>


				<div class="ycd-mini-specs-container" id="single-yacht-quick-specs" style="display: block;">
					<div class="yt-pre-container">
						<div class="yt-container">
							<div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
								<div class="ycd-mini-specs yt-hidden-md yt-hidden-sm" style="margin: 0 20px;">
									<div class="ycd-specs-values">
										<?php if (isset($data->NumberOfGuests) && !empty($data->NumberOfGuests)) : ?>
											<?= $data->NumberOfGuests ?>
										<?php else : ?>
											N/A
										<?php endif; ?>
									</div>
									Cruising Guests
								</div>

								<div class="ycd-mini-specs" style="margin: 0 20px;">
									<div class="ycd-specs-values">
										<?php if (isset($data->TotalStateRooms) && !empty($data->TotalStateRooms)) : ?>
											<?= $data->TotalStateRooms ?>
										<?php else : ?>
											N/A
										<?php endif; ?>
									</div>
									Staterooms
								</div>

								<div class="ycd-mini-specs yt-hidden-md yt-hidden-sm" style="margin: 0 20px;">
									<div class="ycd-specs-values">
										<?php if ($options->is_euro_site) : ?>
											<?= $data->LOAMeters.'m' ?>

										<?php else : ?>
											<?php if (isset($data->LOA_Format) && !empty($data->LOA_Format)): ?>
												<?= $data->LOA_Format ?>
											<?php else : ?>
												N/A
											<?php endif; ?>
										<?php endif; ?>
									</div>

									Length				
								</div>
								<?php
								if(isset($data->RatesList) && !empty($data->RatesList)){
								?>
								<div class="ycd-mini-specs "  style="margin: 0 20px;">
									<div class="ycd-specs-values">
										<?php if ($options->is_euro_site) : ?>
											<?php if(isset($data->RatesList[0]->LowRateFormatEuro)){ ?>
												<?= $data->RatesList[0]->LowRateFormatEuro ?>
											<?php } ?>
										<?php else: ?>
											<?php if(isset($data->RatesList[0]->LowRateFormat)){ ?>
												<?= $data->RatesList[0]->LowRateFormat ?>
											<?php } ?>
										<?php endif; ?>
									</div>

									Starting At 
									<?php if(isset($data->RatesList[0]->Frequency)){ ?>
										<?= $data->RatesList[0]->Frequency ?>
									<?php } ?>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

				<div id="overview"></div>

				<div id="single-yacht-about-the-boat">
					<div class="yt-container" style="background-color: transparent !important;">
						<div class="yt-row">
							<div class="yt-col-lg-8 yt-col-12 yachtdetail_left">
								<div class="inner">
									<?php include __DIR__.'/charter/single-parts/left.php'; ?>
								</div>
							</div>

							<div class="yt-col-lg-4 yt-hidden-sm yt-hidden-md yachtdetail_right">						
								<div class="inner">
									<?php include __DIR__.'/charter/single-parts/right.php'; ?>								
								</div>
							</div>
						</div>
					</div>
				</div>

				<script type="text/javascript">
					window.addEventListener('load', function() {
						if (typeof _YCT != 'undefined') {
							_YCT.charter.view("<?= $data->VesselID ?>");
						}
					});
				</script>

				<?php 

				include __DIR__.'/charter/single-parts/bottom.php';		
					
				include __DIR__.'/charter/single-parts/modals.php';			
			}
		}

	?>

</main><!-- #site-content -->

<?php get_footer();
