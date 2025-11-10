<!-- <div class="yt-container yachtdetail_left"> -->
	<?php if (count($data->Photos) != 0) : ?>
		<div class="gallery-panel" id="gallery">
			<!-- <h3 class="section-heading">
				GALLERY
			</h3> -->

			<div class="section-content read-more-on-photos" id="yeahmangallery">
				<div id="lightgallery" class="yt-row" style="margin-left: 0px; margin-right: 0px;">
					<?php if(isset($data->Videos) && !empty($data->Videos)) { ?>
					<?php foreach ($data->Videos as $vid) : 
					preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $vid->FullVideoUrl, $matches);
					$y_id=$matches[1];?>

						<div data-src="<?= $vid->FullVideoUrl ?>">
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
						</div>
					<?php endforeach; ?>
					<?php } ?>

					<?php foreach ($data->Photos as $pic) :
						$gallery_href = (wp_is_mobile())?$pic->mediumImageURL:$pic->largeImageURL;
						?>
					
						<div data-src="<?= $gallery_href ?>">
							<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
								<div class="pic-wrapper">
									<div class="pic-overlay">VIEW GALLERY</div>
						
						        	<picture>
							        	<source media="(min-width: 650px)" srcset="<?= $pic->mediumImageURL ?>">
							        	<img src="<?= $pic->smallImageURL ?>" alt="<?= $pic->Caption ?>" loading="lazy" class="pic" />
							      	</picture>				
								</div>
							</div>
						</div>

					<?php endforeach; ?>
				</div>

			</div>
			
		</div>
	<?php endif; ?>
<!-- </div> -->

<div class="yt-yacht-details-new-full-spec" style="background-color: #f8f8f8; padding: 4% 0; margin-bottom: 4%;">
	<div class="yt-container yt-text-center">
		
		<h2>Full Specifications</h2>

		<hr>

		<p>Additional specifications are available for this listing. Click below to get detailed information. </p>
		
		<br>

		<button data-modal="#single-charter-modal-email-broker-about-full-spec" class="yt-btn">GET FULL SPECIFICATIONS</button>

	</div>
</div>

