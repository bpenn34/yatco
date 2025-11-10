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
				
				?>
				<div class="yt-container">
					<div id="top-links">
						<ul class="breadcrumb yt-pull-left">
							<li class="breadcrumb-item">
								<a href="<?= get_site_url(); ?>">Home</a>
							</li>

							<li class="breadcrumb-item">
								<a href="<?= $back_to_search; ?>">Yacht Search</a>
							</li>

							<li class="breadcrumb-item">
								<span>
									<?php 
										echo sprintf('%s %s %s %s %s Yacht MLS #%s',
											$data->BasicInfo->BoatName,
											$data->BasicInfo->YearBuilt,
											$data->BasicInfo->Builder,
											$data->BasicInfo->Model,
											$data->BasicInfo->MainCategory,
											$data->MiscInfo->MLSID	
										);
									?>
								</span>
							</li>
						</ul>

						<a href="<?= $back_to_search ?>" class="yt-pull-right">
							&lt; Back to search
						</a>

						<div class="yt-clearfix"></div>
					</div>
				</div>
				
				<!-- Top Slider Gallery -->				
				<div id="yt-light-slider-gallery" class="">
					<?php 
						$count = intval(8);
						$gallery = '';

						if (is_array($data->PhotoGallery)) {

							foreach($data->PhotoGallery as $img) {
								$image_url=htmlentities($img->largeImageURL);
								$medium_image_url=htmlentities($img->mediumImageURL);


								echo '<div class="carousel-cell">';
									echo sprintf('<img data-flickity-lazyload-srcset="%s 650w, %s 320w" sizes="(min-width: 650px) 650px, 320px" data-flickity-lazyload-src="%s" alt="%s" class="pic" />',
										$image_url,
										$medium_image_url,
										( wp_is_mobile() )?$medium_image_url:$image_url,
										htmlentities($img->Caption)
									);
								echo '</div>';
								
								$count--;
								
								if ($count === 0) {
									break;
								}
							}
						}
					?>
				</div>

				<div id="single-yacht-top-nav" class="yt-hidden-sm yt-hidden-md">
					<div class="yt-container">
						<div class="yt-row">
							<div class="yt-col-12 nav-links" style="padding-right: 32px;">
								<a href="#overview">Overview</a>
								<a href="#features">Features</a>
								<a href="#details">Details</a>
								<a href="#gallery">Gallery</a>

								<span style="cursor: pointer;" data-modal="#single-yacht-modal-share">
									<!-- Share -->
									<i class="icon-share-here yt-share-icon"></i>
								</span>
							</div>
						</div>
					</div>
				</div>

				<div class="ycd-mini-specs-container yt-hidden-md yt-hidden-sm" id="single-yacht-quick-specs">
					<div class="ycd-mini-specs">
						<div class="ycd-specs-values">
							<?= $data->BasicInfo->YearBuilt ?>
						</div>
						Year
					</div>

					<div class="ycd-mini-specs yt-hidden-md yt-hidden-sm">
						<div class="ycd-specs-values">
							<?= $data->BasicInfo->Builder ?>
						</div>
						Builder
					</div>

					<div class="ycd-mini-specs">
						<div class="ycd-specs-values">
							<?php if ($options->is_euro_site) : ?>
								<?= $data->Dimensions->LOAMeter ?>

							<?php else : ?>
								<?php if (isset($data->Dimensions->LOA) && !empty($data->Dimensions->LOA)): ?>
									<?= $data->Dimensions->LOA ?>
								<?php else : ?>
									N/A
								<?php endif; ?>
							<?php endif; ?>
						</div>
						Length
					</div>

					<div class="ycd-mini-specs yt-hidden-md yt-hidden-sm">
						<div class="ycd-specs-values">
							<?= $data->Accommodations->StateRooms ?>
						</div>
						Cabins
					</div>
				</div>

				<?php /* <div class="container-fluid">

					<div class="row padding-xs text-center" id="specs-with-icons" style="padding-top: 15px; padding-bottom: 15px;">
						<div class="col-xs-4">
							<img src="<?= YATCO_PLUGIN_ASSETS.'/img/length icon.png' ?>" alt="Length Icon" style="margin: auto;">
							<strong>
								<span gpref-unit="us"><?= $data->Dimensions->LOAFeet ?></span>
								<span gpref-unit="m"><?= $data->Dimensions->LOAMeter ?></span>
							</strong>
						</div>
						<div class="col-xs-4">
							<img src="<?= YATCO_PLUGIN_ASSETS.'/img/cabins icon.png' ?>" alt="Cabin Icon" style="margin: auto;">
							<strong><?= $data->Accommodations->StateRooms ?></strong>
							CABINS
						</div>
						<div class="col-xs-4">
							<img src="<?= YATCO_PLUGIN_ASSETS.'/img/guests icon.png' ?>" alt="Guests Icon" style="margin: auto;">
							<strong><?= $data->Accommodations->Sleeps ?></strong>
							GUESTS
						</div>
					</div>
				</div> */ ?>
				
				<div id="overview"></div>

				<div class="yt-container" id="single-yacht-about-the-boat" style="background-color: transparent !important;">
					<div class="yt-row">
						<div class="yt-col-lg-8 yt-col-12 yachtdetail_left">
							<div class="inner">
								<?php include 'single-yacht-parts/for-brokerage/yachtdetails-left.php'; ?>

								<div class="yt-text-right">
									<?php 
										echo do_shortcode('[yatco-powered-by]');
									?>
								</div>
							</div>
						</div>

						<div class="yt-col-lg-4 yt-hidden-sm yt-hidden-md yachtdetail_right">						
							<div class="inner">
								<?php include 'single-yacht-parts/for-brokerage/yachtdetails-right.php'; ?>
							</div>
						</div>
					</div>
				</div>

				<?php 
					include 'single-yacht-parts/for-brokerage/yachtdetails-bottom.php'; 
				?>

				<!-- Modal: Share -->
				<div id="single-yacht-modal-share" class="yt-modal single-yacht-modal">
					<h4 style="font-size: 30px !important; font-family: initial;">Share With Your Friends</h4>

					<h5 style="font-size: 20px; margin-top: 0px;"><?= $data->BasicInfo->Builder.' '.$data->BasicInfo->ModelYear; ?> </h5>
					
					<?php include 'sharing.php'; ?>
				</div>
				
				<?php 
			}
		}

	?>

</main><!-- #site-content -->

<?php get_footer();
