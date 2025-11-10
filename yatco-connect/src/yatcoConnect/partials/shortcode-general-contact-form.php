<div class="yatco-shortcode-general-contact-form">
	<form action="" method="post" class="yatco-general-contact-form">            
        <input type="hidden" name="CompanyID" value="0">

        <?php 
        	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        ?>

        <input type="hidden" name="ReferrerUrl" value="<?= $current_url ?>">
        <input type="hidden" name="FormTypeID" value="1">

        <input type="text" name="contact_me_by_fax_only" style="display:none !important" tabindex="-1" autocomplete="off">

      	<div class='hide-after-submit'>
            <div class="">
            	<div class="yt-row row-smaller">
	                <div class="yt-col-md-6 yt-col-12">
	                	<div class="yt-input-block">
	                    	<input  type="text" name="FirstName" placeholder="First Name*" required="required" class="yt-input FormInput">
	               		</div>
	                </div>

	                <div class="yt-col-md-6 yt-col-12">
	                	<div class="yt-input-block">
	                    	<input type="text" name="LastName" placeholder="Last Name*" required="required" class="yt-input FormInput">
	               		</div>
	                </div>
	            </div>

	           	<div class="yt-row row-smaller">
	                <div class="yt-col-md-6 yt-col-12">
	                	<div class="yt-input-block">
		                    <input class="yt-input FormInput" type="email" name="Email" placeholder="Email Address*" required="required">
		                </div>
		            </div>

	                <div class="yt-col-md-6 yt-col-12">
	                	<div class="yt-input-block">
		                    <input class="yt-input FormInput" type="tel" name="Phone" placeholder="Phone Number">
		                </div>
		            </div>
		        </div>

		       	<div class="yt-row row-smaller">
	                <div class="yt-col-12">
	                	<div class="yt-input-block">
		                    <textarea rows="5" class="yt-input FormInput" placeholder="Message*" name="Message" style="resize: none;"></textarea>
		                </div>
            			<div class="yt-input-block">
	                		<div class="yatco-g-recaptcha" data-sitekey="<?= $options->getOption('google_recap_api_key') ?>"></div>
		            	</div>
		            	<div class="yt-col-12">
		            		<div class="yt-input-block yt-text-center">
	                    		<input type="submit" class="yt-btn MessageBtn" value="MESSAGE" style="border-radius: 0px !important; width: 200px;"/>
		            		</div>
		            	</div>
            			
		            </div>
	            </div>
            </div>
        </div>

        <div class="form-success-message" style="display: none; font-size: 75%;">
		    <div class="yt-alert yt-alert-success yt-text-center" role="alert">
		    	Thank you! Your form has been submitted
		    </div>
		</div>
	</form>
</div>