<div id="single-yacht-brokers" style="padding-top: 50px; padding-bottom: 50px;">
	<div class="yt-container">
		<div class="yt-row max-900-row">

			<?php if (! $options->is_brokerage_site) : ?>
				<div class="yt-col-12 yt-col-lg-8">
					<h3>
						Listing Marketed by
					</h3>
					
					<div class="yt-row">
						<?php if (count($data->BrokerList) > 0) : ?>
							<?php foreach ($data->BrokerList as $b_index => $broker) : ?>
								<?php //var_dump($broker); ?>

								<div class="ycd-broker yt-col-12 yt-col-md-12">
									<div class="yt-row">
										<div class="yt-col-4">
											
											<?php if (isset($broker->PhotoURL) && $broker->PhotoURL != "https://placehold.it/300x150/") : ?>
												<!-- <a href="<?= $broker->a_url ?>"> -->
													<img alt="<?= $broker->BrokerName ?> Photo <?= $broker->BrokerID ?> Side" src="<?= $broker->PhotoURL ?>" loading="lazy" style="max-width: unset;" />
												<!-- </a>  -->
											<?php endif; ?>

											<a href="<?= $data->Company->a_url ?>">	
												<img src="<?= $broker->BrokerCompanyLogoURL ?>" alt="<?= $broker->BrokerCompanyName ?>" loading="lazy" style="max-width: unset;">
											</a>
										</div>

										<div class="yt-col-8 ycd-broker-d">
											<!-- <a href="<?= $broker->a_url ?>" style="color: #fff;"> -->
												<?= $broker->BrokerName ?>
											<!-- </a> -->
											<br>

											<a href="<?= $data->Company->a_url ?>" style="color: #fff;">
												<?= $broker->BrokerCompanyName ?>
											</a>
											
											<br>

											<?php 

												if (isset($broker->Office) && isset($broker->Office->Country)) : 
													echo apply_filters('yatco_full_address', $broker->Office, true, true); 

												elseif (isset($broker->Company) && isset($broker->Company->Country)) :
													echo apply_filters('yatco_full_address', $broker->Company, true, true); 

												endif;

											?>

										</div>
									</div>
								</div>
							<?php endforeach; ?>
						<?php else: ?>
							<div class="ycd-broker yt-col-12 yt-col-md-12">
								<div class="yt-row">
									<div class="yt-col-4">
										<a href="<?= $data->Company->a_url ?>">	
											<img src="<?= $data->CompanyLogoURL ?>" alt="<?= $data->CompanyName ?>" loading="lazy" style="max-width: unset;">
										</a>
									</div>

									<div class="yt-col-8 ycd-broker-d">
										
										<a href="<?= $data->Company->a_url ?>" style="color: #fff;">
											<?= $data->CompanyName ?>
										</a>
										
										<br>

										<?php 

											if (isset($data->Company) && isset($data->Company->Country)) :
												echo apply_filters('yatco_full_address', $data->Company, true, false); 
											endif;

										?>

									</div>
								</div>
							</div>

						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			
			<div class="yt-col-12 yt-col-lg-4">
				
				<div class="ycd-broker-form" id="ycd-broker-form">
					<h3>More information or schedule a showing</h3>

					<form action="" method="post" class="charter-lead-form">            
						<input type="hidden" name="VesselID" value="<?= $data->VesselID ?>">
			            <input type="hidden" name="CompanyID" value="<?= $data->CompanyID ?>">
			            <input type="hidden" name="ReferrerUrl" value="<?= $data->a_url ?>">
			            <input type="hidden" name="FormTypeID" value="3">
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
						            </div>
						        </div>
								
								<div class="yt-row row-smaller">
						        	 <div class="yt-col-12">
					                	<div class="yt-input-block">
											<input type="date" name="IdealCheckIn" placeholder="Ideal Check In Date" class="yt-input">
										</div>
					                </div>
					            </div>

						        <div class="yt-row row-smaller">
									<div class="yt-col-12 yt-col-md-6">
										<div class="yt-input-block">
											<input type="text" name="Duration" placeholder="Duration Of Stay" class="yt-input">
										</div>
									</div>
									<div class="yt-col-12 yt-col-md-6">
										<div class="yt-input-block">
											<select name="RateFreq" class="yt-input">
												<option value="Days">Days</option>
												<option value="Weeks">Weeks</option>
											</select>
										
										</div>
									</div>
								</div>

								<div class="yt-row row-smaller">
					                <div class="yt-col-12">
					                	<div class="yt-input-block">
											<label style="color: #fff;">
												<input type="radio" name="owner_or_industry" value="owner">
												I am interested in chartering the vessel.
											</label>
											<label  style="color: #fff;">
												<input type="radio" name="owner_or_industry" value="industry">
												I work in the yachting industry.
											</label>
										</div>
					                </div>
								</div>

						       	<div class="yt-row row-smaller">
					                <div class="yt-col-12">
					                	<div class="yt-input-block">
					                		<div class="yatco-g-recaptcha" data-sitekey="<?= $options->getOption('google_recap_api_key') ?>"></div>
						            	</div>

				            			<div class="yt-input-block yt-text-center">
					                    	<input type="submit" class="yt-btn yt-btn-block yt-btn-darker-blue" value="CONTACT LISTING BROKER" style="border-radius: 0px !important;" />
						            	</div>
						            </div>
					            </div>
				            </div>
				        </div>

				        <div class="form-success-message" style="display: none; font-size: 75%;">
						    <div class="yt-alert yt-alert-success yt-text-center" role="alert">
						    	Your form has been submitted. <br>
						    	The listing broker will contact you shortly.
						    </div>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>
</div>
