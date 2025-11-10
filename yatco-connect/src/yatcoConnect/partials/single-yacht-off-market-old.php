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
				// var_dump($data);
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

						<a href="<?= $back_to_search; ?>" class="yt-pull-right">
							&lt; Back to search
						</a>

						<div class="yt-clearfix"></div>
					</div>
				</div>
				
				<!-- Top Slider Gallery -->		

				<div id="off-the-market-header" style="background-image: url('<?= $data->PhotoGallery[0]->largeImageURL ?>'); ">
					<div class="overlay">
						<a href="#similar-yachts"><h2>Find Similar Yachts for Sale</h2></a>
						<a href="#new-search"><h3>Or Start a New Search</h3></a>
					</div>
				</div>		

				<div class="yt-container" id="single-yacht-about-the-boat" style="background-color: transparent !important;">
					<div class="yt-row">
						<div class="yt-col-12 yachtdetail_left">
							<div class="inner">
								<div class="ycd-top-details">
									<p style="text-align: center;">
										<img src="<?= YATCO_PLUGIN_ASSETS.'/img/Yatco-Logo-ice-Blue.png' ?>" alt="yatco anchor icon" title="yatco anchor icon" style="display: inline-block;" class="yatco-anchor-icon">
									</p>

									<h1 class="ycd-title" style="line-height: 1.25; text-align: center;">
										<div class="mobile-only">
											<?= $data->BasicInfo->BoatName ?> - 
											<span class="mobile-hide">
											<?= $data->BasicInfo->YearBuilt .' '. $data->Dimensions->LOAFeet .' '. $data->BasicInfo->Builder .' '. $data->BasicInfo->Model ?>
											</span>
										</div>
									</h1>
								</div>

								<hr>
								
								<div class="yt-container yachtdetail_right">
									<div class="inner">
										<div class="YachtDetail_ContactForm">
										    <div style="padding-bottom: 10px; color: #003470; font-size: 12px; font-weight: bold; text-transform: uppercase;">
										       LOOKING FOR A SIMILAR LISTING OR HAVE QUESTIONS FOR THE SELLING BROKER?
										    </div>

										    <div>
												<form action="" method="post" class="contact-broker-for-real-form">
											        <input type="hidden" name="CompanyID" value="<?= $data->Company->CompanyID ?>">
											        <input type="hidden" name="BrokerID" value="<?= $data->Brokers[0]->BrokerID ?>">
											        <input type="hidden" name="BrokerCompanyID" value="<?= $data->Brokers[0]->CompanyID ?>">
											        <input type="hidden" name="ReferrerUrl" value="<?= $data->a_url ?>">
											        <input type="hidden" name="FormTypeID" value="9">

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

								<div class="yt-container" style="margin-top: 4%; margin-bottom: 4%;" id="new-search">

									<div class="yt-row">
										<div class="yt-col-12">
											
											<h2 class="yt-text-center" style="margin-bottom: 30px;">Start New Search</h2>

											<?php echo do_shortcode('[yatco-yachts-quick-search][/yatco-yachts-quick-search]'); ?>
										</div>
									</div>
									
								</div>


								<div class="yt-container" style="margin-top: 4%; margin-bottom: 4%;" id="similar-yachts">
									<div class="yt-row">
										<div class="yt-col-12">
											
											<h2 class="yt-text-center">Find Similar Yachts for Sale</h2>

											<hr>

											<?php echo do_shortcode('[yatco-yachts-results FeaturedNotFirst="1" view-all-button="1" page_size="24" sort="2" MainCategoryID="'. $data->BasicInfo->MainCategoryID .'" pagination=0][/yatco-yachts-results]'); ?>
										</div>
									</div>
									
								</div>

							</div>
						</div>
					</div>
				</div>

				<div id="single-yacht-brokers">
					<div class="yt-container">
						<div class="yt-row">
							<div class="yt-col-12">
								
								<div class="ycd-broker-form3" id="ycd-broker-Form">
									<h3>Contact A Brokerage for More Information</h3>

									<form action="" method="post" class="contact-broker-for-real-form">
								        <input type="hidden" name="CompanyID" value="<?= $data->Company->CompanyID ?>">
								        <input type="hidden" name="BrokerID" value="<?= $data->Brokers[0]->BrokerID ?>">
								        <input type="hidden" name="BrokerCompanyID" value="<?= $data->Brokers[0]->CompanyID ?>">
								        <input type="hidden" name="ReferrerUrl" value="<?= $data->a_url ?>">
								        <input type="hidden" name="FormTypeID" value="9">

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
				</div>

				<script type="text/javascript">
					if (typeof _YCT != 'undefined') {
						window.addEventListener('load', function() {
							_YCT.forsale.view("<?= $data->Result->VesselID ?>");
						});
					}
				</script>

				<!-- <div class="yt-container" id="single-yacht-about-the-boat" style="background-color: transparent !important;">
					<div class="yt-row">
						<div class="yt-col-lg-8 yt-col-12 yachtdetail_left">
							<div class="inner">
								<?php //include 'yacht-off-market/left.php'; ?>
							</div>
						</div>

						<div class="yt-col-lg-4 yt-hidden-sm yt-hidden-md yachtdetail_right">						
							<div class="inner">
								<?php// include 'yacht-off-market/right.php'; ?>
							</div>
						</div>
					</div>
				</div> -->

				<?php 


				// include 'single-yacht-parts/yachtdetails-modals.php';
			}
		}

	?>

</main><!-- #site-content -->

<?php get_footer();
