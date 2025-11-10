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
$proData = new yatcoConnect_ApiToBoss();



?>

<main id="site-content" role="main">

	<?php

		if ( have_posts() ) {

			while ( have_posts() ) {
				the_post();
				$data=$post->boss_data;
				
				//var_dump($data);
				//var_dump($proData->common_builder_models(["BuilderModelID" => 21]));

				?>
				<div class="yt-pre-container">
				<div class="yt-container section-spacing" style="margin-top: 60px;">
					<div class="yt-row">
						<div class="yt-row-12">
							<div class="yt-center">
								
								<img src="<?= $data->ImageMediumUrl ?>" alt="<?= $data->CompanyName ?>" style="display: inline-block; max-width: 320px;">

								<hr>
								
								<h1 class="heading-font-size">
									<?= apply_filters('yatco_seo_heading_company_builder', $data->CompanyName) ?>
								</h1>

								<?php if (isset($data->AboutCompany) && !empty($data->AboutCompany)) : ?>

									<p style="white-space: pre-line;">
										<?= $data->AboutCompany ?>
									</p>

								<?php else : ?>

									<p>
										<?= apply_filters('yatco_seo_paragraph_company_builder', $data->CompanyName) ?>
									</p>

								<?php endif; ?>

							</div>
						</div>
					</div>
				</div>

				<div class="yt-container section-spacing">
					<div class="yt-row">
						<div class="yt-row-12">

							<h2 class="heading-font-size"><?= $data->CompanyName ?> Yachts for Sale</h2>

							<hr>

							<?php 
								echo do_shortcode('[yatco-js-yachts-results CompanyID="'. $data->CompanyID  .'" sort="false" load-more-button="1" pagination=0][/yatco-js-yachts-results]');
							?>

							<div style="text-align: center; margin-top: 5px;">
						    	<a href="<?= $options->getOption('yacht_search_url'); ?>">
						    		Start New Search
						    	</a>
						    </div>
						</div>
					</div>
				</div>

				<div class="yt-container section-spacing">
					<div class="yt-row">
						<div class="yt-col-12">
							<h3 class="heading-font-size yt-center"><?= $data->CompanyName ?> - Locations</h3>

							<hr>

							<div class="yt-row">
								<?php foreach ($data->Offices as $company_office) : ?>

									<div class="col-office yt-col-12 yt-col-md-6 yt-col-lg-4">
										<div style="margin-bottom: 15px">
											
											<h4 style="font-weight: bold;"><?= $company_office->OfficeName ?></H4>
											<h5><?= $company_office->City ?>, <?= $company_office->Country ?></h5>

											<?php if ($company_office->Phone != '') : ?>
												<a href="tel: <?= $company_office->Phone ?>" style="color: #636363;">
													<?= $company_office->Phone ?>
												</a>
											<?php else: ?>
												<br>
											<?php endif; ?>
										</div>
									</div>

								<?php endforeach; ?>
							</div>

						</div>
					</div>
				</div>

				<div class="yt-container" style='margin-bottom: 60px;'>
					<div class="yt-row">
						<div class="yt-row-12">
							<h3 class="heading-font-size"><?= $data->CompanyName ?> - Sales Representative</h3>

							<hr>

							<div class="card-container">
							    <div class="yt-row display-flex">
							    	<?php foreach ($data->Brokers as $broker) : ?>
							    		<?php 
							    			//var_dump($broker); 
							    			
							    			$contact_onclick = esc_attr(sprintf(
									            " openBrokerContactModal(\"%s\", \"%s\", \"%s\"); ",
									            $broker->BrokerID,
									            addslashes($broker->CompanyName),
									            addslashes($broker->FullName)
									        ));
									        
							    		?>

							            <div class="yt-col-lg-3 yt-col-md-6 yt-col-12">
							                <div class="card broker">
							                	<a href='<?= $broker->a_url; ?>'>
							                        <div class="pic pic-std">
							                        	<img src="<?= esc_attr($broker->IMG) ?>" alt="<?= $broker->FullName ?>" loading="lazy" />
        												<div class="overlay"></div>
							                        </div>
							                    </a>
													
												<div class="below-picture">
								                	<a href='<?= $broker->a_url; ?>'>
							                            <div class="card-title">
							                                <?= $broker->FullName ?>
							                            </div>
							                        </a>

							                        <div class="card-title" style="margin-bottom: 30px;">
								                        <?php 

															if (isset($broker->Office) && isset($broker->Office->Country)) {
																echo apply_filters('yatco_full_address', $broker->Office, false, true);
															}
															elseif (isset($broker->Company) && isset($broker->Company->Country)) {
																echo apply_filters('yatco_full_address', $broker->Company, false, true);
															}

														?>
													</div>
						                            
						                            <?php if ($broker->Stats != null) : ?>
							                            <div class="card-title card-name greyfont" style="font-size: 90%;">
															<p><b>LISTINGS:</b> <?= $broker->Stats->VesselCount ?></p>

															<p><b>LOA RANGE:</b> <?= apply_filters('yatco_range', $broker->Stats->StartLOAFormatted, $broker->Stats->EndLOAFormatted, false) ?></p>

															<p><b>PRICE RANGE:</b> <?= apply_filters('yatco_range', $broker->Stats->PriceRangeUSD->Start, $broker->Stats->PriceRangeUSD->End, true) ?></p>
														</div>
													<?php endif; ?>
						                         								                         										            
								                    <div class="btnRow" style="position: static;">
									                    <a href="<?= $broker->a_url ?>" class="greyfont" style="float: left;">DETAILS</a>

									                    <span style="float: right;" class="yt-btn yt-btn-alt-dark-blue yt-btn-radius " onclick="<?= $contact_onclick ?>">
									                    	CONTACT
									                    </span>
									               		
									               		<div class="yt-clearfix"></div>
								               		</div>
								               	</div>
							           	 	</div>
							           	 </div>
							       	<?php endforeach; ?>
							    </div>
							</div>


						</div>
					</div>
				</div>
				</div>

				<!-- Modal: Email Broker -->
				<div id="single-company-modal-email-broker" class="yt-modal single-yacht-modal single-yacht-modal-email-broker">
					<div class="headings">
						<h4>Contact Broker</h4>

						<div class="yt-input-block">
							<h5 class="broker-name"></h5>
							<h5 class="brokerage"></h5>
						</div>
					</div>

					<form action="" method="post" class="contact-broker-for-real-form">
				        <input type="hidden" name="CompanyID" value="<?= $data->CompanyID ?>">
				        <input type="hidden" name="BrokerID" value="">
				        <input type="hidden" name="BrokerCompanyID" value="<?= $data->CompanyID ?>">
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
