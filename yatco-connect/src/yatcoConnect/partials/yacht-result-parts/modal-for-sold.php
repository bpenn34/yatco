<div id="yatco-contact-modal" class="yt-modal single-yacht-modal single-yacht-modal-email-broker">
	<form action="" method="post" class="contact-broker-for-real-form">
        <input type="hidden" name="CompanyID">
        <input type="hidden" name="BrokerID">
        <input type="hidden" name="BrokerCompanyID">
        <input type="hidden" name="ReferrerUrl">
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