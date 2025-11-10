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

<main id="site-content" role="main">

	<?php

		if ( have_posts() ) {

			while ( have_posts() ) {
				the_post();
				$data=$post->boss_data;

				//var_dump($data);
				
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
								<a href="">
									<?php 
										echo sprintf('%s %s %s %s %s Yacht MLS #%s',
											$data->BasicInfo->BoatName,
											$data->BasicInfo->ModelYear,
											$data->BasicInfo->Builder,
											$data->BasicInfo->Model,
											$data->BasicInfo->MainCategory,
											$data->MiscInfo->MLSID	
										);
									?>
								</a>
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
							<div class="yt-col-12 nav-links">
								<a href="#overview">Overview</a>
								<a href="#features">Features</a>
								<a href="#details">Details</a>
								<a href="#gallery">Gallery</a>
								<a href="#single-yacht-modal-share" data-modal="#single-yacht-modal-share">Share</a>
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
						<?php if (isset($data->Dimensions->LOA) && !empty($data->Dimensions->LOA)): ?>
							<div class="ycd-specs-values">
								<?= $data->Dimensions->LOA ?>
							</div>
							Length
						<?php else : ?>
							N/A
						<?php endif; ?>
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
								<div id="single-yacht-content">
									<div class="ycd-top-details">
										<h1 class="ycd-title" style="line-height: 1.25;">
											<?php 
												$seo_heading = '<div class="mobile-only">'. $data->BasicInfo->BoatName .' - <span class="mobile-hide">'. $data->BasicInfo->YearBuilt .' '. $data->Dimensions->LOAFeet .' '. $data->BasicInfo->Builder .' '. $data->BasicInfo->Model .' <br>'. $data->BasicInfo->City . ' ' . $data->BasicInfo->State .' '. $data->BasicInfo->Country .'</span></div> '. $data->BasicInfo->AskingPriceFormatted;

												echo apply_filters('yatco_seo_heading_yacht', $seo_heading);
											?>	
										</h1>
									</div>

									<hr>

									<div id="single-yach-read-more">
										<div id='overview'></div>

										<div id="ycd-description" class="ag-readmore">
											<?php 
												$seo_address = apply_filters('yatco_full_address', $data->BasicInfo, false, true);

												$seo_sentence = sprintf('Yacht for sale is a %s %s %s "%s" %s %s in %s. ',
													$data->BasicInfo->ModelYear,
													$data->BasicInfo->Builder,
													$data->Dimensions->LOAFeet,
													$data->BasicInfo->BoatName,
													$data->BasicInfo->Model,
													$data->BasicInfo->MainCategory,
													$seo_address
												);

												$seo_second_sentence = '';

												$vessel_short_description = $data->VD->VesselDescriptionShortDescriptionNoStyles;

												$vessel_teaser = $data->VD->VesselDescriptionBrokerTeaser;

												if ($vessel_teaser == $vessel_short_description || $vessel_teaser == 'N/A') {

													$vessel_teaser = '';

												}

												$seo_first_sentence = apply_filters('yatco_seo_sentence_yacht', $seo_sentence);

												$seo_second_sentence = apply_filters('yatco_seo_second_sentence_yacht', $seo_second_sentence);

												$seo_last_sentence = apply_filters('yatco_seo_last_sentence_yacht', '');

												if (!empty($seo_first_sentence)) {
													echo $seo_first_sentence;
												}

												if (!empty($seo_second_sentence)) {
													echo $seo_second_sentence;
												}

												echo '<br><br>';

												if (!empty($vessel_teaser)) {
													echo $vessel_teaser.'<br><br>';
												}

												echo $vessel_short_description;

												echo '<br><br>';

												echo $seo_last_sentence;
											?>
										</div>
									</div>

									<hr>

									<div class="mobile-broker yt-hidden-lg">
										
										<?php include __DIR__.'/../yachtdetails-broker-form.php'; ?>

									</div>

									<div id="yc-details-specs">
										<div id="features"></div>

										<div class="yt-row">
											<?php $class_detail_spec_col="yt-col-6 yt-col-md-3"; ?>
											
											<div class="<?= $class_detail_spec_col ?>">
												<?=
													apply_filters('yatco_single_yacht_format_key_spec_table', 
														'LENGTH',
														($data->is_eur)?$data->Dimensions->LOAMeter:$data->Dimensions->LOAFeet,
														''
													); 
												?>
											</div>
											<div class="<?= $class_detail_spec_col ?>">
												<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'BUILDER', $data->BasicInfo->Builder); ?>
											</div>
											<div class="<?= $class_detail_spec_col ?>">
												<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'ASKING PRICE', $data->BasicInfo->AskingPriceFormatted); ?>
											</div>
											<div class="<?= $class_detail_spec_col ?>">
												<?php
													$label_built_year = 'BUILT'.((isset($data->BasicInfo->RefitYear) && ! empty($data->BasicInfo->RefitYear))?' (REFIT)':'');

													echo apply_filters('yatco_single_yacht_format_key_spec_table', $label_built_year, $data->BasicInfo->YearBuilt, $data->BasicInfo->RefitYear); 
												?>
											</div>
										</div>
										<div class="yt-row">
											<div class="<?= $class_detail_spec_col ?>">
												<?php 
													echo apply_filters(
														'yatco_single_yacht_format_key_spec_table', 
														'BEAM', 
														($data->is_eur)?$data->Dimensions->BeamMeter:$data->Dimensions->BeamFeet,
														''
													); 
												?>
											</div>
											<div class="<?= $class_detail_spec_col ?>">
												<?php 
													echo apply_filters(
														'yatco_single_yacht_format_key_spec_table', 
														'DRAFT', 
														($data->is_eur)?$data->Dimensions->DraftMeter:$data->Dimensions->DraftFeet, 
														''
													); 
												?>
											</div>
											<div class="<?= $class_detail_spec_col ?>">
												<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'MX SPEED', $data->SpeedWeight->MaxSpeed, ''); ?>
											</div>
											<div class="<?= $class_detail_spec_col ?>">
												<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'CRUISING SPEED', $data->SpeedWeight->CruiseSpeed, ''); ?>
											</div>
										</div>
										<div class="yt-row">
											<div class="<?= $class_detail_spec_col ?>">
												<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'CABINS', $data->Accommodations->StateRooms, ''); ?>
											</div>
											<div class="<?= $class_detail_spec_col ?>">
												<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'GROSS TONNAGE', $data->SpeedWeight->GrossTonnage , ''); ?>
											</div>
											<div class="<?= $class_detail_spec_col ?>">
												<?php 
													echo apply_filters(
														'yatco_single_yacht_format_key_spec_table', 
														'DISPLACEMENT', 
														($data->is_eur)?$data->SpeedWeight->DisplacementKG:$data->SpeedWeight->DisplacementLBS,
														''
													);
												 ?>
											</div>
											<div class="<?= $class_detail_spec_col ?>">
												<?php if (strlen($data->BasicInfo->City.' '.$data->BasicInfo->State.' '.$data->BasicInfo->Country) >= 2) : ?>
													<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'LOCATION', apply_filters('yatco_full_address', $data->BasicInfo, false, true), ''); ?>
												<?php else : ?>
													<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'LOCATION', 'N/A', ''); ?>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>

								<div class="yt-text-right">
									<?php 
										echo do_shortcode('[yatco-powered-by]');
									?>
								</div>
							</div>
						</div>

						<div class="yt-col-lg-4 yt-hidden-sm yt-hidden-md yachtdetail_right">						
							<div class="inner">
								<div class="YachtDetail_ContactForm">
								    <div style="padding-bottom: 10px; color: #003470; font-size: 14px; font-weight: bold; text-transform: uppercase;">
								        Get more information on this listing
								    </div>

								    <div>
										<form action="" method="post" id="template-form-vessel-details" class="contact-broker-form">            
											<input type="hidden" name="VesselID" value="<?= $data->BasicInfo->VesselID ?>">
								            <input type="hidden" name="CompanyID" value="<?= $data->Company->CompanyID ?>">
								            <input type="hidden" name="ReferrerUrl" value="<?= $data->a_url ?>">
								            <input type="hidden" name="FormTypeID" value="9">

								            <input type="text" name="contact_me_by_fax_only" style="display:none !important" tabindex="-1" autocomplete="off">

								          	<div class='hide-after-submit'>
									            <div class="">
									            	<div class="yt-row row-smaller">
										                <div class="yt-col-md-6 yt-col-12">
										                	<div class="yt-input-block">
										                    	<input  type="text" name="FirstName" placeholder="First Name*" required="required" class="yt-input">
										               		</div>
										                </div>

										                <div class="yt-col-md-6 yt-col-12">
										                	<div class="yt-input-block">
										                    	<input type="text" name="LastName" placeholder="Last Name*" required="required" class="yt-input">
										               		</div>
										                </div>
										            </div>

										           	<div class="yt-row row-smaller">
										                <div class="yt-col-md-6 yt-col-12">
										                	<div class="yt-input-block">
											                    <input class="yt-input" type="email" name="Email" placeholder="Email Address*" required="required">
											                </div>
											            </div>

										                <div class="yt-col-md-6 yt-col-12">
										                	<div class="yt-input-block">
											                    <input class="yt-input" type="tel" name="Phone" placeholder="Phone Number">
											                </div>
											            </div>
											        </div>

											       	<div class="yt-row row-smaller">
										                <div class="yt-col-12">
										                	<div class="yt-input-block">
											                    <textarea rows="5" class="yt-input" placeholder="Message*" name="Message" required="required" style="resize: none;"></textarea>
											                </div>

									            			<div class="yt-input-block">
										                		<div class="yatco-g-recaptcha" data-sitekey="<?= $options->getOption('google_recap_api_key') ?>"></div>
											            	</div>

									            			<div class="yt-input-block">
										                    	<input type="submit" class="yt-btn yt-btn-block" value="MESSAGE BROKER" style="border-radius: 0px !important;" />
											            	</div>
											            </div>
										            </div>
									            </div>
									        </div>

									        <div class="form-success-message" style="display: none; font-size: 75%;">
											    <div class="yt-alert yt-alert-success yt-text-center" role="alert">
											    	<?php 
											    		$success_message = $options->getOption('lead_success_message');

											    		if ($success_message == '') {
											    			if ($options->is_brokerage_site) :
											    				$success_message = 'Your form has been submitted. <br> We will contact you shortly.';
										    				else :
										    					$success_message = 'Your form has been submitted. <br> The listing broker will contact you shortly.';
										    				endif;
										    			}

											    		echo $success_message;
											    	?>		    
											    </div>
											</div>
										</form>
								    </div>

								</div>
							</div>
						</div>
					</div>
				</div>

				<?php if (count($data->PhotoGallery) != 0) : ?>
					<div class="gallery-panel" id="gallery">
						<!-- <h3 class="section-heading">
							GALLERY
						</h3> -->

						<div class="section-content read-more-on-photos">
							<div id="lightgallery" class="yt-row" data-vessel-id="<?= $data->BasicInfo->VesselID ?>">
								<?php if (isset($data->BasicInfo) && isset($data->BasicInfo->VirtualTourURL) && $data->BasicInfo->VirtualTourURL != '') : ?>
									<a href="<?= $data->BasicInfo->VirtualTourURL ?>" data-iframe="true" class="noLightbox">
										<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
											<div class="pic-wrapper">
												<div class="pic-overlay" style="text-align: center;">360 TOUR</div>
												
												<div class="icon tour"></div>

												<img src="<?= $data->PhotoGallery[0]->mediumImageURL ?>" alt="MEDIA 360 TOUR" loading="lazy" class="pic" />
											</div>
										</div>
									</a>
								<?php endif; ?>

								<?php foreach ($data->Videos as $vid) : 

									preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $vid->VideoUrl, $matches);

									if (isset($matches[1])) {
										$y_id=$matches[1]; 
									}

									if (isset($y_id) && $y_id != null) :

										?>
										<a href="<?= $vid->VideoUrl ?>" class="noLightbox">
											<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
												<div class="pic-wrapper">
													<div class="pic-overlay">PLAY VIDEO</div>
													
													<div class="icon play"></div>

													<picture>
												        <source media="(min-width: 650px)" srcset="https://img.youtube.com/vi/<?= $y_id ?>/maxresdefault.jpg">
											        	<img src="https://img.youtube.com/vi/<?= $y_id ?>/mqdefault.jpg" alt="VIDEO <?= $y_id ?>" loading="lazy" class="pic" />
											      	</picture>
												</div>
											</div>
										</a>
									<?php else : ?>
										<a href="<?= $vid->VideoUrl ?>" data-iframe="true">
											<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
												<div class="pic-wrapper">
													<div class="pic-overlay" style="text-align: center;">360 TOUR</div>
													
													<div class="icon tour"></div>

													<img src="<?= $data->PhotoGallery[0]->mediumImageURL ?>" alt="MEDIA 360 TOUR" loading="lazy" class="pic" />
												</div>
											</div>
										</a>
									<?php endif; ?>	

								<?php endforeach; ?>

								<?php foreach ($data->PhotoGallery as $pic) : ?>
									
									<a href="<?= $pic->largeImageURL ?>" class="noLightbox">
										<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
											<div class="pic-wrapper">
												<div class="pic-overlay">VIEW GALLERY</div>
												
												<picture>
											        <source media="(min-width: 650px)" srcset="<?= $pic->mediumImageURL ?>">
										        	<img src="<?= $pic->smallImageURL ?>" alt="<?= $pic->Caption ?>" loading="lazy" class="pic" />
										      	</picture>
											</div>
										</div>
									</a>

								<?php endforeach; ?>
							</div>

						</div>
						
					</div>
				<?php endif; ?>

				<div class="yt-container yachtdetail_left">
					<?php if ($data->Sections != null && count($data->Sections) > 0) : ?>
						<div id="details" style="margin-top: 2%;">
							<h3 class="section-heading" style="border-bottom: 0px">
								FULL SPECIFICATIONS
							</h3>

							<div class="section-content">
								<?php foreach ($data->Sections as $sec) : ?>
									<h4 class="accordion">
										<?= $sec->SectionName ?>
									</h4>
									<div class="_panel">
										<?= $sec->SectionText ?>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

						<div style="height: 15px;"></div>
					<?php endif; ?>	
				</div>


				<!-- Modal: Share -->
				<div id="single-yacht-modal-share" class="yt-modal single-yacht-modal">
					<h4 style="font-size: 30px !important; font-family: initial;">Share With Your Friends</h4>

					<h5 style="font-size: 20px;"><?= $data->BasicInfo->Builder.' '.$data->BasicInfo->ModelYear; ?> </h5>
					
					<div class="addthis_inline_share_toolbox"></div>

					<div style="margin-left: -12px;">
						<div class="addthis_toolbox addthis_default_style"> 
						  <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a> 
						</div>
					</div>
				</div>

				<script type="text/javascript">
					if (typeof _YCT != 'undefined') {
						window.addEventListener('load', function() {
							_YCT.forsale.view("<?= $data->Result->VesselID ?>");
						});
					}
				</script>
				<?php 
			}
		}

	?>

</main><!-- #site-content -->

<?php get_footer();
