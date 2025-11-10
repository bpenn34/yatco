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
				
				//var_dump($data);
				
				?>
				<div class="yt-container">
					<div id="top-links">
						<ul class="breadcrumb yt-pull-left" id="breadcrumb">
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

						<a href="<?= $back_to_search; ?>" class="yt-pull-right">
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

							foreach($data->PhotoGallery as $p_index => $img) {
								$image_url=htmlentities($img->largeImageURL);
								$medium_image_url=htmlentities($img->mediumImageURL);	
								$small_image_url=htmlentities($img->smallImageURL);	

								if ($p_index == 1) :
									echo '<div class="carousel-cell">';
										echo sprintf('<img data-flickity-lazyload-srcset="%s 650w, %s 320w" sizes="(min-width: 650px) 650px, 320px"  src="%s" alt="%s" class="pic" />',
											$medium_image_url,
											$small_image_url,
											( wp_is_mobile() )?$small_image_url:$medium_image_url,
											htmlentities($img->Caption)
										);
										
										/*echo sprintf('<img data-flickity-lazyload-src="%s" alt="%s" class="pic" />',
											$image_url,
											htmlentities($img->Caption)
										);*/
									echo '</div>';
									
								else :
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
								endif; 

								
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
							<div class="yt-col-12">
								<div class=" nav-links">
									<a href="#overview">Overview</a>
									<a href="#features">Features</a>
									<a href="#gallery">Gallery</a>

									<a href="#single-yacht-modal-share" data-modal="#single-yacht-modal-share">Share</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="ycd-mini-specs-container" id="single-yacht-quick-specs">
					<div class="ycd-mini-specs">
						<div class="ycd-specs-values">
							<?= $data->BasicInfo->YearBuilt ?>
						</div>
						<div class="ycd-specs-label">
							Year
						</div>
					</div>

					<div class="ycd-mini-specs yt-hidden-md yt-hidden-sm">
						<div class="ycd-specs-values">
							<?= $data->BasicInfo->Builder ?>
						</div>
						<div class="ycd-specs-label">
							Builder
						</div>
					</div>

					<div class="ycd-mini-specs">
						<div class="ycd-specs-values">
							<?php if ($options->is_euro_site) : ?>
								<?= $data->Dimensions->LOA_Meters ?>

							<?php else : ?>
								<?php if (isset($data->Dimensions->LOA) && !empty($data->Dimensions->LOA)): ?>
									<?= $data->Dimensions->LOA ?>
								<?php else : ?>
									N/A
								<?php endif; ?>
							<?php endif; ?>
						</div>
						<div class="ycd-specs-label">
							Length
						</div>
					</div>

					<div class="ycd-mini-specs yt-hidden-md yt-hidden-sm">
						<div class="ycd-specs-values">
							<?= $data->Accommodations->StateRooms ?>
						</div>
						<div class="ycd-specs-label">
							Cabins
						</div>
					</div>
				</div>

				<div id="overview"></div>

				<div class="yt-container" id="single-yacht-about-the-boat" style="background-color: transparent !important;">
					<div class="yt-row">
						<div class="yt-col-lg-8 yt-col-12 yachtdetail_left">
							<div class="inner">
								<div id="single-yacht-content">
									<div class="ycd-top-details">
										<p style="text-align: center;">
											<img src="<?= YATCO_PLUGIN_ASSETS.'/img/Yatco-Logo-ice-Blue.png' ?>" loading="lazy" alt="yatco anchor icon" title="yatco anchor icon" style="display: inline-block;" class="yatco-anchor-icon">
										</p>

										<h1 class="ycd-title" style="line-height: 1.25; margin-bottom:  0px; ">
											<?= ''. $data->BasicInfo->BoatName .' - '. $data->BasicInfo->YearBuilt .' '. $data->Dimensions->LOAFeet .' '. $data->BasicInfo->Builder .' '. $data->BasicInfo->Model .'' ?> 

										</h1>

										<h2 class="ycd-title" style="line-height: 1.25; margin-top: 0px; font-size: 30px;">
											This Yacht Is No Longer Available
										</h2>
									</div>

									<hr>

									<div id="single-yach-read-more">
										<div id='overview'></div>

										<div id="ycd-description" class="ag-readmore">
											<?php 
												$vessel_short_description = $data->VD->VesselDescriptionShortDescriptionNoStyles;

												echo $vessel_short_description;
											?>
										</div>
									</div>

									<hr>

									<?php // include 'yachtdetails-mobile-broker-section.php'; ?>

									<div id="yc-details-specs">
										<div id="features"></div>

										<div class="yt-row">
											<?php $class_detail_spec_col="yt-col-6 yt-col-md-3"; ?>
											
											<div class="<?= $class_detail_spec_col ?>">
												<?=
													apply_filters(
														'yatco_single_yacht_format_key_spec_table', 
														'LENGTH',
														$data->Dimensions->LOAFeet,
														$data->Dimensions->LOAMeter 
													); 
												?>
											</div>
											<div class="<?= $class_detail_spec_col ?>">
												<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'BUILDER', $data->BasicInfo->Builder); ?>
											</div>
											<div class="<?= $class_detail_spec_col ?>">
												<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'ASKING PRICE', 'This Yacht Is No Longer Available'); ?>
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
												<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'BEAM', $data->Dimensions->BeamFeet, $data->Dimensions->BeamMeter); ?>
											</div>
											<div class="<?= $class_detail_spec_col ?>">
												<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'DRAFT', $data->Dimensions->DraftFeet, $data->Dimensions->DraftMeter); ?>
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
												<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'STATEROOMS', $data->Accommodations->StateRooms, ''); ?>
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
												<?php if (strlen($data->BasicInfo->City.' '.$data->BasicInfo->State.' '.$data->BasicInfo->Country) >= 3) : ?>
													<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'LOCATION', apply_filters('yatco_full_address', $data->BasicInfo, false, true), ''); ?>
												<?php else : ?>
													<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'LOCATION', 'N/A', ''); ?>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>

						<div class="yt-col-lg-4 yt-hidden-sm yt-hidden-md yachtdetail_right">						
							<div class="inner">
								<div style="padding-bottom: 10px; color: #003470; font-size: 12px; font-weight: bold; text-transform: uppercase;">
							       LOOKING FOR A SIMILAR LISTING OR HAVE QUESTIONS FOR THE SELLING BROKER?
							    </div>

								<form action="" method="post" class="sold-contact-broker-for-real-form">
							        <input type="hidden" name="CompanyID" value="<?= $data->Company->CompanyID ?>">
							        <input type="hidden" name="BrokerID" value="<?= $data->Brokers[0]->BrokerID ?>">
							        <input type="hidden" name="BrokerCompanyID" value="<?= $data->Brokers[0]->CompanyID ?>">
							        <input type="hidden" name="ReferrerUrl" value="<?= $data->a_url ?>">
							        <input type="hidden" name="FormTypeID" value="9">

							        <input type="text" name="contact_me_by_fax_only" style="display:none !important" tabindex="-1" autocomplete="off">

							        <div class='hide-after-submit'>
							        	<div class="yt-row">
							        		<div class="yt-col-md-6 yt-col-12">
												<div class="yt-input-block">
													<input type="text" name="FirstName" placeholder="First Name*" required="" class="yt-input">
												</div>
											</div>
							        		<div class="yt-col-md-6 yt-col-12">
												<div class="yt-input-block">
													<input type="text" name="LastName" placeholder="Last Name*" required="" class="yt-input">
												</div>   
											</div>
										</div>

										<div class="yt-row">
							        		<div class="yt-col-md-6 yt-col-12">
												<div class="yt-input-block">
													<input type="email" name="Email" placeholder="Insert Email*" required="" class="yt-input">
												</div>
											</div>
							        		<div class="yt-col-md-6 yt-col-12">
												<div class="yt-input-block">
													<input class="yt-input" type="tel" name="Phone" placeholder="Phone Number">
												</div>
											</div>
										</div>

										<!-- <div class="yt-input-block">
											<input type="text" name="currentboat" placeholder="Current Boat Owned" class="yt-input">
										</div> -->

										<!-- <div class="yt-input-block">
											<input type="text" name="homeport" placeholder="Home Port" required="" class="yt-input">
										</div> -->

										<div class="yt-input-block">
											<!-- <textarea id="subject" name="subject" placeholder="I'm interested in Listing Name..." required="" class="yt-input"></textarea> -->

											 <textarea rows="5" class="yt-input" placeholder="Message*" name="Message" required=""></textarea>
										</div>

										<!-- <div class="yt-input-block">
											<label>
												<input type="checkbox" name="Brochure" value="Brochure" class="yt-input"> 
												Send me a Brochure
											</label>
											
											<label>
												<input type="checkbox" name="Price-Changes" value="Price-Changes" class="yt-input"> 
												Email me with Price Changes
											</label>

											<label>
												<input type="checkbox" name="Terms-Conditons" value="Terms-Conditions" checked="" class="yt-input"> 
												I agree terms &amp; conditions<br><br>
											</label>
										</div>	 -->

										<div class="yt-input-block">
											<div class="yatco-g-recaptcha" data-sitekey="<?= $options->getOption('google_recap_api_key') ?>"></div>
										</div>

										<!-- <button type="submit" class="yt-btn">Contact Listing Broker</button> -->  
										<button type="submit" class="yt-btn yt-btn-block">MESSAGE BROKER</button>  
									</div>

									 <div class="form-success-message" style="display: none; font-size: 75%;">
									    <div class="yt-alert yt-alert-success yt-text-center" role="alert">
									    	Your form has been submitted.<br> The listing broker will contact you shortly.
									    </div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<?php if (count($data->PhotoGallery) != 0) : ?>
					<div class="gallery-panel" id="gallery" itemscope itemtype="http://schema.org/ImageGallery">
						<!-- <h3 class="section-heading">
							GALLERY
						</h3> -->

						<div class="section-content read-more-on-photos" id="yeahmangallery">
							<div id="lightgallery" class="yt-row" data-vessel-id="<?= $data->BasicInfo->VesselID ?>">
								<?php if (isset($data->BasicInfo) && isset($data->BasicInfo->VirtualTourURL) && $data->BasicInfo->VirtualTourURL != '') : ?>
									<figure data-src="<?= $data->BasicInfo->VirtualTourURL ?>" data-iframe="true">
										<a href="<?= $data->BasicInfo->VirtualTourURL ?>" data-iframe="true" class="noLightbox">
											<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
												<div class="pic-wrapper">
													<div class="pic-overlay" style="text-align: center;">360 TOUR</div>
													
													<div class="icon tour"></div>

													<img src="<?= $data->PhotoGallery[0]->mediumImageURL ?>" alt="MEDIA 360 TOUR" loading="lazy" class="pic" />
												</div>
											</div>
										</a>
										
									</figure>
								<?php endif; ?>

								<?php foreach ($data->Videos as $vid) : 

									preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $vid->VideoUrl, $matches);

									if (isset($matches[1])) {
										$y_id=$matches[1]; 
									}

									if (isset($y_id) && $y_id != null) :

										?>
										<figure data-src="<?= $vid->VideoUrl ?>" >
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
											
										</figure>
									<?php else : ?>
										<figure data-src="<?= $vid->VideoUrl ?>"  data-iframe="true" >
											<a href="<?= $vid->VideoUrl ?>" data-iframe="true" class="noLightbox">
												<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
													<div class="pic-wrapper">
														<div class="pic-overlay" style="text-align: center;">360 TOUR</div>
														
														<div class="icon tour"></div>

														<img src="<?= $data->PhotoGallery[0]->mediumImageURL ?>" alt="MEDIA 360 TOUR" loading="lazy" class="pic" />
													</div>
												</div>
											</a>
										</figure>
									<?php endif; ?>	

								<?php endforeach; ?>

								<?php foreach ($data->PhotoGallery as $pic_index => $pic) : 
									$gallery_href = (wp_is_mobile())?$pic->mediumImageURL:$pic->largeImageURL;
									?>
									<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" data-src="<?= $pic->largeImageURL ?>">
										<a href="<?= $gallery_href ?>" class="noLightbox" itemprop="contentUrl">	
											<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
												<div class="pic-wrapper">
													<div class="pic-overlay">VIEW GALLERY</div>
													
													<picture>
												        <source media="(min-width: 650px)" srcset="<?= $pic->mediumImageURL ?>">
											        	<img src="<?= $pic->smallImageURL ?>" itemprop="thumbnail" alt="<?= $data->BasicInfo->BoatName.' '.$pic_index.' '.$pic->Caption ?>" loading="lazy" class="pic" />
											      	</picture>
												</div>
											</div>

											<figcaption itemprop="caption description" style="display: none;"><?= $data->BasicInfo->BoatName.' '.$pic_index.' '.$pic->Caption ?></figcaption>
										</a>
									</figure>
								<?php endforeach; ?>
							</div>

						</div>
						
					</div>
				<?php endif; ?>
				
				<div class="yt-container" style="margin-top: 4%; margin-bottom: 4%;" id="similar-yachts">
					<div class="yt-row">
						<div class="yt-col-12">
							
							<h2 class="yt-text-center">Find Similar Yachts for Sale</h2>

							<hr>

							<?php echo do_shortcode('[yatco-yachts-results FeaturedNotFirst="1" view-all-button="1" page_size="24" sort="2" MainCategoryID="'. $data->BasicInfo->MainCategoryID .'" pagination=0][/yatco-yachts-results]'); ?>
						</div>
					</div>
					
				</div>

				<?php 

				
			}
		}

	?>

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


</main><!-- #site-content -->

<?php get_footer();
