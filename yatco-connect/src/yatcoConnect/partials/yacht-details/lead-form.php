<div class="yatco-grabbed-yacht-details-lead-form">
	<form action="" method="post" id="shortcode-form-vessel-details" class="contact-broker-form">
		<input type="hidden" name="VesselID" value="<?= $vessel->BasicInfo->VesselID ?>">
        <input type="hidden" name="CompanyID" value="<?= $vessel->Company->CompanyID ?>">
        <input type="hidden" name="ReferrerUrl" value="<?= $vessel->a_url ?>">
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
		        	<div class="yt-col-12">
						<div class="yt-input-block">
							<input type="email" name="email" placeholder="Email*" required class="yt-input">
						</div>

						<div class="yt-input-block">
							<input type="tel" name="phone" placeholder="Phone Number" class="yt-input">
						</div>
						
						<!-- <div class="yt-input-block">									
							<input type="text" name="current-boat" placeholder="Current Boat Owned" class="yt-input">
						</div> -->

						<div class="yt-input-block">								
							<textarea rows="10" id="subject" name="subject" placeholder="I'm interested in Listing Name..." required class="yt-input"></textarea>
						</div>
						
						<div class="yt-input-block">								
							<div class="yatco-g-recaptcha" data-sitekey="<?= $options->getOption('google_recap_api_key') ?>"></div>
						</div>

						<div class="yt-input-block">								
							<button type="submit" value="message broker" class="yt-btn yt-btn-darker-blue">CONTACT BROKER</button>
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
		    	Your form has been submitted. The listing broker will contact you shortly.
		    </div>
		</div>
	</form>
</div>