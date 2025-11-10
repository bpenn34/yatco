<?php if (count($data->PhotoGallery) != 0) : ?>
	<div class="gallery-panel" id="gallery">
		<!-- <h3 class="section-heading">
			GALLERY
		</h3> -->

		<div class="section-content read-more-on-photos">
			<div id="lightgallery" class="yt-row" data-vessel-id="<?= $data->BasicInfo->VesselID ?>">
				<?php if (isset($data->BasicInfo) && isset($data->BasicInfo->VirtualTourURL) && $data->BasicInfo->VirtualTourURL != '') : ?>
					<figure data-src="<?= $data->BasicInfo->VirtualTourURL ?>" data-iframe="true">
						<div data-src="<?= $data->BasicInfo->VirtualTourURL ?>" data-iframe="true" class="noLightbox">
							<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
								<div class="pic-wrapper">
									<div class="pic-overlay" style="text-align: center;">360 TOUR</div>
									
									<div class="icon tour"></div>

									<img src="<?= $data->PhotoGallery[0]->mediumImageURL ?>" alt="MEDIA 360 TOUR" loading="lazy" class="pic" />
								</div>
							</div>
						</div>
						
					</figure>
				<?php endif; ?>

				<?php foreach ($data->Videos as $vid) : 

					preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $vid->VideoUrl, $yt_matches);

					if (isset($yt_matches[1])) {
						$y_id=$yt_matches[1]; 
					}

					if(preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/?(showcase\/)*([0-9))([a-z]*\/)*([0-9]{6,11})[?]?.*/", $vid->VideoUrl, $vimeo_output)) {
					    $vimeo_id=$vimeo_output[6];
					}

					if (isset($y_id) && $y_id != null) :

						?>
						<figure data-src="<?= $vid->VideoUrl ?>" >
							<div data-src="<?= $vid->VideoUrl ?>" class="noLightbox">
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
						</figure>
					<?php elseif (isset($vimeo_id)) : ?>
						<figure data-src="<?= $vid->VideoUrl ?>" >
							<div data-src="<?= $vid->VideoUrl ?>" class="noLightbox">
								<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
									<div class="pic-wrapper">
										<div class="pic-overlay">PLAY VIDEO</div>
										
										<div class="icon play"></div>

										<picture>
									        <source media="(min-width: 650px)" srcset="https://img.youtube.com/vi/<?= $y_id ?>/maxresdefault.jpg">
								        	<img src="<?= $data->PhotoGallery[0]->mediumImageURL ?>" alt="VIDEO <?= $y_id ?>" loading="lazy" class="pic" />
								      	</picture>
									</div>
								</div>
							</div>
						</figure>
					<?php else : ?>
						<figure data-src="<?= $vid->VideoUrl ?>"  data-iframe="true" >
							<div data-src="<?= $vid->VideoUrl ?>" data-iframe="true" class="noLightbox">
								<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
									<div class="pic-wrapper">
										<div class="pic-overlay" style="text-align: center;">360 TOUR</div>
										
										<div class="icon tour"></div>

										<img src="<?= $data->PhotoGallery[0]->mediumImageURL ?>" alt="MEDIA 360 TOUR" loading="lazy" class="pic" />
									</div>
								</div>
							</div>
						</figure>
					<?php endif; ?>	

				<?php endforeach; ?>

				<?php foreach ($data->PhotoGallery as $pic_index => $pic) : 
					$gallery_href = (wp_is_mobile())?$pic->mediumImageURL:$pic->largeImageURL;
					?>
					<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" data-src="<?= $pic->largeImageURL ?>">
						<div data-src="<?= $gallery_href ?>" class="noLightbox" itemprop="contentUrl">	
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
						</div>
					</figure>
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

<div id="single-yacht-brokers" class="yt-print-hide">
	<div class="yt-container">
		<div class="yt-row max-900-row">
			<div class="yt-col-lg-2 yt-col-12"><br></div>
			<div class="yt-col-lg-8 yt-col-12">
				
				<div class="ycd-broker-form" id="ycd-broker-Form">
					<h3>
						Interested in this <?= $data->is_boat_by_length?'boat':'yacht'; ?>?
					</h3>

					<form action="" method="post" id="bottom-form-vessel-details" class="contact-broker-form">
						<input type="hidden" name="VesselID" value="<?= $data->BasicInfo->VesselID ?>">
			            <input type="hidden" name="CompanyID" value="<?= $data->Company->CompanyID ?>">
			            <input type="hidden" name="ReferrerUrl" value="<?= $data->a_url ?>">
			           	<input type="hidden" name="FormTypeID" value="9">

			           	<input type="text" name="contact_me_by_fax_only" style="display:none !important" tabindex="-1" autocomplete="off">

			          	<div class='hide-after-submit'>
				            <div class="">
				            	<div class="yt-row row-smaller">
					                <div class="yt-col-6">
					                	<div class="yt-input-block">
					                    	<input class="yt-input" type="text" name="FirstName" placeholder="First Name*" required="required" style="">
					               		</div>
					                </div>

					                <div class="yt-col-6">
					                	<div class="yt-input-block">
					                    	<input class="yt-input" type="text" name="LastName" placeholder="Last Name*" required="required">
					               		</div>
					                </div>
					            </div>

					            <div class="yt-row row-smaller">
					                <div class="yt-col-6">
					                	<div class="yt-input-block">
											<input type="email" name="Email" placeholder="Email*" required class="yt-input">
										</div>
					                </div>

					                <div class="yt-col-6">					                	
										<div class="yt-input-block">
											<input type="tel" name="Phone" placeholder="Phone Number" class="yt-input">
										</div>
					            	</div>
					            </div>

					            <div class="yt-row row-smaller">
						        	<div class="yt-col-12">
										
										<div class="yt-input-block">								
											<textarea rows="10" name="Message" placeholder="I'm interested in Listing Name..." required class="yt-input"></textarea>
										</div>

										<div class="yt-input-block">								
											<div class="yatco-g-recaptcha" data-sitekey="<?= $options->getOption('google_recap_api_key') ?>"></div>
										</div>

										<div class="yt-input-block">								
											<input type="submit" value="CONTACT BROKER" class="yt-btn yt-btn-block yt-btn-darker-blue">
										</div>
									</div>
								</div>

								<!-- <div class="yt-input-block">								
									<label>
										<input type="checkbox" name="Brochure" value="Brochure" class="yt-input"> 
										Send me a Brochure
									</label>
								</div> -->
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
