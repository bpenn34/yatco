
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
?>

<main id="site-content" role="main">

	<?php

		if ( have_posts() ) {

			while ( have_posts() ) {
				the_post();
				$data=$post->boss_data;

				//var_dump($data);
				?>
					<div class="yt-pre-container">
					<div class="yt-container section-spacing"  style="margin-top: 60px;">
						<div class="yt-row">
							<div class="yt-col-12 yt-col-md-3 broker-col">
								<div class="inner">
									<div class="card-container">
									    <div class="yt-row display-flex">
									    
								            <div class="yt-col-12">
								                <div class="card broker">
								                	<a href='<?= $data->a_url; ?>'>
								                        <div class="pic pic-std" style="background-image: url('<?= esc_attr($data->IMG) ?>'); background-size: cover;"></div>
								                    </a>
													
													<div class="below-picture">
									                	<a href='<?= $data->a_url; ?>'>
								                            <div class="card-title">
								                                <?= $data->FirstName.' '.$data->LastName ?>
								                            </div>
								                        </a>

								                        <div class="card-title" style="margin-bottom: 30px;">
									                        <?php 

																if (isset($data->Office) && isset($data->Office->Country)) {
																	echo apply_filters('yatco_full_address', $data->Office, false, true);
																}
																elseif (isset($data->Company) && isset($data->Company->Country)) {
																	echo apply_filters('yatco_full_address', $data->Company, false, true);
																}

															?>
														</div>

														<?php if ($data->Stats != null) : ?>
															<div class="card-title card-name greyfont" style="font-size: 90%;">
																<p><b>LISTINGS:</b> <?= $data->Stats->VesselCount ?></p>

																<p><b>LOA RANGE:</b> <?= apply_filters('yatco_range', $data->Stats->StartLOAFormatted, $data->Stats->EndLOAFormatted, false) ?></p>

																<p><b>PRICE RANGE:</b> <?= apply_filters('yatco_range', $data->Stats->PriceRangeUSD->Start, $data->Stats->PriceRangeUSD->End, true) ?></p>
															</div>
														<?php endif; ?>
							                         	
														<br>

														<div class="addthis_toolbox addthis_default_style broker-addthis-toolbox" addthis:url="<?= $data->a_url; ?>" style="margin-left: -12px;"> 
														  <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a> 
														</div>

									                    <div class="btnRow" style="margin-top: 0px; position: static;">
										                    <?php if (! empty($data->OfficePhone) || ! empty($data->MobilePhone)) : ?>
																<!-- href="#single-yacht-modal-call-broker" rel="modal:open"  -->											
																<a data-modal="#single-broker-modal-call" class="yt-btn yt-btn-alt-dark-blue yt-btn-radius broker-btn ycd-broker-call">CALL</a>
															<?php endif; ?>

															<?php if (! empty($data->Email)) : ?>
																<!-- href="#single-yacht-modal-email-broker" rel="modal:open"  -->
																<a data-modal="#single-broker-modal-email" class="yt-btn yt-btn-alt-dark-blue yt-btn-radius broker-btn ycd-broker-email">EMAIL</a> 
															<?php endif; ?>
										               		
										               		<div class="yt-clearfix"></div>

										               		<br>
									               		</div>
									               	</div>
								           	 	</div>
								           	 </div>
									    </div>
									</div>

									<?php
									/*
										<a href="<?= $data->Company->a_url ?>">
											<img src="<?= $data->Company->ImageMediumUrl ?>" alt="<?= $data->CompanyName ?>" style="display: block; width: 100%; max-width: 320px; margin: auto;">
										</a>
									*/
									?> 
									
									<br>
									<?php if (in_array(3443, $data->Company->CompanyTypeIDs)) : ?>
										<a href="<?= $data->Company->a_url ?>">< Back to Company Page</a>

									<?php else : ?>
										<a href="<?= $data->Company->a_url ?>">< Back to Brokerage Page</a>

									<?php endif; ?>
								</div>
							</div>

							<div class="yt-col-12 yt-col-md-9">
								
								<div class="yt-center brokage-img-name-description">
									
									<h1 class="heading-font-size">
										<?= ($data->FirstName.' '.$data->LastName) ?>											
									</h1>

									<?php if ($data->Country == 'United States') : ?>
										<h2 style="font-size: 24px">
											<?= $data->Company->CompanyName.' '. $data->Office->City.' '.$data->Office->State ?>
										</h2>
									<?php else : ?>
										<h2 style="font-size: 24px">
											<?= $data->Company->CompanyName.' '. $data->Office->City.' '.$data->Office->Country ?>
										</h2>

									<?php endif; ?>
										
									<hr>

									<p>				

										<?php if (isset($data->StrippedBio) && ! empty($data->StrippedBio)) : ?>
											<?php echo $data->StrippedBio; ?>
										
										<?php else : ?>
											<?= apply_filters('yatco_seo_broker_first_sentence', ''); ?>

											<?= apply_filters('yatco_seo_broker_last_sentence', ''); ?>
											
										<?php endif; ?>

									</p>
								</div>
							</div>
						</div>
					</div>
					</div>

					<hr style="margin-top: 0px; margin-bottom: 120px; " />
					<div class="yt-pre-container">
						<div class="yt-container" style="margin-bottom: 60px;">
							
							<h2 class="heading-font-size"><?= $data->Company->CompanyName ?> - Yachts For Sale</h2>
						
							<hr>

					        <?php 
					        	if ($data->Yachts->Count > 0) :

									echo do_shortcode('[yatco-js-yachts-results BrokerID="'. $data->AccountID  .'" sort="false" load-more-button="1" pagination=0][/yatco-js-yachts-results]');	

								else: 
									echo do_shortcode('[yatco-js-yachts-results CompanyID="'. $data->CompanyID  .'" sort="false" load-more-button="1" pagination=0][/yatco-js-yachts-results]');	

								endif;
							?>
							
					        <!-- <hr>

					        <a href="">View More</a> -->
								
						</div>
					</div>


					<!-- Modal: Call Broker -->
					<div id="single-broker-modal-call" class="yt-modal single-yacht-modal single-yacht-modal-call-broker" style="max-width: 300px;">
						<div class="headings">
							<h4>Call Broker</h4>

							<div class="yt-input-block">
								<h5 class="broker-name"><?= $data->FirstName.' '.$data->LastName ?></h5>
								<h5 class="brokerage"><?= $data->Company->CompanyName ?></h5>
							</div>

							<hr>
						</div>

						<?php if (isset($data->OfficePhone) && $data->OfficePhone != '') : ?>
							<div class="yt-input-block">
								<a href="tel: <?= $broker->Phone ?>" onclick="GA_TrackTele('broker-<?= $data->BrokerID ?>', 'Office');">
									<button type="button" class="yt-btn yt-btn-block">
										Office: <?= $data->OfficePhone ?>
									</button>
								</a>
							</div>
						<?php endif; ?>

						<?php if (isset($data->MobilePhone) && $data->MobilePhone !='') : ?>
							<div class="yt-input-block">
								<a href="tel: <?= $broker->Mobile ?>" onclick="GA_TrackTele('broker-<?= $data->BrokerID ?>', 'Mobile');">
									<button type="button" class="yt-btn yt-btn-block">
										Mobile: <?= $data->MobilePhone ?>
									</button>
								</a>
							</div>
						<?php endif; ?>

						<div class="yt-input-block">
							<button type="button" class="yt-btn yt-btn-block yt-btn-alt-blue" data-modal="#single-yacht-modal-email-broker" style="width: 200px; margin: auto;">
								EMAIL INSTEAD
							</button>
						</div>
					</div>

					<!-- Modal: Email Broker -->
					<div id="single-broker-modal-email" class="yt-modal single-yacht-modal single-yacht-modal-email-broker">
						<div class="headings">
							<h4>Contact Broker</h4>

							<div class="yt-input-block">
								<h5 class="broker-name"><?= $data->FirstName.' '.$data->LastName ?></h5>
								<h5 class="brokerage"><?= $data->Company->CompanyName ?></h5>
							</div>
						</div>

						<form action="" method="post" class="contact-broker-for-real-form">
					        <input type="hidden" name="CompanyID" value="<?= $data->Company->CompanyID ?>">
					        <input type="hidden" name="BrokerID" value="<?= $data->BrokerID ?>">
					        <input type="hidden" name="BrokerCompanyID" value="<?= $data->Company->CompanyID ?>">
					        <input type="hidden" name="ReferrerUrl" value="<?= $data->a_url ?>">
					        <input type="hidden" name="FormTypeID" value="9">

					        <input type="text" name="contact_me_by_fax_only" style="display:none !important" tabindex="-1" autocomplete="off">

					        <div class='hide-after-submit'>
								<div class="yt-input-block">
									<input type="text" name="FirstName" placeholder="First Name*" required="" class="yt-input">
								</div>

								<div class="yt-input-block">
									<input type="text" name="LastName" placeholder="Last Name*" required="" class="yt-input">
								</div>   

								<div class="yt-input-block">
									<input type="email" name="Email" placeholder="Insert Email*" required="" class="yt-input">
								</div>

								<div class="yt-input-block">
									<input class="yt-input" type="tel" name="Phone" placeholder="Phone Number">
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
				<?php 
			}
		}

	?>

</main><!-- #site-content -->

<?php get_footer();
