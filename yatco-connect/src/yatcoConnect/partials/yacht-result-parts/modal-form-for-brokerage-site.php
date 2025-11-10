<div id="yatco-contact-modal" class="yt-modal single-yacht-modal single-yacht-modal-email-broker">
	<form action="#" id="brokerage-form-vessel-results" class="contact-broker-form">

	    <input type="hidden" name="VesselID" value="">
	    <input type="hidden" name="CompanyID" value="">
	    <input type="hidden" name="FormTypeID" value="9">

   		<input type="hidden" name="ReferrerUrl" value="<?= get_permalink(get_the_ID()) ?>">

   		<input type="checkbox" name="contact_me_by_fax_only" value="1" style="display:none !important" tabindex="-1" autocomplete="off">

	    <div class='hide-after-submit'>
	    	<div class="headings">
		    	<h4>
		    		<?php 
			    		$lead_modal_title = $options->getOption('lead_modal_title');

			    		if ($lead_modal_title == '') {
			    			$lead_modal_title = 'Contact Broker';
		    			}

			    		echo $lead_modal_title;
			    	?>	
		    	</h4>
			</div>

		    <div class="yt-input-block">
		      <input type="text" name="FirstName" placeholder="First Name*" required="" class="yt-input">
		    </div>

		    <div class="yt-input-block">
		      <input type="text" name="LastName" placeholder="Last Name*" required="" class="yt-input">
		    </div>

		    <div class="yt-input-block">
		      <input type="email" name="Email" placeholder="Email Address*" required="" class="yt-input">
		    </div>

		    <div class="yt-input-block">
		      <input type="tel" name="Phone" placeholder="Phone Number" class="yt-input">
		    </div>

		    <div class="yt-input-block">
		    	<textarea name="Message" placeholder="Message" required="" class="yt-input" rows='5' style="resize: none;"></textarea>
		    </div>

		    <!-- <div class="yt-input-block">
		    	<label>
		    		<input type="checkbox" name="allow_to_contact" required="">

		    		Please click here to confirm your consent to being contacted by YATCO in regards to the interests you have indicated above. For further details please see our Privacy Policy.
		    	</label>
		    </div> -->

		    <div class="yt-input-block">
         	
            	<div class="yatco-g-recaptcha" data-sitekey="<?= $options->getOption('google_recap_api_key'); ?>"></div>

            </div>

		    <input type="submit" value="Contact Broker" class="yt-btn yt-btn-block" />
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
